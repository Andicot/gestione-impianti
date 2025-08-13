<?php

namespace App\Services;

use App\Models\ImportazioneCsv;
use App\Models\DispositivoMisura;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class ImportazioneService
{
    /**
     * Elabora file CSV con letture dispositivi
     */
    public function elaboraCsvLetture($file, array $parametri): array
    {
        $nomeFile = $file->getClientOriginalName();
        $pathFile = $file->store('importazioni/csv', 'public');

        // Crea record importazione
        $importazione = $this->creaRecordImportazione($nomeFile, $pathFile, $parametri);

        try {
            // Legge e analizza il CSV
            $contenutoCsv = Storage::disk('public')->get($pathFile);
            $risultato = $this->analizzaCsvLetture($contenutoCsv, $importazione);

            // Aggiorna statistiche importazione
            $this->aggiornaStatisticheImportazione($importazione, $risultato);

            return [
                'success' => true,
                'importazione' => $importazione,
                'messaggio' => $this->generaMessaggioSuccesso($risultato)
            ];

        } catch (\Exception $e) {
            $this->gestisciErroreImportazione($importazione, $e);

            return [
                'success' => false,
                'importazione' => $importazione,
                'errore' => $e->getMessage()
            ];
        }
    }

    /**
     * Elabora file Excel inventario
     */
    public function elaboraExcelInventario($file, array $parametri): array
    {
        $nomeFile = $file->getClientOriginalName();
        $pathFile = $file->store('importazioni/excel', 'public');

        try {
            $filePath = Storage::disk('public')->path($pathFile);
            $risultato = $this->analizzaExcelInventario($filePath);

            return [
                'success' => true,
                'risultato' => $risultato,
                'messaggio' => $this->generaMessaggioSuccessoExcel($risultato)
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'errore' => "Errore nell'elaborazione Excel: " . $e->getMessage()
            ];
        }
    }

    /**
     * Crea record importazione nel database
     */
    private function creaRecordImportazione(string $nomeFile, string $pathFile, array $parametri): ImportazioneCsv
    {
        $importazione = new ImportazioneCsv();
        $importazione->nome_file = $nomeFile;
        $importazione->path_file = $pathFile;
        $importazione->impianto_id = $parametri['impianto_id'];
        $importazione->concentratore_id = $parametri['concentratore_id'] ?? null;
        $importazione->periodo_id = $parametri['periodo_id'] ?? null;
        $importazione->caricato_da_id = Auth::id();
        $importazione->tipo_caricamento = 'manuale';
        $importazione->ip_mittente = request()->ip();
        $importazione->stato = 'in_elaborazione';
        $importazione->save();

        return $importazione;
    }

    /**
     * Analizza contenuto CSV letture
     */
    private function analizzaCsvLetture(string $contenutoCsv, ImportazioneCsv $importazione): array
    {
        $righe = str_getcsv($contenutoCsv, "\n");

        $metadataCsv = [];
        $righeElaborate = 0;
        $righeErrore = 0;
        $dispositiviNuovi = 0;
        $logErrori = [];

        // Debug: aggiungi info sul CSV
        $logErrori[] = [
            'riga' => 'DEBUG',
            'errore' => 'Informazioni CSV: ' . count($righe) . ' righe totali nel file',
            'dati' => []
        ];

        // Estrae metadata dall'header (prime righe)
        $metadataCsv = $this->estraiMetadataCsv($righe);

        // Trova l'inizio dei dati dispositivi
        $indiceDatiDispositivi = $this->trovaInizioDispositivi($righe);

        $logErrori[] = [
            'riga' => 'DEBUG',
            'errore' => 'Inizio dati dispositivi alla riga: ' . ($indiceDatiDispositivi + 1),
            'dati' => []
        ];

        // Elabora i dati dei dispositivi
        for ($i = $indiceDatiDispositivi; $i < count($righe); $i++) {
            $riga = trim($righe[$i]);
            if (empty($riga)) continue;

            $campi = str_getcsv($riga, ';');
            if (count($campi) < 7) {
                $righeErrore++;
                $logErrori[] = [
                    'riga' => $i + 1,
                    'errore' => 'Riga con troppo pochi campi: ' . count($campi) . ' (minimo 7 richiesti)',
                    'dati' => $campi
                ];
                continue;
            }

            try {
                $nuovoDispositivo = $this->elaboraRigaDispositivo($campi, $importazione, $metadataCsv);
                if ($nuovoDispositivo) {
                    $dispositiviNuovi++;
                }
                $righeElaborate++;
            } catch (\Exception $e) {
                // Sempre incrementa il contatore errori
                $righeErrore++;

                // Logga tutti gli errori, ma con messaggi diversi per le intestazioni
                if (strpos($e->getMessage(), 'Riga di intestazione saltata') !== false) {
                    $logErrori[] = [
                        'riga' => $i + 1,
                        'errore' => 'Riga di intestazione saltata (normale in CSV con header ripetuti)',
                        'dati' => array_slice($campi, 0, 3) // Solo prime 3 colonne per risparmiare spazio
                    ];
                } else {
                    $logErrori[] = [
                        'riga' => $i + 1,
                        'errore' => $e->getMessage(),
                        'dati' => $campi
                    ];
                }
            }
        }

        return [
            'righe_totali' => count($righe) - $indiceDatiDispositivi,
            'righe_elaborate' => $righeElaborate,
            'righe_errore' => $righeErrore,
            'dispositivi_nuovi' => $dispositiviNuovi,
            'log_errori' => $logErrori,
            'metadata_csv' => $metadataCsv
        ];
    }

    /**
     * Estrae metadata dall'header CSV
     */
    private function estraiMetadataCsv(array $righe): array
    {
        $metadataCsv = [];

        if (count($righe) > 0) {
            $primaRiga = str_getcsv($righe[0], ';');
            if (count($primaRiga) >= 6) {
                $metadataCsv = [
                    'serial' => $primaRiga[0] ?? null,
                    'nome_impianto' => $primaRiga[1] ?? null,
                    'indirizzo_impianto' => $primaRiga[2] ?? null,
                    'nome_installatore' => $primaRiga[3] ?? null,
                    'nome_cliente' => $primaRiga[4] ?? null,
                    'data_installazione' => $primaRiga[5] ?? null
                ];
            }
        }

        return $metadataCsv;
    }

    /**
     * Trova l'indice di inizio dati dispositivi nel CSV
     */
    private function trovaInizioDispositivi(array $righe): int
    {
        for ($i = 0; $i < count($righe); $i++) {
            $riga = trim($righe[$i]);
            if (strpos($riga, 'Matricola;Nome Dispositivo') !== false) {
                return $i + 1;
            }
        }
        return 0;
    }

    /**
     * Elabora singola riga dispositivo dal CSV
     */
    private function elaboraRigaDispositivo(array $campi, ImportazioneCsv $importazione, array $metadataCsv): bool
    {
        $matricola = trim($campi[0]);
        $nomeDispositivo = trim($campi[1]) ?: null;
        $descrizione1 = trim($campi[2]) ?: null;
        $descrizione2 = trim($campi[3]) ?: null;
        $data = trim($campi[4]);
        $ora = trim($campi[5]);
        $stato = trim($campi[6]);

        // Fix: Salta le righe di intestazione ripetute
        if ($matricola === 'Matricola' || strtolower($matricola) === 'matricola') {
            throw new \Exception("Riga di intestazione saltata");
        }

        // Validazione matricola più specifica
        if (empty($matricola)) {
            throw new \Exception("Matricola vuota alla colonna 1");
        }

        if (!is_numeric($matricola)) {
            throw new \Exception("Matricola non numerica: '{$matricola}' (tipo: " . gettype($matricola) . ")");
        }

        // Verifica che sia un numero intero positivo
        $matricolaNum = intval($matricola);
        if ($matricolaNum <= 0) {
            throw new \Exception("Matricola non valida: {$matricola} (deve essere un numero positivo)");
        }

        // Cerca o crea dispositivo
        $dispositivo = DispositivoMisura::where('matricola', $matricola)->first();
        $nuovoDispositivo = false;

        if (!$dispositivo) {
            try {
                $dispositivo = new DispositivoMisura();
                $dispositivo->matricola = $matricola;
                $dispositivo->impianto_id = $importazione->impianto_id;
                $dispositivo->concentratore_id = $importazione->concentratore_id;
                $dispositivo->tipo = 'udr';
                $dispositivo->stato = 'attivo';
                $dispositivo->creato_automaticamente = true;
                $dispositivo->nome_dispositivo = $nomeDispositivo;
                $dispositivo->descrizione_1 = $descrizione1;
                $dispositivo->descrizione_2 = $descrizione2;
                $dispositivo->save();
                $nuovoDispositivo = true;
            } catch (\Exception $e) {
                throw new \Exception("Errore creazione dispositivo {$matricola}: " . $e->getMessage());
            }
        }

        // Aggiorna ultima lettura se necessario
        if (count($campi) > 7) {
            $valoreUdr = str_replace(',', '.', trim($campi[7]));
            if (is_numeric($valoreUdr)) {
                try {
                    $dispositivo->ultimo_valore_rilevato = (float)$valoreUdr;

                    // Parsing data e ora se disponibili
                    if (!empty($data) && !empty($ora)) {
                        try {
                            $dataOra = Carbon::createFromFormat('d/m/y H:i', $data . ' ' . $ora);
                            $dispositivo->data_ultima_lettura = $dataOra;
                        } catch (\Exception $e) {
                            // Se il parsing della data fallisce, usa la data corrente
                            $dispositivo->data_ultima_lettura = now();
                        }
                    } else {
                        $dispositivo->data_ultima_lettura = now();
                    }

                    $dispositivo->save();
                } catch (\Exception $e) {
                    throw new \Exception("Errore aggiornamento lettura dispositivo {$matricola}: " . $e->getMessage());
                }
            }
        }

        return $nuovoDispositivo;
    }

    /**
     * Analizza file Excel inventario
     */
    private function analizzaExcelInventario(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $dati = $worksheet->toArray();

        $righeElaborate = 0;
        $righeErrore = 0;
        $logErrori = [];

        // Prima riga contiene gli header
        $headers = $dati[0];

        // Elabora le righe dati
        for ($i = 1; $i < count($dati); $i++) {
            $riga = $dati[$i];
            try {
                // Qui potresti elaborare i dati secondo le tue esigenze
                // Per ora salvo solo le statistiche
                $righeElaborate++;
            } catch (\Exception $e) {
                $righeErrore++;
                $logErrori[] = [
                    'riga' => $i + 1,
                    'errore' => $e->getMessage(),
                    'dati' => $riga
                ];
            }
        }

        return [
            'headers' => $headers,
            'righe_totali' => count($dati) - 1,
            'righe_elaborate' => $righeElaborate,
            'righe_errore' => $righeErrore,
            'log_errori' => $logErrori
        ];
    }

    /**
     * Aggiorna statistiche importazione
     */
    private function aggiornaStatisticheImportazione(ImportazioneCsv $importazione, array $risultato): void
    {
        $importazione->righe_totali = $risultato['righe_totali'];
        $importazione->righe_elaborate = $risultato['righe_elaborate'];
        $importazione->righe_errore = $risultato['righe_errore'];
        $importazione->dispositivi_nuovi = $risultato['dispositivi_nuovi'];

        // Fix: stati più brevi per evitare errore "Data too long"
        if ($risultato['righe_errore'] > 0) {
            $importazione->stato = 'con_errori'; // Era: completato_con_errori
        } else {
            $importazione->stato = 'completato';
        }

        // Limita il log errori per evitare problemi di dimensione
        $logErrori = $risultato['log_errori'];
        if (count($logErrori) > 100) {
            // Tieni i primi 50 e gli ultimi 50 errori
            $primiErrori = array_slice($logErrori, 0, 50);
            $ultimiErrori = array_slice($logErrori, -50);

            $logErrori = array_merge(
                $primiErrori,
                [[
                    'riga' => '...',
                    'errore' => 'Log troncato: mostrati primi 50 e ultimi 50 errori di ' . count($risultato['log_errori']) . ' totali',
                    'dati' => []
                ]],
                $ultimiErrori
            );
        }

        $importazione->log_errori = $logErrori;
        $importazione->metadata_csv = $risultato['metadata_csv'];
        $importazione->save();
    }

    /**
     * Gestisce errore durante importazione
     */
    private function gestisciErroreImportazione(ImportazioneCsv $importazione, \Exception $e): void
    {
        $importazione->stato = 'errore';
        $importazione->log_errori = [['errore_generale' => $e->getMessage()]];
        $importazione->save();
    }

    /**
     * Genera messaggio di successo per CSV
     */
    private function generaMessaggioSuccesso(array $risultato): string
    {
        $messaggio = "CSV elaborato con successo. Righe elaborate: {$risultato['righe_elaborate']}";

        if ($risultato['righe_errore'] > 0) {
            $messaggio .= ", Errori: {$risultato['righe_errore']}";
        }

        if ($risultato['dispositivi_nuovi'] > 0) {
            $messaggio .= ", Dispositivi creati: {$risultato['dispositivi_nuovi']}";
        }

        return $messaggio;
    }

    /**
     * Genera messaggio di successo per Excel
     */
    private function generaMessaggioSuccessoExcel(array $risultato): string
    {
        $messaggio = "Excel elaborato con successo. Righe elaborate: {$risultato['righe_elaborate']}";

        if ($risultato['righe_errore'] > 0) {
            $messaggio .= ", Errori: {$risultato['righe_errore']}";
        }

        return $messaggio;
    }
}
