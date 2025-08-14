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
     * Gestore log intelligente per distinguere errori reali da messaggi informativi
     */
    protected array $logErrori = [];
    protected array $logWarning = [];
    protected array $logInfo = [];
    protected int $contErroriReali = 0;
    protected int $contWarning = 0;
    protected int $contInfo = 0;

    /**
     * Messaggi che NON sono errori reali ma eventi normali
     */
    protected array $messaggiInformativi = [
        'Riga di intestazione saltata',
        'Header ripetuto',
        'Sezione vuota ignorata',
        'Inizio dati dispositivo',
        'Fine sezione dispositivo',
        'Informazioni CSV:',
        'Metadata estratti',
        'Dispositivo già esistente',
        'normale in CSV con header ripetuti'
    ];

    /**
     * Messaggi di warning (problemi minori ma non bloccanti)
     */
    protected array $messaggiWarning = [
        'Valore mancante sostituito',
        'Formato data non standard',
        'Campo opzionale vuoto',
        'Unità di misura non specificata',
        'sostituito con default'
    ];

    /**
     * Elabora file CSV con letture dispositivi
     */
    public function elaboraCsvLetture($file, array $parametri): array
    {
        // Reset dei contatori per ogni importazione
        $this->resetContatori();

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
     * Reset dei contatori log
     */
    private function resetContatori(): void
    {
        $this->logErrori = [];
        $this->logWarning = [];
        $this->logInfo = [];
        $this->contErroriReali = 0;
        $this->contWarning = 0;
        $this->contInfo = 0;
    }

    /**
     * METODO DEBUG - Versione mirata per "Errore non specificato"
     */
    private function aggiungiLog(int $riga, string $messaggio, array $dati = [], string $tipoForzato = null): void
    {
        // DEBUG: Logga solo se il messaggio è vuoto, contiene "non specificato" o è sospetto
        $messaggioSospetto = empty(trim($messaggio)) ||
            str_contains(strtolower($messaggio), 'non specificato') ||
            str_contains(strtolower($messaggio), 'errore senza messaggio') ||
            strlen(trim($messaggio)) < 3;

        if ($messaggioSospetto) {
            \Log::warning("🚨 MESSAGGIO SOSPETTO RILEVATO:", [
                'riga' => $riga,
                'messaggio_originale' => "'{$messaggio}'",
                'messaggio_length' => strlen($messaggio),
                'messaggio_trimmed_length' => strlen(trim($messaggio)),
                'tipo_forzato' => $tipoForzato,
                'dati' => $dati,
                'stack_trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)
            ]);
        }

        // Se il messaggio è vuoto, manteniamo comunque il log per debug
        if (empty(trim($messaggio))) {
            $messaggioOriginale = $messaggio;
            $messaggio = '[Messaggio vuoto alla riga ' . $riga . ']';
            $tipoForzato = 'errore';

            \Log::error("🔴 MESSAGGIO VUOTO SOSTITUITO:", [
                'riga' => $riga,
                'messaggio_originale' => "'{$messaggioOriginale}'",
                'messaggio_sostitutivo' => $messaggio
            ]);
        }

        $tipo = $tipoForzato ?? $this->classificaMessaggio($messaggio);

        $record = [
            'riga' => $riga,
            'tipo' => $tipo,
            'messaggio' => $messaggio,
            'dati' => $dati,
            'timestamp' => now()->toDateTimeString()
        ];

        switch ($tipo) {
            case 'errore':
                $this->logErrori[] = $record;
                $this->contErroriReali++;
                break;
            case 'warning':
                $this->logWarning[] = $record;
                $this->contWarning++;
                break;
            case 'info':
                $this->logInfo[] = $record;
                $this->contInfo++;
                break;
        }
    }

    /**
     * METODO DEBUG - Elabora riga con debug mirato
     */
    private function elaboraRigaDispositivo(array $campi, ImportazioneCsv $importazione, array $metadataCsv): bool
    {
        $aziendaServizioId=Auth::user()->aziendaServizio->id;

        try {
            $matricola = trim($campi[0]);
            $nomeDispositivo = trim($campi[1]) ?: null;
            $descrizione1 = trim($campi[2]) ?: null;
            $descrizione2 = trim($campi[3]) ?: null;
            $data = trim($campi[4]);
            $ora = trim($campi[5]);
            $stato = trim($campi[6]);

            // Salta le righe di intestazione ripetute - QUESTO È NORMALE!
            if ($matricola === 'Matricola' || strtolower($matricola) === 'matricola') {
                throw new \Exception("Riga di intestazione saltata (normale in CSV con header ripetuti)");
            }

            // Validazione matricola
            if (empty($matricola)) {
                throw new \Exception("Errore: Matricola vuota alla colonna 1");
            }

            if (!is_numeric($matricola)) {
                throw new \Exception("Errore: Matricola non numerica: '{$matricola}'");
            }

            $matricolaNum = intval($matricola);
            if ($matricolaNum <= 0) {
                throw new \Exception("Errore: Matricola non valida: {$matricola} (deve essere un numero positivo)");
            }

            // Cerca o crea dispositivo
            $dispositivo = DispositivoMisura::firstWhere('matricola', $matricola);
            $nuovoDispositivo = false;

            if (!$dispositivo) {
                try {
                    $dispositivo = new DispositivoMisura();
                    $dispositivo->azienda_servizio_id = $aziendaServizioId;
                    $dispositivo->matricola = $matricola;
                    $dispositivo->impianto_id = $importazione->impianto_id;
                    $dispositivo->concentratore_id = $importazione->concentratore_id;
                    $dispositivo->tipo = 'udr';
                    $dispositivo->stato_dispositivo = 'attivo';
                    $dispositivo->creato_automaticamente = true;
                    $dispositivo->nome_dispositivo = $nomeDispositivo;
                    $dispositivo->descrizione_1 = $descrizione1;
                    $dispositivo->descrizione_2 = $descrizione2;
                    $dispositivo->save();
                    $nuovoDispositivo = true;
                } catch (\Exception $e) {
                    // DEBUG: Logga gli errori di creazione dispositivo
                    \Log::error("🔴 ERRORE CREAZIONE DISPOSITIVO:", [
                        'matricola' => $matricola,
                        'exception_message' => $e->getMessage(),
                        'exception_class' => get_class($e),
                        'dati_dispositivo' => [
                            'impianto_id' => $importazione->impianto_id,
                            'concentratore_id' => $importazione->concentratore_id,
                            'nome_dispositivo' => $nomeDispositivo
                        ]
                    ]);

                    // Mostra il messaggio REALE dell'errore database
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
                                // Warning per formato data non standard ma non bloccante
                                $dispositivo->data_ultima_lettura = now();
                                throw new \Exception("Formato data non standard ma interpretabile per dispositivo {$matricola}");
                            }
                        } else {
                            $dispositivo->data_ultima_lettura = now();
                        }

                        $dispositivo->save();
                    } catch (\Exception $e) {
                        if (str_contains($e->getMessage(), 'Formato data non standard')) {
                            // Re-throw come warning, non errore
                            throw $e;
                        }

                        // DEBUG: Logga gli errori di aggiornamento lettura
                        \Log::error("🔴 ERRORE AGGIORNAMENTO LETTURA:", [
                            'matricola' => $matricola,
                            'valore_udr' => $valoreUdr,
                            'exception_message' => $e->getMessage(),
                            'exception_class' => get_class($e)
                        ]);

                        // Mostra il messaggio REALE dell'errore database
                        throw new \Exception("Errore aggiornamento lettura dispositivo {$matricola}: " . $e->getMessage());
                    }
                }
            }

            return $nuovoDispositivo;

        } catch (\Exception $e) {
            // DEBUG: Logga tutte le exception che escono da questo metodo
            $messaggioException = $e->getMessage();

            if (empty(trim($messaggioException)) || str_contains(strtolower($messaggioException), 'non specificato')) {
                \Log::error("🚨 EXCEPTION SOSPETTA DA elaboraRigaDispositivo:", [
                    'messaggio_exception' => "'{$messaggioException}'",
                    'exception_class' => get_class($e),
                    'matricola' => $campi[0] ?? 'N/A',
                    'campi' => $campi,
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'stack_trace' => $e->getTraceAsString()
                ]);
            }

            // IMPORTANTE: Manteniamo SEMPRE il messaggio originale dell'exception
            throw $e;
        }
    }
    /**
     * METODO DEBUG - Classifica con debug
     */
    private function classificaMessaggio(string $messaggio): string
    {
        $messaggioLower = strtolower($messaggio);

        \Log::info("DEBUG classificaMessaggio:", [
            'messaggio_originale' => $messaggio,
            'messaggio_lower' => $messaggioLower
        ]);

        // Verifica messaggi informativi
        foreach ($this->messaggiInformativi as $pattern) {
            if (str_contains($messaggioLower, strtolower($pattern))) {
                \Log::info("Classificato come INFO per pattern: {$pattern}");
                return 'info';
            }
        }

        // Verifica warning
        foreach ($this->messaggiWarning as $pattern) {
            if (str_contains($messaggioLower, strtolower($pattern))) {
                \Log::info("Classificato come WARNING per pattern: {$pattern}");
                return 'warning';
            }
        }

        // Parole chiave per errori reali
        $paroleChiaveErrore = [
            'errore', 'fallito', 'impossibile', 'non valido', 'mancante richiesto',
            'violazione', 'duplicato', 'non trovato', 'formato errato', 'obbligatorio',
            'creazione dispositivo', 'aggiornamento lettura'
        ];

        foreach ($paroleChiaveErrore as $parola) {
            if (str_contains($messaggioLower, $parola)) {
                \Log::info("Classificato come ERRORE per parola: {$parola}");
                return 'errore';
            }
        }

        // Default: info se non rientra in nessuna categoria
        \Log::info("Classificato come INFO (default)");
        return 'info';
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
        $importazione->versione_log = '2.0'; // Nuova versione con log intelligente
        $importazione->save();

        return $importazione;
    }

    /**
     * Analizza contenuto CSV letture - VERSIONE MIGLIORATA
     */
    private function analizzaCsvLetture(string $contenutoCsv, ImportazioneCsv $importazione): array
    {
        $righe = str_getcsv($contenutoCsv, "\n");

        $metadataCsv = [];
        $righeElaborate = 0;

        // Log informativo iniziale
        $this->aggiungiLog(0, "Informazioni CSV: " . count($righe) . " righe totali nel file", [], 'info');

        // Estrae metadata dall'header (prime righe)
        $metadataCsv = $this->estraiMetadataCsv($righe);
        $this->aggiungiLog(0, "Metadata estratti dall'header CSV", $metadataCsv, 'info');

        // Trova l'inizio dei dati dispositivi
        $indiceDatiDispositivi = $this->trovaInizioDispositivi($righe);
        $this->aggiungiLog($indiceDatiDispositivi + 1, "Inizio dati dispositivi alla riga: " . ($indiceDatiDispositivi + 1), [], 'info');

        $dispositiviNuovi = 0;

        // Elabora i dati dei dispositivi
        for ($i = $indiceDatiDispositivi; $i < count($righe); $i++) {
            $riga = trim($righe[$i]);
            if (empty($riga)) {
                $this->aggiungiLog($i + 1, "Riga vuota ignorata", [], 'info');
                continue;
            }

            $campi = str_getcsv($riga, ';');
            if (count($campi) < 7) {
                $this->aggiungiLog($i + 1, "Riga con troppo pochi campi: " . count($campi) . " (minimo 7 richiesti)", $campi, 'errore');
                continue;
            }

            try {
                $nuovoDispositivo = $this->elaboraRigaDispositivo($campi, $importazione, $metadataCsv);
                if ($nuovoDispositivo) {
                    $dispositiviNuovi++;
                    $this->aggiungiLog($i + 1, "Nuovo dispositivo creato: " . $campi[0], ['matricola' => $campi[0]], 'info');
                }
                $righeElaborate++;
            } catch (\Exception $e) {
                // Classifica automaticamente il tipo di messaggio
                $this->aggiungiLog($i + 1, $e->getMessage(), array_slice($campi, 0, 3));
            }
        }

        // Log finale
        $this->aggiungiLog(0, "Elaborazione completata", [
            'righe_elaborate' => $righeElaborate,
            'errori_reali' => $this->contErroriReali,
            'warning' => $this->contWarning,
            'dispositivi_nuovi' => $dispositiviNuovi
        ], 'info');

        return [
            'righe_totali' => count($righe) - $indiceDatiDispositivi,
            'righe_elaborate' => $righeElaborate,
            'righe_errore' => $this->contErroriReali, // SOLO errori reali!
            'righe_warning' => $this->contWarning,
            'righe_info' => $this->contInfo,
            'dispositivi_nuovi' => $dispositiviNuovi,
            'log_errori' => $this->generaLogCompleto(),
            'metadata_csv' => $metadataCsv
        ];
    }

    /**
     * Genera log completo strutturato
     */
    private function generaLogCompleto(): array
    {
        return [
            'statistiche' => [
                'errori_reali' => $this->contErroriReali,
                'warning' => $this->contWarning,
                'messaggi_info' => $this->contInfo,
                'ultima_analisi' => now()->toDateTimeString(),
                'versione_log' => '2.0'
            ],
            'errori' => $this->logErrori,
            'warning' => $this->logWarning,
            'info' => array_slice($this->logInfo, -30) // Solo ultimi 30 messaggi info
        ];
    }

    /**
     * Estrae metadata dall'header CSV
     */
    private function estraiMetadataCsv(array $righe): array
    {
        $metadataCsv = [];

        if (count($righe) > 1) {
            $secondaRiga = str_getcsv($righe[1], ';');
            if (count($secondaRiga) >= 6) {
                $metadataCsv = [
                    'serial' => $secondaRiga[0] ?? null,
                    'nome_impianto' => $secondaRiga[1] ?? null,
                    'indirizzo_impianto' => $secondaRiga[2] ?? null,
                    'nome_installatore' => $secondaRiga[3] ?? null,
                    'nome_cliente' => $secondaRiga[4] ?? null,
                    'data_installazione' => $secondaRiga[5] ?? null
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
        return 4; // Default se non trova l'header
    }





    /**
     * Gestisce errore durante importazione - CON MESSAGGIO COMPLETO
     */
    private function gestisciErroreImportazione(ImportazioneCsv $importazione, \Exception $e): void
    {
        $importazione->stato = 'errore';

        // Mantieni il messaggio completo dell'errore
        $messaggioErrore = $e->getMessage();
        if (empty($messaggioErrore)) {
            $messaggioErrore = 'Errore senza messaggio - Tipo: ' . get_class($e);
        }

        $importazione->log_errori = [
            'statistiche' => [
                'errori_reali' => 1,
                'warning' => 0,
                'messaggi_info' => 0,
                'versione_log' => '2.0'
            ],
            'errori' => [[
                'riga' => 'GENERALE',
                'tipo' => 'errore',
                'messaggio' => $messaggioErrore,
                'dati' => [
                    'classe_exception' => get_class($e),
                    'file' => $e->getFile(),
                    'linea' => $e->getLine(),
                    'stack_trace' => $e->getTraceAsString()
                ],
                'timestamp' => now()->toDateTimeString()
            ]]
        ];

        $importazione->save();
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
     * Aggiorna statistiche importazione - VERSIONE MIGLIORATA
     */
    private function aggiornaStatisticheImportazione(ImportazioneCsv $importazione, array $risultato): void
    {
        $importazione->righe_totali = $risultato['righe_totali'];
        $importazione->righe_elaborate = $risultato['righe_elaborate'];
        $importazione->righe_errore = $risultato['righe_errore']; // Solo errori reali!
        $importazione->righe_warning = $risultato['righe_warning'] ?? 0;
        $importazione->righe_info = $risultato['righe_info'] ?? 0;
        $importazione->dispositivi_nuovi = $risultato['dispositivi_nuovi'];

        // Determina stato più accurato
        if ($risultato['righe_errore'] > 0) {
            $importazione->stato = 'con_errori';
        } elseif (($risultato['righe_warning'] ?? 0) > 0) {
            $importazione->stato = 'con_avvisi';
        } else {
            $importazione->stato = 'completato';
        }

        $importazione->log_errori = $risultato['log_errori'];
        $importazione->metadata_csv = $risultato['metadata_csv'];
        $importazione->data_elaborazione = now();
        $importazione->save();
    }



    /**
     * Genera messaggio di successo per CSV - VERSIONE MIGLIORATA
     */
    private function generaMessaggioSuccesso(array $risultato): string
    {
        $messaggi = ["Righe elaborate: {$risultato['righe_elaborate']}"];

        if ($risultato['righe_errore'] > 0) {
            $messaggi[] = "Errori reali: {$risultato['righe_errore']}";
        }

        if (($risultato['righe_warning'] ?? 0) > 0) {
            $messaggi[] = "Avvisi: {$risultato['righe_warning']}";
        }

        if ($risultato['dispositivi_nuovi'] > 0) {
            $messaggi[] = "Dispositivi creati: {$risultato['dispositivi_nuovi']}";
        }

        return "CSV elaborato. " . implode(', ', $messaggi);
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
