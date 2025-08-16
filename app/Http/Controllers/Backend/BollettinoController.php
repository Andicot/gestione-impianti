<?php

namespace App\Http\Controllers\Backend;

use App\Enums\StatoPagamentoBollettinoEnum;
use App\Http\Controllers\Controller;
use App\Models\Impianto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bollettino;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BollettinoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $conFiltro = false;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nomeClasse = get_class($this);
        $recordsQB = $this->applicaFiltri($request);

        //Ordinamento
        $ordinamenti = [
            'recente' => ['testo' => 'Più recente', 'filtro' => function ($q) {
                return $q->orderBy('id', 'desc');
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
                'html' => base64_encode(view('Backend.Bollettino.tabella', [
                    'records' => $records,
                    'controller' => $nomeClasse,
                ]))
            ];
        }

        return view('Backend.Bollettino.index', [
            'records' => $records,
            'controller' => $nomeClasse,
            'titoloPagina' => 'Elenco ' . \App\Models\Bollettino::NOME_PLURALE,
            'orderBy' => $orderBy,
            'ordinamenti' => $ordinamenti,
            'filtro' => $filtro ?? 'tutti',
            'conFiltro' => $this->conFiltro,
            'testoNuovo' =>null,
            'testoCerca' => 'Cerca in: nome file originale',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applicaFiltri($request)
    {
        $queryBuilder = \App\Models\Bollettino::query();
        $term = $request->input('cerca');
        if ($term) {
            // Ricerca automatica su 1 campo: nome_file_originale
            $searchColumns = ['nome_file_originale'];

            $searchTerms = collect(explode(' ', trim($term)))
                ->map(fn($term) => trim($term))
                ->filter(fn($term) => !empty($term))
                ->values();

            if ($searchTerms->isNotEmpty() && !empty($searchColumns)) {
                $queryBuilder->where(function ($query) use ($searchTerms, $searchColumns) {
                    foreach ($searchTerms as $searchTerm) {
                        $query->where(function ($subQuery) use ($searchTerm, $searchColumns) {
                            foreach ($searchColumns as $index => $column) {
                                if ($index === 0) {
                                    $subQuery->where($column, 'LIKE', '%' . $searchTerm . '%');
                                } else {
                                    $subQuery->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
                                }
                            }
                        });
                    }
                });
            }
        }

        return $queryBuilder;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create($impiantoId)
    {
        $record = new Bollettino();
        $record->impianto_id = $impiantoId;
        $record->stato_pagamento=StatoPagamentoBollettinoEnum::non_pagato->value;
        return view('Backend.Bollettino.edit', [
            'record' => $record,
            'titoloPagina' => 'Nuovo ' . Bollettino::NOME_SINGOLARE,
            'controller' => get_class($this),
            'breadcrumbs' => [action([BollettinoController::class, 'index']) => 'Torna a elenco ' . Bollettino::NOME_PLURALE],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(null));
        $record = new Bollettino();
        $impianto=Impianto::find($request->input('impianto_id'));
        abort_if(!$impianto, 404,'Impianto non trovato');
        $record->impianto_id = $impianto->id;
        $record->azienda_servizio_id=$impianto->azienda_servizio_id;
        $record->caricato_da_id=Auth::id();
        $this->salvaDati($record, $request);
        return $this->backToImpianto($record->impianto_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Bollettino::find($id);
        abort_if(!$record, 404, 'Questo bollettino non esiste');
        return view('Backend.Bollettino.show', [
            'record' => $record,
            'controller' => BollettinoController::class,
            'titoloPagina' => ucfirst(Bollettino::NOME_SINGOLARE),
            'breadcrumbs' => [action([BollettinoController::class, 'index']) => 'Torna a elenco ' . Bollettino::NOME_PLURALE],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = Bollettino::find($id);
        abort_if(!$record, 404, 'Questo bollettino non esiste');
        if (false) {
            $eliminabile = 'Non eliminabile perchè presente in ...';
        } else {
            $eliminabile = true;
        }
        return view('Backend.Bollettino.edit', [
            'record' => $record,
            'controller' => BollettinoController::class,
            'titoloPagina' => 'Modifica ' . Bollettino::NOME_SINGOLARE,
            'eliminabile' => $eliminabile,
            'breadcrumbs' => [action([BollettinoController::class, 'index']) => 'Torna a elenco ' . Bollettino::NOME_PLURALE],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = Bollettino::find($id);
        abort_if(!$record, 404, 'Questo ' . Bollettino::NOME_SINGOLARE . ' non esiste');
        $request->validate($this->rules($id));
        $this->salvaDati($record, $request);

        return $this->backToImpianto($record->impianto_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Bollettino::find($id);
        abort_if(!$record, 404, 'Questo ' . Bollettino::NOME_SINGOLARE . ' non esiste');
        $record->delete();

        return [
            'success' => true,
            'redirect' => action([BollettinoController::class, 'index']),
        ];
    }

    /**
     * Download del PDF bollettino
     */
    public function download(string $id)
    {
        $record = Bollettino::find($id);
        abort_if(!$record, 404, 'Questo bollettino non esiste');

        // Verifica che l'utente possa scaricare il bollettino
        // (es. condomino può scaricare solo i suoi bollettini)
        if (!$this->puoScaricareUtente($record)) {
            abort(403, 'Non hai i permessi per scaricare questo bollettino');
        }

        // Verifica che il file PDF esista
        if (!$record->path_file || !Storage::exists($record->path_file)) {
            abort(404, 'File PDF non trovato');
        }

        // Traccia il download (opzionale)
        $this->tracciaDownload($record);

        // Restituisce il file con nome originale
        $nomeDownload = $record->nome_file_originale ?: 'bollettino_' . $record->id . '.pdf';

        return Storage::download($record->path_file, $nomeDownload);
    }

    /**
     * Verifica se l'utente può scaricare il bollettino
     */
    private function puoScaricareUtente(Bollettino $bollettino): bool
    {
        $user = Auth::user();

        // Admin e azienda servizio possono scaricare tutto
        if (in_array($user->ruolo, ['admin', 'azienda_di_servizio'])) {
            return true;
        }

        // Amministratore condominio può scaricare solo del suo impianto
        if ($user->ruolo === 'amministratore_condominio') {
            return $user->amministratore &&
                $user->amministratore->impianti()->where('id', $bollettino->impianto_id)->exists();
        }

        // Condomino può scaricare solo i suoi bollettini
        if ($user->ruolo === 'condomino') {
            return $bollettino->unitaImmobiliare->user_id === $user->id;
        }

        return false;
    }

    /**
     * Traccia il download del bollettino
     */
    private function tracciaDownload(Bollettino $bollettino): void
    {
        // Se è un condomino che scarica per la prima volta, segna come visualizzato
        if (Auth::user()->ruolo === 'condomino' && !$bollettino->visualizzato) {
            $bollettino->visualizzato = true;
            $bollettino->data_visualizzazione = now();
            $bollettino->save();
        }

        // Aggiorna statistiche download (se presenti nella migration originale)

            $bollettino->data_visualizzazione = now();
            $bollettino->save();

    }

    /**
     * @param Bollettino $record
     * @param Request $request
     * @return mixed
     */
    protected function salvaDati($record, $request)
    {
        $nuovo = !$record->id;

        if ($nuovo) {

        }


        // Gestione upload file
        if ($request->hasFile('file_documento')) {
            $file = $request->file('file_documento');

            // Elimina vecchio file se presente
            if (!$nuovo && $record->path_file && Storage::exists($record->path_file)) {
                Storage::delete($record->path_file);
            }

            $nomeFile = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $pathFile = $file->storeAs('bollettini', $nomeFile);

            $record->nome_file_originale = $file->getClientOriginalName();
            $record->path_file = $pathFile;
            $record->mime_type = $file->getMimeType();
            $record->dimensione_file = $file->getSize();
        }

        //Ciclo su campi
        $campi = [
            'periodo_id' => '',
            'unita_immobiliare_id' => '',
            'importo' => 'app\getInputNumero',
            'metodo_pagamento' => '',
            'note' => '',
            'stato_pagamento' => '',
            'importo_pagato' => 'app\getInputNumero',
            'data_scadenza' => 'app\getInputData',
        ];
        foreach ($campi as $campo => $funzione) {
            $valore = $request->$campo;
            if ($funzione != '') {
                $valore = $funzione($valore);
            }
            $record->$campo = $valore;
        }

        $record->save();
        return $record;
    }

    protected function backToImpianto($impiantoId)
    {
        return redirect()->action([ImpiantoController::class, 'tab'],[$impiantoId,'tab'=>'tab_bollettini']);
    }


    protected function rules($id = null)
    {
        $rules = [
            'unita_immobiliare_id' => ['required'],
            'periodo_id' => ['nullable'],
            'importo' => ['required'],
            'metodo_pagamento' => ['nullable', 'max:255'],
            'note' => ['nullable'],
            'file_documento' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240' // 10MB in KB
            ],
            'stato_pagamento' => ['required', 'max:20'],
            'importo_pagato' => ['nullable'],
            'data_scadenza' => ['nullable', new \App\Rules\DataItalianaRule()],
        ];

        return $rules;
    }

}
