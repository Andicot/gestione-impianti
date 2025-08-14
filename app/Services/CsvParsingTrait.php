<?php

namespace App\Services;

use Carbon\Carbon;

trait CsvParsingTrait
{
    /**
     * Estrae metadata dall'header CSV
     */
    protected function estraiMetadataCsv(array $righe): array
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
    protected function trovaInizioDispositivi(array $righe): int
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
     * Parse di valori decimali con gestione virgola italiana
     */
    protected function parseDecimal(?string $valore): ?float
    {
        if (empty($valore)) {
            return null;
        }

        $valore = str_replace(',', '.', trim($valore));
        return is_numeric($valore) ? (float)$valore : null;
    }

    /**
     * Parse data lettura da data e ora separate
     */
    protected function parseDataLettura(string $data, string $ora): Carbon
    {
        try {
            return Carbon::createFromFormat('d/m/y H:i', $data . ' ' . $ora);
        } catch (\Exception $e) {
            return now();
        }
    }

    /**
     * Parse data semplice (solo data)
     */
    protected function parseDataSemplice(?string $data): ?Carbon
    {
        if (empty($data)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('d/m/Y', trim($data));
        } catch (\Exception $e) {
            return null;
        }
    }
}
