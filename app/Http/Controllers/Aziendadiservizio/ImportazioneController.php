<?php

namespace App\Http\Controllers\Aziendadiservizio;

use App\Http\Controllers\Controller;
use App\Http\MieClassi\AlertMessage;
use App\Models\ImportazioneCsv;
use App\Services\ImportazioneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImportazioneController extends Controller
{
    protected ImportazioneService $importazioneService;

    public function __construct(ImportazioneService $importazioneService)
    {
        $this->importazioneService = $importazioneService;
    }

    /**
     * Mostra la pagina di importazione
     */
    public function index()
    {
        return view('Aziendadiservizio.Importazione.index', [
            'titoloPagina' => 'Importazione File',
            'controller' => self::class
        ]);
    }

    /**
     * Gestisce l'upload dei file
     */
    public function caricaFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // Max 10MB
            'tipo_file' => 'required|in:csv_letture,excel_inventario',
            'impianto_id' => 'required_if:tipo_file,csv_letture|exists:impianti,id',
            'concentratore_id' => 'nullable|exists:concentratori,id',
            'periodo_id' => 'nullable|exists:periodi_contabilizzazione,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('file');
        $tipoFile = $request->input('tipo_file');

        try {
            if ($tipoFile === 'csv_letture') {
                return $this->gestisciCsvLetture($file, $request);
            } else {
                return $this->gestisciExcelInventario($file, $request);
            }
        } catch (\Exception $e) {
            $alert = new AlertMessage();
            $alert->messaggio('Errore durante l\'elaborazione del file', 'danger')
                ->messaggio($e->getMessage(), 'danger')
                ->titolo('Errore di sistema', 'danger', 'fas fa-bug')
                ->flash();

            return back()->withInput();
        }
    }

    /**
     * Gestisce elaborazione CSV letture - VERSIONE MIGLIORATA
     */
    private function gestisciCsvLetture($file, Request $request)
    {
        $parametri = [
            'impianto_id' => $request->input('impianto_id'),
            'concentratore_id' => $request->input('concentratore_id'),
            'periodo_id' => $request->input('periodo_id')
        ];

        // Elaborazione tramite service
        $risultato = $this->importazioneService->elaboraCsvLetture($file, $parametri);

        $alert = new AlertMessage();

        if ($risultato['success']) {
            $importazione = $risultato['importazione'];

            // Gestione messaggi basata sul nuovo sistema di classificazione
            $messaggi = [
                "File: {$importazione->nome_file}",
                "Righe elaborate: " . number_format($importazione->righe_elaborate)
            ];

            // Solo errori REALI
            if ($importazione->righe_errore > 0) {
                $messaggi[] = "⚠️ Errori bloccanti: " . number_format($importazione->righe_errore);

                if (($importazione->righe_warning ?? 0) > 0) {
                    $messaggi[] = "⚡ Avvisi gestiti: " . number_format($importazione->righe_warning);
                }

                $alert->messaggio($messaggi, 'danger')
                    ->titolo('Importazione completata con errori', 'danger', 'fas fa-exclamation-triangle');

            } elseif (($importazione->righe_warning ?? 0) > 0) {
                // Solo warning (situazioni gestite automaticamente)
                $messaggi[] = "⚡ Avvisi gestiti: " . number_format($importazione->righe_warning);

                if (($importazione->righe_info ?? 0) > 0) {
                    $messaggi[] = "ℹ️ Messaggi informativi: " . number_format($importazione->righe_info);
                }

                $alert->messaggio($messaggi, 'warning')
                    ->titolo('Importazione completata con avvisi', 'warning', 'fas fa-exclamation-circle');

            } else {
                // Successo completo
                if (($importazione->righe_info ?? 0) > 0) {
                    $messaggi[] = "ℹ️ Messaggi informativi: " . number_format($importazione->righe_info);
                }

                $alert->messaggio($messaggi, 'success')
                    ->titolo('Importazione completata con successo', 'success', 'fas fa-check-circle');
            }

            // Aggiungi info sui dispositivi nuovi se presenti
            if ($importazione->dispositivi_nuovi > 0) {
                $alert->messaggio("🆕 Dispositivi creati automaticamente: " . number_format($importazione->dispositivi_nuovi),
                    $importazione->righe_errore > 0 ? 'danger' : (($importazione->righe_warning ?? 0) > 0 ? 'warning' : 'success'));
            }

            // Link al dettaglio
            $alert->messaggio("👁️ <a href='" . route('importazione.dettaglio', $importazione->id) . "' class='text-decoration-underline'>Visualizza dettaglio completo</a>",
                $importazione->righe_errore > 0 ? 'danger' : (($importazione->righe_warning ?? 0) > 0 ? 'warning' : 'success'));

        } else {
            // Errore durante l'importazione
            $alert->messaggio('Importazione fallita', 'danger')
                ->messaggio($risultato['errore'], 'danger')
                ->titolo('Errore nell\'importazione CSV', 'danger', 'fas fa-file-csv');
        }

        $alert->flash();
        return redirect()->route('importazione.index');
    }






    /**
     * Gestisce elaborazione Excel inventario
     */
    private function gestisciExcelInventario($file, Request $request)
    {
        $parametri = [
            'note' => $request->input('note')
        ];

        // Elaborazione tramite service
        $risultato = $this->importazioneService->elaboraExcelInventario($file, $parametri);

        $alert = new AlertMessage();

        if ($risultato['success']) {
            $dati = $risultato['risultato'];

            if ($dati['righe_errore'] > 0) {
                // Excel elaborato con errori
                $messaggi = [
                    "File Excel elaborato",
                    "Righe elaborate: " . number_format($dati['righe_elaborate']),
                    "Errori rilevati: " . number_format($dati['righe_errore'])
                ];

                $alert->messaggio($messaggi, 'warning')
                    ->titolo('Excel elaborato con errori', 'warning', 'fas fa-exclamation-triangle');
        } else {
                // Excel elaborato con successo
                $messaggi = [
                    "File Excel elaborato con successo",
                    "Righe elaborate: " . number_format($dati['righe_elaborate']),
                    "Colonne rilevate: " . count($dati['headers'])
                ];

                $alert->messaggio($messaggi, 'success')
                    ->titolo('Excel elaborato con successo', 'success', 'fas fa-file-excel');
            }

        } else {
            // Errore durante l'elaborazione Excel
            $alert->messaggio('Elaborazione Excel fallita', 'danger')
                ->messaggio($risultato['errore'], 'danger')
                ->titolo('Errore nell\'elaborazione Excel', 'danger', 'fas fa-file-excel');
        }

        $alert->flash();
        return redirect()->route('importazione.index');
    }

    /**
     * Mostra storico importazioni
     */
    public function storico()
    {
        $importazioni = ImportazioneCsv::with(['impianto', 'concentratore', 'caricatoDa'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('Aziendadiservizio.Importazione.storico', [
            'importazioni' => $importazioni,
            'titoloPagina' => 'Storico Importazioni',
            'controller' => self::class
        ]);
    }

    /**
     * Mostra dettagli importazione
     */
    public function dettaglioImportazione($id)
    {
        $importazione = ImportazioneCsv::with(['impianto', 'concentratore', 'caricatoDa'])
            ->findOrFail($id);

        return view('Aziendadiservizio.Importazione.dettaglio', [
            'importazione' => $importazione,
            'titoloPagina' => 'Dettaglio Importazione #' . $importazione->id,
            'controller' => self::class
        ]);
    }
}

