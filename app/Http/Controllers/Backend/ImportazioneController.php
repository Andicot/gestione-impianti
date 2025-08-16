<?php

namespace App\Http\Controllers\Backend;

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
     * Gestisce l'upload dei file - VERSIONE UNIFICATA
     */
    public function caricaFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240|mimes:csv,txt,xlsx,xls', // Max 10MB, formati specifici
            'impianto_id' => 'required|exists:impianti,id',
            'concentratore_id' => 'nullable|exists:concentratori,id',
            'periodo_id' => 'nullable|exists:periodi_contabilizzazione,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('file');

        // Rileva automaticamente il tipo di file
        $tipoFile = $this->rilevaTipoFile($file);

        try {
            if ($tipoFile === 'csv') {
                return $this->gestisciCsvLetture($file, $request);
            } elseif ($tipoFile === 'excel') {
                return $this->gestisciExcelLetture($file, $request);
            } else {
                throw new \Exception("Formato file non supportato: {$file->getClientOriginalExtension()}");
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
     * Rileva il tipo di file automaticamente
     */
    private function rilevaTipoFile($file): string
    {
        $estensione = strtolower($file->getClientOriginalExtension());

        if (in_array($estensione, ['csv', 'txt'])) {
            return 'csv';
        } elseif (in_array($estensione, ['xlsx', 'xls'])) {
            return 'excel';
        }

        throw new \Exception("Estensione file non supportata: {$estensione}");
    }

    /**
     * Gestisce elaborazione Excel letture
     */
    private function gestisciExcelLetture($file, Request $request)
    {
        $parametri = [
            'impianto_id' => $request->input('impianto_id'),
            'concentratore_id' => $request->input('concentratore_id'),
            'periodo_id' => $request->input('periodo_id')
        ];

        // Elaborazione tramite service
        $risultato = $this->importazioneService->elaboraExcelLetture($file, $parametri);

        $alert = new AlertMessage();

        if ($risultato['success']) {
            $importazione = $risultato['importazione'];

            $messaggi = [
                "File Excel: {$importazione->nome_file}",
                "Righe elaborate: " . number_format($importazione->righe_elaborate)
            ];

            // Gestione degli stati
            if ($importazione->righe_errore > 0) {
                $messaggi[] = "Errori bloccanti: " . number_format($importazione->righe_errore);
                $tipoAlert = 'danger';
                $titoloAlert = 'Importazione Excel con errori';
                $iconaAlert = 'fas fa-exclamation-triangle';
            } elseif (($importazione->righe_warning ?? 0) > 0) {
                $messaggi[] = "Avvisi gestiti: " . number_format($importazione->righe_warning);
                $tipoAlert = 'warning';
                $titoloAlert = 'Importazione Excel con avvisi';
                $iconaAlert = 'fas fa-exclamation-circle';
            } else {
                $tipoAlert = 'success';
                $titoloAlert = 'Importazione Excel completata';
                $iconaAlert = 'fas fa-check-circle';
            }

            // Info aggiuntive
            if ($importazione->dispositivi_nuovi > 0) {
                $messaggi[] = "Dispositivi creati: " . number_format($importazione->dispositivi_nuovi);
            }

            if (($importazione->letture_create ?? 0) > 0) {
                $messaggi[] = "Letture create: " . number_format($importazione->letture_create);
            }

            $alert->messaggio($messaggi, $tipoAlert)
                ->titolo($titoloAlert, $tipoAlert, $iconaAlert);

            // Link al dettaglio
            $alert->messaggio("<a href='" . route('importazione.dettaglio', $importazione->id) . "' class='text-decoration-underline'>Visualizza dettaglio completo</a>", $tipoAlert);

        } else {
            $alert->messaggio('Importazione Excel fallita', 'danger')
                ->messaggio($risultato['errore'], 'danger')
                ->titolo('Errore nell\'importazione Excel', 'danger', 'fas fa-file-excel');
        }

        $alert->flash();
        return redirect()->route('importazione.index');
    }

    /**
     * Gestisce elaborazione CSV letture
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

            $messaggi = [
                "File CSV: {$importazione->nome_file}",
                "Righe elaborate: " . number_format($importazione->righe_elaborate)
            ];

            // Gestione degli stati
            if ($importazione->righe_errore > 0) {
                $messaggi[] = "Errori bloccanti: " . number_format($importazione->righe_errore);
                $tipoAlert = 'danger';
                $titoloAlert = 'Importazione CSV con errori';
                $iconaAlert = 'fas fa-exclamation-triangle';
            } elseif (($importazione->righe_warning ?? 0) > 0) {
                $messaggi[] = "Avvisi gestiti: " . number_format($importazione->righe_warning);
                $tipoAlert = 'warning';
                $titoloAlert = 'Importazione CSV con avvisi';
                $iconaAlert = 'fas fa-exclamation-circle';
            } else {
                $tipoAlert = 'success';
                $titoloAlert = 'Importazione CSV completata';
                $iconaAlert = 'fas fa-check-circle';
            }

            // Info aggiuntive
            if ($importazione->dispositivi_nuovi > 0) {
                $messaggi[] = "Dispositivi creati: " . number_format($importazione->dispositivi_nuovi);
            }

            if (($importazione->letture_create ?? 0) > 0) {
                $messaggi[] = "Letture create: " . number_format($importazione->letture_create);
            }

            $alert->messaggio($messaggi, $tipoAlert)
                ->titolo($titoloAlert, $tipoAlert, $iconaAlert);

            // Link al dettaglio
            $alert->messaggio("<a href='" . route('importazione.dettaglio', $importazione->id) . "' class='text-decoration-underline'>Visualizza dettaglio completo</a>", $tipoAlert);

        } else {
            $alert->messaggio('Importazione CSV fallita', 'danger')
                ->messaggio($risultato['errore'], 'danger')
                ->titolo('Errore nell\'importazione CSV', 'danger', 'fas fa-file-csv');
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

