<?php

namespace App\Services;

use App\Models\ImportazioneCsv;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
            // Delega l'elaborazione al service specifico
            $csvService = new LettureCsvService();
            $contenutoCsv = Storage::disk('public')->get($pathFile);
            $risultato = $csvService->elaboraContenuto($contenutoCsv, $importazione);

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
     * Elabora file Excel letture - NUOVO METODO
     */
    public function elaboraExcelLetture($file, array $parametri): array
    {
        $nomeFile = $file->getClientOriginalName();
        $pathFile = $file->store('importazioni/excel', 'public');

        // Crea record importazione
        $importazione = $this->creaRecordImportazione($nomeFile, $pathFile, $parametri);

        try {
            // Delega l'elaborazione al service specifico
            $excelService = new LettureExcelService();
            $filePath = Storage::disk('public')->path($pathFile);
            $risultato = $excelService->elaboraFile($filePath, $importazione);

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
     * Aggiorna statistiche importazione
     */
    private function aggiornaStatisticheImportazione(ImportazioneCsv $importazione, array $risultato): void
    {
        $importazione->righe_totali = $risultato['righe_totali'];
        $importazione->righe_elaborate = $risultato['righe_elaborate'];
        $importazione->righe_errore = $risultato['righe_errore'];
        $importazione->dispositivi_nuovi = $risultato['dispositivi_nuovi'];
        $importazione->letture_create = $risultato['letture_create'] ?? 0;

        // Determina stato
        if ($risultato['righe_errore'] > 0) {
            $importazione->stato = 'con_errori';
        } else {
            $importazione->stato = 'completato';
        }

        $importazione->log_errori = $risultato['log_errori'];
        $importazione->metadata_csv = $risultato['metadata_csv'];
        $importazione->data_elaborazione = now();
        $importazione->save();
    }

    /**
     * Gestisce errore durante importazione
     */
    private function gestisciErroreImportazione(ImportazioneCsv $importazione, \Exception $e): void
    {
        $importazione->stato = 'errore';
        $importazione->log_errori = [
            'errori' => [[
                'riga' => 'GENERALE',
                'messaggio' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ]]
        ];
        $importazione->save();
    }

    /**
     * Genera messaggio di successo per CSV
     */
    private function generaMessaggioSuccesso(array $risultato): string
    {
        $messaggi = ["Righe elaborate: {$risultato['righe_elaborate']}"];

        if ($risultato['righe_errore'] > 0) {
            $messaggi[] = "Errori: {$risultato['righe_errore']}";
        }

        if ($risultato['dispositivi_nuovi'] > 0) {
            $messaggi[] = "Dispositivi creati: {$risultato['dispositivi_nuovi']}";
        }

        if (($risultato['letture_create'] ?? 0) > 0) {
            $messaggi[] = "Letture create: {$risultato['letture_create']}";
        }

        return "CSV elaborato. " . implode(', ', $messaggi);
    }


}
