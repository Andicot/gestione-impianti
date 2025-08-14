<?php

namespace App\Http\Controllers\Aziendadiservizio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DispositivoMisura;
use DB;

class DispositivoMisuraController extends Controller
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
                'html' => base64_encode(view('Aziendadiservizio.DispositivoMisura.tabella', [
                    'records' => $records,
                    'controller' => $nomeClasse,
                ]))
            ];
        }

        return view('Aziendadiservizio.DispositivoMisura.index', [
            'records' => $records,
            'controller' => $nomeClasse,
            'titoloPagina' => 'Elenco ' . \App\Models\DispositivoMisura::NOME_PLURALE,
            'orderBy' => $orderBy,
            'ordinamenti' => $ordinamenti,
            'filtro' => $filtro ?? 'tutti',
            'conFiltro' => $this->conFiltro,
            'testoNuovo' => 'Nuovo ' . \App\Models\DispositivoMisura::NOME_SINGOLARE,
            'testoCerca' => 'Cerca in matricola',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applicaFiltri($request)
    {
        $queryBuilder = \App\Models\DispositivoMisura::query();
        $term = $request->input('cerca');
        if ($term) {
            // Array delle colonne su cui effettuare la ricerca
            $searchColumns = ['matricola']; // Aggiungi le colonne che ti servono

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
        $record = new DispositivoMisura();
        return view('Aziendadiservizio.DispositivoMisura.edit', [
            'record' => $record,
            'titoloPagina' => 'Nuovo ' . DispositivoMisura::NOME_SINGOLARE,
            'controller' => get_class($this),
            'breadcrumbs' => [action([DispositivoMisuraController::class, 'index']) => 'Torna a elenco ' . DispositivoMisura::NOME_PLURALE],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(null));
        $record = new DispositivoMisura();
        $this->salvaDati($record, $request);
        return $this->backToIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = DispositivoMisura::find($id);
        abort_if(!$record, 404, 'Questo dispositivomisura non esiste');
        return view('Aziendadiservizio.DispositivoMisura.show', [
            'record' => $record,
            'controller' => DispositivoMisuraController::class,
            'titoloPagina' => ucfirst(DispositivoMisura::NOME_SINGOLARE),
            'breadcrumbs' => [action([DispositivoMisuraController::class, 'index']) => 'Torna a elenco ' . DispositivoMisura::NOME_PLURALE],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = DispositivoMisura::find($id);
        abort_if(!$record, 404, 'Questo dispositivomisura non esiste');
        if (false) {
            $eliminabile = 'Non eliminabile perchè presente in ...';
        } else {
            $eliminabile = true;
        }
        return view('Aziendadiservizio.DispositivoMisura.edit', [
            'record' => $record,
            'controller' => DispositivoMisuraController::class,
            'titoloPagina' => 'Modifica ' . DispositivoMisura::NOME_SINGOLARE,
            'eliminabile' => $eliminabile,
            'breadcrumbs' => [action([DispositivoMisuraController::class, 'index']) => 'Torna a elenco ' . DispositivoMisura::NOME_PLURALE],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = DispositivoMisura::find($id);
        abort_if(!$record, 404, 'Questo ' . DispositivoMisura::NOME_SINGOLARE . ' non esiste');
        $request->validate($this->rules($id));
        $this->salvaDati($record, $request);

        return $this->backToIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = DispositivoMisura::find($id);
        abort_if(!$record, 404, 'Questo ' . DispositivoMisura::NOME_SINGOLARE . ' non esiste');
        $record->delete();

        return [
            'success' => true,
            'redirect' => action([DispositivoMisuraController::class, 'index']),
        ];
    }

    /**
     * @param DispositivoMisura $record
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
            'matricola' => '',
            'nome_dispositivo' => 'app\getInputUcwords',
            'descrizione_1' => '',
            'descrizione_2' => '',
            'marca' => '',
            'modello' => '',
            'tipo' => '',
            'offset' => 'app\getInputNumero',
            'data_installazione' => 'app\getInputData',
            'stato_dispositivo' => '',
            'ubicazione' => '',
            'unita_immobiliare_id' => '',
            'impianto_id' => '',
            'concentratore_id' => '',
            'ultimo_valore_rilevato' => 'app\getInputNumero',
            'data_ultima_lettura' => 'app\getInputData',
            'creato_automaticamente' => 'app\getInputCheckbox',
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
            'matricola' => ['required', 'max:255'],
            'nome_dispositivo' => ['nullable', 'max:255'],
            'descrizione_1' => ['nullable', 'max:255'],
            'descrizione_2' => ['nullable', 'max:255'],
            'marca' => ['nullable', 'max:255'],
            'modello' => ['nullable', 'max:255'],
            'tipo' => ['required', 'max:30'],
            'offset' => ['required'],
            'data_installazione' => ['nullable', new \App\Rules\DataItalianaRule()],
            'stato' => ['required', 'max:20'],
            'ubicazione' => ['nullable', 'max:255'],
            'unita_immobiliare_id' => ['nullable'],
            'impianto_id' => ['nullable'],
            'concentratore_id' => ['nullable'],
            'ultimo_valore_rilevato' => ['nullable'],
            'data_ultima_lettura' => ['nullable', new \App\Rules\DataItalianaRule()],
            'creato_automaticamente' => ['nullable'],
            'note' => ['nullable'],
        ];

        return $rules;
    }

}
