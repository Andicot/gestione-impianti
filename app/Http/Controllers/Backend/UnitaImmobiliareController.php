<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UnitaImmobiliare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitaImmobiliareController extends Controller
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
                'html' => base64_encode(view('Backend.UnitaImmobiliare.tabella', [
                    'records' => $records,
                    'controller' => $nomeClasse,
                ]))
            ];
        }

        return view('Backend.UnitaImmobiliare.index', [
            'records' => $records,
            'controller' => $nomeClasse,
            'titoloPagina' => 'Elenco ' . \App\Models\UnitaImmobiliare::NOME_PLURALE,
            'orderBy' => $orderBy,
            'ordinamenti' => $ordinamenti,
            'filtro' => $filtro ?? 'tutti',
            'conFiltro' => $this->conFiltro,
            'testoNuovo' => 'Nuova ' . \App\Models\UnitaImmobiliare::NOME_SINGOLARE,
            'testoCerca' => null,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applicaFiltri($request)
    {
        $queryBuilder = \App\Models\UnitaImmobiliare::query();
        $term = $request->input('cerca');
        if ($term) {
            // Array delle colonne su cui effettuare la ricerca
            $searchColumns = ['cognome', 'nome']; // Aggiungi le colonne che ti servono

            $searchTerms = collect(explode(' ', trim($term)))
                ->map(fn($term) => trim($term))
                ->filter(fn($term) => !empty($term))
                ->values();

            if ($searchTerms->isNotEmpty()) {
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
    public function create()
    {
        $record = new UnitaImmobiliare();
        $record->impianto_id = 1;
        return view('Backend.UnitaImmobiliare.edit', [
            'record' => $record,
            'titoloPagina' => 'Nuova ' . UnitaImmobiliare::NOME_SINGOLARE,
            'controller' => get_class($this),
            'breadcrumbs' => [action([UnitaImmobiliareController::class, 'index']) => 'Torna a elenco ' . UnitaImmobiliare::NOME_PLURALE],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(null));
        $record = new UnitaImmobiliare();
        $record->azienda_servizio_id = Auth::user()->aziendaServizio?->id;
        $this->salvaDati($record, $request);
        return $this->backToIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = UnitaImmobiliare::find($id);
        abort_if(!$record, 404, 'Questa unitaimmobiliare non esiste');
        return view('Backend.UnitaImmobiliare.show', [
            'record' => $record,
            'controller' => UnitaImmobiliareController::class,
            'titoloPagina' => ucfirst(UnitaImmobiliare::NOME_SINGOLARE),
            'breadcrumbs' => [action([UnitaImmobiliareController::class, 'index']) => 'Torna a elenco ' . UnitaImmobiliare::NOME_PLURALE],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = UnitaImmobiliare::find($id);
        abort_if(!$record, 404, 'Questa unitaimmobiliare non esiste');
        if (false) {
            $eliminabile = 'Non eliminabile perchè presente in ...';
        } else {
            $eliminabile = true;
        }
        return view('Backend.UnitaImmobiliare.edit', [
            'record' => $record,
            'controller' => UnitaImmobiliareController::class,
            'titoloPagina' => 'Modifica ' . UnitaImmobiliare::NOME_SINGOLARE,
            'eliminabile' => $eliminabile,
            'breadcrumbs' => [action([UnitaImmobiliareController::class, 'index']) => 'Torna a elenco ' . UnitaImmobiliare::NOME_PLURALE],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = UnitaImmobiliare::find($id);
        abort_if(!$record, 404, 'Questa ' . UnitaImmobiliare::NOME_SINGOLARE . ' non esiste');
        $request->validate($this->rules($id));
        $this->salvaDati($record, $request);

        return $this->backToIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = UnitaImmobiliare::find($id);
        abort_if(!$record, 404, 'Questa ' . UnitaImmobiliare::NOME_SINGOLARE . ' non esiste');
        $record->delete();

        return [
            'success' => true,
            'redirect' => action([UnitaImmobiliareController::class, 'index']),
        ];
    }

    /**
     * @param UnitaImmobiliare $record
     * @param Request $request
     * @return mixed
     */
    protected function salvaDati($record, $request)
    {
        $nuovo = !$record->id;

        if ($nuovo) {

        }

        //Ciclo su campi
        $campi = [
            'azienda_servizio_id' => '',
            'impianto_id' => '',
            'scala' => '',
            'piano' => '',
            'interno' => '',
            'nominativo_unita' => 'app\getInputUcwords',
            'tipologia' => '',
            'millesimi_riscaldamento' => 'app\getInputNumero',
            'millesimi_acs' => 'app\getInputNumero',
            'metri_quadri' => 'app\getInputNumero',
            'corpo_scaldante' => '',
            'contatore_acs_numero' => '',
            'note' => '',
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

    protected function backToIndex()
    {
        return redirect()->action([get_class($this), 'index']);
    }


    protected function rules($id = null)
    {
        $rules = [
            'scala' => ['nullable', 'max:255'],
            'piano' => ['required', 'max:255'],
            'interno' => ['required', 'max:255'],
            'nominativo_unita' => ['nullable', 'max:255'],
            'tipologia' => ['required', 'max:20'],
            'millesimi_riscaldamento' => ['required'],
            'millesimi_acs' => ['required'],
            'metri_quadri' => ['nullable'],
            'corpo_scaldante' => ['nullable', 'max:255'],
            'contatore_acs_numero' => ['nullable', 'max:255'],
            'note' => ['nullable'],
        ];

        return $rules;
    }

}
