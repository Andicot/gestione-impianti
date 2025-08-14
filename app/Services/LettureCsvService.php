<?php

namespace App\Services;

use App\Models\ImportazioneCsv;
use App\Models\DispositivoMisura;
use App\Models\LetturaConsumo;
use App\Models\UnitaImmobiliare;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LettureCsvService
{
    use CsvParsingTrait;

    protected array $logErrori = [];
    protected int $contErrori = 0;
    protected int $dispositiviNuovi = 0;
    protected int $lettureCreate = 0;

    /**
     * Elabora contenuto CSV
     */
    public function elaboraContenuto(string $contenutoCsv, ImportazioneCsv $importazione): array
    {
        $this->resetContatori();

        $righe = str_getcsv($contenutoCsv, "\n");
        $metadataCsv = $this->estraiMetadataCsv($righe);
        $indiceDatiDispositivi = $this->trovaInizioDispositivi($righe);

        $righeElaborate = 0;

        // Elabora i dati dei dispositivi
        for ($i = $indiceDatiDispositivi; $i < count($righe); $i++) {
            $riga = trim($righe[$i]);
            if (empty($riga)) continue;

            $campi = str_getcsv($riga, ';');
            if (count($campi) < 7) {
                $this->aggiungiMessaggio($i + 1, "Riga con troppo pochi campi", 'errore');
                continue;
            }

            try {
                $risultatoRiga = $this->elaboraRigaDispositivo($campi, $importazione);
                if ($risultatoRiga['nuovo_dispositivo']) {
                    $this->dispositiviNuovi++;
                }
                if ($risultatoRiga['lettura_creata']) {
                    $this->lettureCreate++;
                }
                $righeElaborate++;
            } catch (\Exception $e) {
                $this->classificaEAggiungiMessaggio($i + 1, $e->getMessage());
            }
        }

        return [
            'righe_totali' => count($righe) - $indiceDatiDispositivi,
            'righe_elaborate' => $righeElaborate,
            'righe_errore' => $this->contErrori,
            'dispositivi_nuovi' => $this->dispositiviNuovi,
            'letture_create' => $this->lettureCreate,
            'log_errori' => $this->logErrori,
            'metadata_csv' => $metadataCsv
        ];
    }

    /**
     * Elabora singola riga dispositivo
     */
    private function elaboraRigaDispositivo(array $campi, ImportazioneCsv $importazione): array
    {
        $matricola = trim($campi[0]);

        // Salta intestazioni
        if ($matricola === 'Matricola') {
            throw new \Exception("Riga di intestazione saltata");
        }

        // Validazione matricola
        if (empty($matricola) || !is_numeric($matricola)) {
            throw new \Exception("Matricola non valida: {$matricola}");
        }

        // Cerca o crea dispositivo
        $dispositivo = DispositivoMisura::firstWhere('matricola', $matricola);
        $nuovoDispositivo = false;

        if (!$dispositivo) {
            $dispositivo = $this->creaDispositivo($campi, $importazione);
            $nuovoDispositivo = true;
        }

        // Crea lettura consumo se ci sono abbastanza campi
        $letturaCreata = false;
        if (count($campi) >= 18) {
            $letturaCreata = $this->creaLetturaConsumo($dispositivo, $campi, $importazione);
        }

        // Aggiorna ultima lettura dispositivo
        $this->aggiornaDispositivo($dispositivo, $campi);

        return [
            'nuovo_dispositivo' => $nuovoDispositivo,
            'lettura_creata' => $letturaCreata
        ];
    }

    /**
     * Crea nuovo dispositivo
     */
    private function creaDispositivo(array $campi, ImportazioneCsv $importazione): DispositivoMisura
    {
        $dispositivo = new DispositivoMisura();
        $dispositivo->azienda_servizio_id = Auth::user()->aziendaServizio->id;
        $dispositivo->matricola = trim($campi[0]);
        $dispositivo->impianto_id = $importazione->impianto_id;
        $dispositivo->concentratore_id = $importazione->concentratore_id;
        $dispositivo->tipo = 'udr';
        $dispositivo->stato = 'attivo';
        $dispositivo->creato_automaticamente = true;
        $dispositivo->nome_dispositivo = trim($campi[1]) ?: null;
        $dispositivo->descrizione_1 = trim($campi[2]) ?: null;
        $dispositivo->descrizione_2 = trim($campi[3]) ?: null;
        $dispositivo->save();

        return $dispositivo;
    }

    /**
     * Crea lettura consumo - AGGIORNATO per unita_immobiliare_id nullable
     */
    private function creaLetturaConsumo(DispositivoMisura $dispositivo, array $campi, ImportazioneCsv $importazione): bool
    {
        try {
            // Trova unità immobiliare (ora opzionale)
            $unitaImmobiliare = $this->trovaUnitaImmobiliare($dispositivo);
            $unitaImmobiliareId = $unitaImmobiliare ? $unitaImmobiliare->id : null;

            // Parse dati
            $dataLettura = $this->parseDataLettura($campi[4], $campi[5]);
            $udrAttuale = $this->parseDecimal($campi[7]);
            $udrTotali = $this->parseDecimal($campi[17]);

            // Validazione valori UDR
            if ($udrAttuale === null) {
                throw new \Exception("Valore UDR attuale non valido");
            }

            // Cerca UDR precedente
            $udrPrecedente = $this->trovaUdrPrecedente($dispositivo, $dataLettura);

            // Crea lettura anche senza unità immobiliare
            $lettura = new LetturaConsumo();
            $lettura->unita_immobiliare_id = $unitaImmobiliareId; // Può essere null
            $lettura->periodo_id = $importazione->periodo_id;
            $lettura->dispositivo_id = $dispositivo->id;
            $lettura->tipo_consumo = 'volontario';
            $lettura->categoria = 'riscaldamento';
            $lettura->ambiente = $dispositivo->nome_dispositivo;
            $lettura->udr_attuale = $udrAttuale;
            $lettura->udr_precedente = $udrPrecedente;
            $lettura->unita_misura = 'UDR';
            $lettura->costo_unitario = 0;
            $lettura->errore = false;
            $lettura->anomalia = $unitaImmobiliareId === null; // Segna come anomalia se non ha unità
            $lettura->importazione_csv_id = $importazione->id;
            $lettura->data_lettura = $dataLettura->toDateString();
            $lettura->ora_lettura = $dataLettura->toTimeString();

            // Dati termici opzionali
            $lettura->comfort_termico_attuale = $this->parseDecimal($campi[10] ?? null);
            $lettura->temp_massima_sonde = $this->parseDecimal($campi[11] ?? null);
            $lettura->udr_totali = $udrTotali;

            $lettura->save();

            return true;

        } catch (\Exception $e) {
            $this->aggiungiMessaggio(0, "Lettura non creata per dispositivo {$dispositivo->matricola}: " . $e->getMessage(), 'errore');
            return false;
        }
    }

    /**
     * Trova unità immobiliare per dispositivo
     */
    private function trovaUnitaImmobiliare(DispositivoMisura $dispositivo): ?UnitaImmobiliare
    {
        // Se il dispositivo ha già un'unità immobiliare associata
        if ($dispositivo->unita_immobiliare_id) {
            $unita = UnitaImmobiliare::find($dispositivo->unita_immobiliare_id);
            if ($unita) {
                return $unita;
            }
        }

        // Cerca per nome dispositivo nell'impianto
        if ($dispositivo->nome_dispositivo) {
            $unitaImmobiliare = UnitaImmobiliare::where('impianto_id', $dispositivo->impianto_id)
                ->where(function($query) use ($dispositivo) {
                    $query->where('nominativo_unita', 'like', '%' . $dispositivo->nome_dispositivo . '%')
                        ->orWhere('interno', $dispositivo->nome_dispositivo);
                })
                ->first();

            if ($unitaImmobiliare) {
                // Associa il dispositivo all'unità immobiliare per il futuro
                $dispositivo->unita_immobiliare_id = $unitaImmobiliare->id;
                $dispositivo->save();
                return $unitaImmobiliare;
            }
        }

        return null;
    }

    /**
     * Trova UDR precedente
     */
    private function trovaUdrPrecedente(DispositivoMisura $dispositivo, Carbon $dataLettura): float
    {
        $letturaPrecedente = LetturaConsumo::where('dispositivo_id', $dispositivo->id)
            ->where('data_lettura', '<', $dataLettura->toDateString())
            ->orderBy('data_lettura', 'desc')
            ->orderBy('ora_lettura', 'desc')
            ->first();

        return $letturaPrecedente ? $letturaPrecedente->udr_attuale : 0.0;
    }

    /**
     * Aggiorna ultima lettura dispositivo
     */
    private function aggiornaDispositivo(DispositivoMisura $dispositivo, array $campi): void
    {
        if (count($campi) > 7) {
            $valoreUdr = $this->parseDecimal($campi[7]);
            if ($valoreUdr !== null) {
                $dispositivo->ultimo_valore_rilevato = $valoreUdr;
                $dispositivo->data_ultima_lettura = $this->parseDataLettura($campi[4], $campi[5]);
                $dispositivo->save();
            }
        }
    }

    /**
     * Reset contatori
     */
    private function resetContatori(): void
    {
        $this->logErrori = [];
        $this->contErrori = 0;
        $this->dispositiviNuovi = 0;
        $this->lettureCreate = 0;
    }

    /**
     * Classifica e aggiungi messaggio al log appropriato
     */
    private function classificaEAggiungiMessaggio(int $riga, string $messaggio): void
    {
        $messaggioLower = strtolower($messaggio);

        // Messaggi informativi (normali)
        $messaggiInfo = [
            'riga di intestazione saltata',
            'header ripetuto',
            'sezione vuota ignorata',
            'normale in csv con header ripetuti'
        ];

        foreach ($messaggiInfo as $pattern) {
            if (str_contains($messaggioLower, $pattern)) {
                // Non aggiungere al log errori - è normale
                return;
            }
        }

        // Solo errori reali vengono contati
        $this->aggiungiMessaggio($riga, $messaggio, 'errore');
    }

    /**
     * Aggiungi messaggio al log con tipo specificato
     */
    private function aggiungiMessaggio(int $riga, string $messaggio, string $tipo = 'errore'): void
    {
        $record = [
            'riga' => $riga,
            'messaggio' => $messaggio,
            'tipo' => $tipo,
            'timestamp' => now()->toDateTimeString()
        ];

        if ($tipo === 'errore') {
            $this->logErrori[] = $record;
            $this->contErrori++;
        }
    }
}
