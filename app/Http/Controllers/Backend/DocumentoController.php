<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Models\UnitaImmobiliare;
use App\Traits\FunzioniUtente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoController extends Controller
{
    use FunzioniUtente;

    protected $conFiltro = false;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nomeClasse = get_class($this);
        $recordsQB = $this->applicaFiltri($request);

        // Ordinamenti
        $ordinamenti = [
            'recente' => ['testo' => 'Più recente', 'filtro' => function ($q) {
                return $q->orderBy('created_at', 'desc');
            }],
            'nome' => ['testo' => 'Nome file', 'filtro' => function ($q) {
                return $q->orderBy('nome_file');
            }],
            'tipo' => ['testo' => 'Tipo documento', 'filtro' => function ($q) {
                return $q->orderBy('tipo_documento')->orderBy('nome_file');
            }],
            'data_caricamento' => ['testo' => 'Data caricamento', 'filtro' => function ($q) {
                return $q->orderBy('created_at', 'desc');
            }]
        ];

        $orderByUser = Auth::user()->getExtra($nomeClasse);
        $orderByString = $request->input('orderBy');
        if ($orderByString) {
            $orderBy = $orderByString;
        } else if ($orderByUser) {
            $orderBy = $orderByUser;
        } else {
            $orderBy = 'recente';
        }
        if ($orderByUser != $orderByString) {
            Auth::user()->setExtra([$nomeClasse => $orderBy]);
        }

        $recordsQB = call_user_func($ordinamenti[$orderBy]['filtro'], $recordsQB);
        $records = $recordsQB->paginate(25)->withQueryString();

        if ($request->ajax()) {
            return [
                'html' => base64_encode(view('Backend.Documento.tabella', [
                    'records' => $records,
                    'controller' => $nomeClasse,
                ]))
            ];
        }

        return view('Backend.Documento.index', [
            'records' => $records,
            'controller' => $nomeClasse,
            'titoloPagina' => 'Elenco ' . Documento::NOME_PLURALE,
            'orderBy' => $orderBy,
            'ordinamenti' => $ordinamenti,
            'filtro' => $this->filtro ?? 'tutti',
            'conFiltro' => $this->conFiltro,
            'testoNuovo' => 'Nuovo ' . Documento::NOME_SINGOLARE,
            'testoCerca' => 'Cerca per nome file, descrizione...',
        ]);
    }

    protected function applicaFiltri($request)
    {
        $query = Documento::query()
            ->with(['caricatoDa', 'impianto', 'unitaImmobiliare'])
            ->attivi();

        // Filtro per amministratore corrente
        $amministratore = Auth::user()->amministratore;
        if ($amministratore) {
            $impiantiIds = $amministratore->impianti()->pluck('id');
            $query->whereIn('impianto_id', $impiantiIds);
        }

        // Filtro ricerca
        if ($request->filled('cerca')) {
            $cerca = $request->cerca;
            $query->where(function ($q) use ($cerca) {
                $q->where('nome_file', 'LIKE', "%$cerca%")
                    ->orWhere('descrizione', 'LIKE', "%$cerca%")
                    ->orWhere('nome_file_originale', 'LIKE', "%$cerca%");
            });
        }

        // Filtro tipo documento
        if ($request->filled('tipo_documento')) {
            $query->where('tipo_documento', $request->tipo_documento);
            $this->conFiltro = true;
        }

        // Filtro impianto
        if ($request->filled('impianto_id')) {
            $query->where('impianto_id', $request->impianto_id);
            $this->conFiltro = true;
        }

        // Filtro visibilità
        if ($request->filled('visibilita')) {
            switch ($request->visibilita) {
                case 'pubblico':
                    $query->where('pubblico', true);
                    break;
                case 'riservato':
                    $query->where('riservato_amministratori', true);
                    break;
                case 'privato':
                    $query->where('pubblico', false)->where('riservato_amministratori', false);
                    break;
            }
            $this->conFiltro = true;
        }

        // Filtro scadenza
        if ($request->filled('scadenza')) {
            switch ($request->scadenza) {
                case 'in_scadenza':
                    $query->where('data_scadenza', '<=', now()->addDays(30))
                        ->where('data_scadenza', '>', now());
                    break;
                case 'scaduti':
                    $query->where('data_scadenza', '<', now());
                    break;
                case 'senza_scadenza':
                    $query->whereNull('data_scadenza');
                    break;
            }
            $this->conFiltro = true;
        }

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $record = new Documento();
        $amministratore = Auth::user()->amministratore;
        $impianti = $amministratore->impianti()->orderBy('nome_impianto')->get();

        $unitaImmobiliari = collect();
        if ($request->filled('impianto_id')) {
            $unitaImmobiliari = UnitaImmobiliare::where('impianto_id', $request->impianto_id)
                ->orderBy('scala')
                ->orderBy('piano')
                ->orderBy('interno')
                ->get();
        }

        return view('Backend.Documento.edit', [
            'controller' => DocumentoController::class,
            'titoloPagina' => 'Nuovo ' . Documento::NOME_SINGOLARE,
            'breadcrumbs' => [action([DocumentoController::class, 'index']) => 'Torna a elenco ' . Documento::NOME_PLURALE],
            'impianti' => $impianti,
            'unitaImmobiliari' => $unitaImmobiliari,
            'record' => $record,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(null));

        $record = new Documento();
        $this->salvaDati($record, $request);

        return $this->backToIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Documento::with(['caricatoDa', 'impianto', 'unitaImmobiliare', 'visualizzazioni.utente'])
            ->findOrFail($id);

        // Verifica permessi
        if (!$record->puo_visualizzare_utente(Auth::id())) {
            abort(403, 'Non hai i permessi per visualizzare questo documento');
        }

        $record->incrementa_visualizzazioni(Auth::id());

        return view('Backend.Documento.show', [
            'record' => $record,
            'controller' => DocumentoController::class,
            'titoloPagina' => ucfirst(Documento::NOME_SINGOLARE),
            'breadcrumbs' => [action([DocumentoController::class, 'index']) => 'Torna a elenco ' . Documento::NOME_PLURALE],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = Documento::findOrFail($id);

        // Solo chi ha caricato il documento può modificarlo
        if ($record->caricato_da_id !== Auth::id()) {
            abort(403, 'Non puoi modificare questo documento');
        }

        $amministratore = Auth::user()->amministratore;
        $impianti = $amministratore->impianti()->orderBy('nome_impianto')->get();

        $unitaImmobiliari = collect();
        if ($record->impianto_id) {
            $unitaImmobiliari = UnitaImmobiliare::where('impianto_id', $record->impianto_id)
                ->orderBy('scala')
                ->orderBy('piano')
                ->orderBy('interno')
                ->get();
        }

        return view('Backend.Documento.edit', [
            'record' => $record,
            'controller' => DocumentoController::class,
            'titoloPagina' => 'Modifica ' . Documento::NOME_SINGOLARE,
            'eliminabile' => true,
            'breadcrumbs' => [action([DocumentoController::class, 'index']) => 'Torna a elenco ' . Documento::NOME_PLURALE],
            'impianti' => $impianti,
            'unitaImmobiliari' => $unitaImmobiliari,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = Documento::findOrFail($id);

        if ($record->caricato_da_id !== Auth::id()) {
            abort(403, 'Non puoi modificare questo documento');
        }

        $request->validate($this->rules($id));
        $this->salvaDati($record, $request);

        return $this->backToIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Documento::findOrFail($id);

        if ($record->caricato_da_id !== Auth::id()) {
            abort(403, 'Non puoi eliminare questo documento');
        }

        // Elimina file fisico
        if ($record->path_file && Storage::exists($record->path_file)) {
            Storage::delete($record->path_file);
        }

        $record->delete();

        return [
            'success' => true,
            'redirect' => action([DocumentoController::class, 'index']),
        ];
    }

    /**
     * Download del documento
     */
    public function download(string $id)
    {
        $record = Documento::findOrFail($id);

        if (!$record->puo_visualizzare_utente(Auth::id())) {
            abort(403, 'Non hai i permessi per scaricare questo documento');
        }

        if (!Storage::exists($record->path_file)) {
            abort(404, 'File non trovato');
        }

        $record->segna_come_scaricato(Auth::id());

        return Storage::download($record->path_file, $record->nome_file_originale);
    }


    protected function salvaDati($record, $request)
    {
        $nuovo = !$record->id;

        // Gestione upload file
        if ($request->hasFile('file_documento')) {
            $file = $request->file('file_documento');

            // Elimina vecchio file se presente
            if (!$nuovo && $record->path_file && Storage::exists($record->path_file)) {
                Storage::delete($record->path_file);
            }

            $nomeFile = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $pathFile = $file->storeAs('documenti', $nomeFile);

            $record->nome_file = $nomeFile;
            $record->nome_file_originale = $file->getClientOriginalName();
            $record->path_file = $pathFile;
            $record->mime_type = $file->getMimeType();
            $record->dimensione_file = $file->getSize();
        }

        // Campi base
        $campi = [
            'tipo_documento' => '',
            'descrizione' => '',
            'impianto_id' => '',
            'unita_immobiliare_id' => '',
            'pubblico' => 'app\getInputCheckbox',
            'riservato_amministratori' => 'app\getInputCheckbox',
            'data_scadenza' => 'app\getInputData',
            'notifica_scadenza' => 'app\getInputCheckbox',
            'note' => '',
        ];

        foreach ($campi as $campo => $funzione) {
            $valore = $request->$campo;
            if ($funzione != '') {
                $valore = $funzione($valore);
            }
            $record->$campo = $valore;
        }

        if ($nuovo) {
            $record->caricato_da_id = Auth::id();
            $record->stato = 'attivo';
        }

        $record->save();
        return $record;
    }

    protected function backToIndex()
    {
        return redirect()->action([DocumentoController::class, 'index']);
    }

    protected function rules($id = null)
    {
        $rules = [
            'tipo_documento' => ['required', 'string', 'max:30'],
            'descrizione' => ['nullable', 'string'],
            'impianto_id' => ['required', 'exists:impianti,id'],
            'unita_immobiliare_id' => ['nullable', 'exists:unita_immobiliari,id'],
            'pubblico' => ['nullable'],
            'riservato_amministratori' => ['nullable'],
            'data_scadenza' => ['nullable', 'date', 'after:today'],
            'notifica_scadenza' => ['nullable'],
            'note' => ['nullable', 'string'],
        ];

        if (!$id) {
            $rules['file_documento'] = ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png'];
        } else {
            $rules['file_documento'] = ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png'];
        }

        return $rules;
    }
}
