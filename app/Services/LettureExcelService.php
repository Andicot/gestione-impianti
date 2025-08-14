<?php

namespace App\Services;

use App\Models\ImportazioneCsv;
use App\Models\DispositivoMisura;
use App\Models\LetturaConsumo;
use App\Models\UnitaImmobiliare;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class LettureExcelService
{
    use CsvParsingTrait;

    protected array $logErrori = [];
    protected int $contErrori = 0;
    protected int $dispositiviNuovi = 0;
    protected int $lettureCreate = 0;

    /**
     * Elabora file Excel
     */
    public function elaboraFile(string $filePath, ImportazioneCsv $importazione): array
    {
        $this->resetContatori();

        try {
            // Leggi il file Excel
            $datiExcel = Excel::toArray([], $filePath);

            if (empty($datiExcel) || empty($datiExcel[0])) {
                throw new \Exception("File Excel vuoto o non valido");
            }

            $foglio = $datiExcel[0]; // Prendo il primo foglio
            $righeElaborate = 0;

            // Salta l'header (prima riga)
            for ($i = 1; $i < count($foglio); $i++) {
                $riga = $foglio[$i];

                // Verifica che la riga non sia vuota
                if ($this->rigaVuota($riga)) {
                    continue;
                }

                try {
                    $risultatoRiga = $this->elaboraRigaExcel($riga, $importazione, $i + 1);
                    if ($risultatoRiga['nuovo_dispositivo']) {
                        $this->dispositiviNuovi++;
                    }
                    if ($risultatoRiga['lettura_creata']) {
                        $this->lettureCreate++;
                    }
                    $righeElaborate++;
                } catch (\Exception $e) {
                    $this->aggiungiMessaggio($i + 1, $e->getMessage(), 'errore');
                }
            }

            return [
                'righe_totali' => count($foglio) - 1, // Escludo header
                'righe_elaborate' => $righeElaborate,
                'righe_errore' => $this->contErrori,
                'dispositivi_nuovi' => $this->dispositiviNuovi,
                'letture_create' => $this->lettureCreate,
                'log_errori' => ['errori' => $this->logErrori],
                'metadata_csv' => $this->estraiMetadataExcel($foglio)
            ];

        } catch (\Exception $e) {
            throw new \Exception("Errore nell'elaborazione Excel: " . $e->getMessage());
        }
    }

    /**
     * Elabora singola riga Excel - AGGIORNATO CON LETTERE
     */
    private function elaboraRigaExcel(array $riga, ImportazioneCsv $importazione, int $numeroRiga): array
    {
        // Mappatura colonne Excel usando le lettere
        $matricola = $this->pulisciMatricola($riga[$this->letterToIndex('B')] ?? null); // Colonna B
        $nomeDispositivo = trim($riga[$this->letterToIndex('F')] . ' ' . $riga[$this->letterToIndex('G')]);
        $interno = trim($riga[$this->letterToIndex('E')] ?? ''); // Colonna E
        $dataLettura = $riga[$this->letterToIndex('M')] ?? null; // Colonna M
        $udrAttuali = $riga[$this->letterToIndex('AD')] ?? null; // Colonna AD (UDR precedenti come attuali)
        $udrTotali = $riga[$this->letterToIndex('AE')] ?? null; // Colonna AE
        $comfortTermico = $riga[$this->letterToIndex('AF')] ?? null; // Colonna AF (comfort precedente)
        $statoDispositivo = trim($riga[$this->letterToIndex('R')] ?? ''); // Colonna R
        $ubicazione = trim($riga[$this->letterToIndex('C')] ?? null); // Colonna R

        // Validazione matricola
        if (empty($matricola) || !is_numeric($matricola)) {
            throw new \Exception("Matricola non valida: {$matricola}");
        }

        // Conversione data Excel
        $dataLetturaCarbon = $this->convertiDataExcel($dataLettura);
        if (!$dataLetturaCarbon) {
            throw new \Exception("Data lettura non valida per matricola {$matricola}");
        }

        // Conversione valori numerici
        $udrAttualiFloat = $this->convertiValoreNumerico($udrAttuali);
        $comfortTermicoFloat = $this->convertiValoreNumerico($comfortTermico);
        $udrTotaliFloat = $this->convertiValoreNumerico($udrTotali);

        // Cerca o crea dispositivo
        $dispositivo = DispositivoMisura::firstWhere('matricola', $matricola);
        $nuovoDispositivo = false;

        if (!$dispositivo) {
            $dispositivo = $this->creaDispositivoExcel($matricola, $nomeDispositivo, $interno, $ubicazione, $importazione);
            $nuovoDispositivo = true;
        }

        // Crea lettura consumo
        $letturaCreata = false;
        if ($udrAttualiFloat !== null) {
            $letturaCreata = $this->creaLetturaConsumoExcel(
                $dispositivo,
                $udrAttualiFloat,
                $comfortTermicoFloat,
                $udrTotaliFloat,
                $dataLetturaCarbon,
                $importazione
            );
        }

        // Aggiorna dispositivo
        $this->aggiornaDispositivoExcel($dispositivo, $udrAttualiFloat, $dataLetturaCarbon, $statoDispositivo);

        return [
            'nuovo_dispositivo' => $nuovoDispositivo,
            'lettura_creata' => $letturaCreata
        ];
    }

    /**
     * Estrae metadata dal file Excel - AGGIORNATO CON LETTERE
     */
    private function estraiMetadataExcel(array $foglio): array
    {
        $metadata = [
            'tipo_file' => 'excel',
            'formato' => 'Ripartitori di calore',
            'righe_totali' => count($foglio) - 1
        ];

        // Se ci sono dati, estrai info dalla prima riga dati
        if (isset($foglio[1])) {
            $metadata['indirizzo_campione'] = $foglio[1][$this->letterToIndex('D')] ?? null; // Colonna D - Via e nr. civico
            $metadata['prima_data_lettura'] = $this->convertiDataExcel($foglio[1][$this->letterToIndex('M')] ?? null)?->toDateString(); // Colonna M
        }

        return $metadata;
    }

    /**
     * Verifica se una riga è vuota - AGGIORNATO CON LETTERE
     */
    private function rigaVuota(array $riga): bool
    {
        // Considera vuota se matricola (colonna B) è vuota
        return empty($riga[$this->letterToIndex('B')]);
    }

    /**
     * Converti lettera colonna in indice
     */
    protected function letterToIndex($letter)
    {
        // Converti la lettera in maiuscolo per uniformità
        $letter = strtoupper($letter);

        // Per colonne multi-lettera (AA, AB, etc.)
        $index = 0;
        $length = strlen($letter);

        for ($i = 0; $i < $length; $i++) {
            $index = $index * 26 + (ord($letter[$i]) - ord('A') + 1);
        }

        return $index - 1; // Sottrai 1 perché gli array iniziano da 0
    }

    /**
     * Crea nuovo dispositivo da Excel
     */
    private function creaDispositivoExcel(string $matricola, string $nomeDispositivo, string $interno, string $ubicazione, ImportazioneCsv $importazione): DispositivoMisura
    {
        $dispositivo = new DispositivoMisura();
        $dispositivo->azienda_servizio_id = Auth::user()->aziendaServizio->id;
        $dispositivo->matricola = $matricola;
        $dispositivo->impianto_id = $importazione->impianto_id;
        $dispositivo->concentratore_id = $importazione->concentratore_id;
        $dispositivo->tipo = 'udr';
        $dispositivo->stato_dispositivo = 'attivo';
        $dispositivo->creato_automaticamente = true;
        $dispositivo->ubicazione = $ubicazione;
        $dispositivo->nome_dispositivo = $nomeDispositivo ?: null;
        $dispositivo->descrizione_1 = "Interno: {$interno}";
        $dispositivo->descrizione_2 = 'Importato da Excel';
        $dispositivo->save();

        return $dispositivo;
    }

    /**
     * Crea lettura consumo da Excel
     */
    private function creaLetturaConsumoExcel(
        DispositivoMisura $dispositivo,
        float             $udrAttuali,
        ?float            $comfortTermico,
        ?float            $udrTotali,
        Carbon            $dataLettura,
        ImportazioneCsv   $importazione
    ): bool
    {
        try {
            // Trova unità immobiliare (opzionale)
            $unitaImmobiliare = $this->trovaUnitaImmobiliare($dispositivo);
            $unitaImmobiliareId = $unitaImmobiliare ? $unitaImmobiliare->id : null;

            // Cerca UDR precedente
            $udrPrecedente = $this->trovaUdrPrecedente($dispositivo, $dataLettura);

            // Crea lettura
            $lettura = new LetturaConsumo();
            $lettura->unita_immobiliare_id = $unitaImmobiliareId;
            $lettura->periodo_id = $importazione->periodo_id;
            $lettura->dispositivo_id = $dispositivo->id;
            $lettura->tipo_consumo = 'volontario';
            $lettura->categoria = 'riscaldamento';
            $lettura->udr_attuale = $udrAttuali;
            $lettura->udr_precedente = $udrPrecedente;
            $lettura->unita_misura = 'UDR';
            $lettura->costo_unitario = 0;
            $lettura->errore = false;
            $lettura->anomalia = false;
            $lettura->importazione_csv_id = $importazione->id;
            $lettura->data_lettura = $dataLettura->toDateString();
            $lettura->ora_lettura = $dataLettura->toTimeString();

            // Dati specifici Excel
            $lettura->comfort_termico_attuale = $comfortTermico;
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
                ->where(function ($query) use ($dispositivo) {
                    $query->where('nominativo_unita', 'like', '%' . $dispositivo->nome_dispositivo . '%')
                        ->orWhere('interno', 'like', '%' . $dispositivo->nome_dispositivo . '%');
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
     * Aggiorna dispositivo con dati Excel
     */
    private function aggiornaDispositivoExcel(
        DispositivoMisura $dispositivo,
        ?float            $udrAttuali,
        Carbon            $dataLettura,
        string            $statoDispositivo
    ): void
    {
        if ($udrAttuali !== null) {
            $dispositivo->ultimo_valore_rilevato = $udrAttuali;
            $dispositivo->data_ultima_lettura = $dataLettura;
        }

        // Aggiorna stato se presente e valido
        if (!empty($statoDispositivo)) {
            $statoNormalizzato = strtolower($statoDispositivo);
            if ($statoNormalizzato === 'ok') {
                $dispositivo->stato_dispositivo = 'attivo';
            } elseif (str_contains($statoNormalizzato, 'riavvio') || str_contains($statoNormalizzato, 'errore')) {
                $dispositivo->stato_dispositivo = 'guasto';
            }
        }

        $dispositivo->save();
    }

    /**
     * Converte valore numerico (gestisce stringhe vuote e null)
     */
    private function convertiValoreNumerico($valore): ?float
    {
        if ($valore === null || $valore === '' || $valore === 0) {
            return null;
        }

        if (is_numeric($valore)) {
            return (float)$valore;
        }

        return null;
    }

    /**
     * Converte data Excel in Carbon
     */
    private function convertiDataExcel($dataExcel): ?Carbon
    {
        if (empty($dataExcel) || !is_numeric($dataExcel)) {
            return null;
        }

        try {
            // Converte numero seriale Excel in data PHP
            $dateTime = ExcelDate::excelToDateTimeObject($dataExcel);
            return Carbon::instance($dateTime);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Pulisce matricola
     */
    private function pulisciMatricola($matricola): ?string
    {
        if (empty($matricola)) {
            return null;
        }

        // Converte in stringa e rimuove spazi
        $matricola = trim((string)$matricola);

        // Rimuove eventuali decimali (.0)
        if (str_contains($matricola, '.')) {
            $matricola = (string)intval(floatval($matricola));
        }

        return $matricola;
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
     * Aggiungi messaggio al log
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
