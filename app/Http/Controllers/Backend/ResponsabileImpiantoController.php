<?php

namespace App\Http\Controllers\Backend;

use App\Enums\RuoliOperatoreEnum;
use App\Http\Controllers\Controller;
use App\Models\ResponsabileImpianto;
use App\Models\User;
use App\Traits\FunzioniUtente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponsabileImpiantoController extends Controller
{
    use FunzioniUtente;

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
            }],
            'nominativo' => ['testo' => 'Nominativo', 'filtro' => function ($q) {
                return $q->orderBy('cognome')->orderBy('nome');
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
                'html' => base64_encode(view('Backend.ResponsabileImpianto.tabella', [
                    'records' => $records,
                    'controller' => $nomeClasse,
                ]))
            ];
        }

        return view('Backend.ResponsabileImpianto.index', [
            'records' => $records,
            'controller' => $nomeClasse,
            'titoloPagina' => 'Elenco ' . \App\Models\ResponsabileImpianto::NOME_PLURALE,
            'orderBy' => $orderBy,
            'ordinamenti' => $ordinamenti,
            'filtro' => $filtro ?? 'tutti',
            'conFiltro' => $this->conFiltro,
            'testoNuovo' => 'Nuovo ' . \App\Models\ResponsabileImpianto::NOME_SINGOLARE,
            'testoCerca' => 'Cerca in nominativo',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applicaFiltri($request)
    {
        $queryBuilder = \App\Models\ResponsabileImpianto::query();
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
        $record = new ResponsabileImpianto();
        return view('Backend.ResponsabileImpianto.edit', [
            'record' => $record,
            'titoloPagina' => 'Nuovo ' . ResponsabileImpianto::NOME_SINGOLARE,
            'controller' => get_class($this),
            'breadcrumbs' => [action([ResponsabileImpiantoController::class, 'index']) => 'Torna a elenco ' . ResponsabileImpianto::NOME_PLURALE],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(null));
        $user = $this->salvaDatiUtente(new User(), $request, '', RuoliOperatoreEnum::responsabile_impianto->value);
        $record = new ResponsabileImpianto();
        $this->salvaDati($record, $request, $user->id);
        return $this->backToIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = ResponsabileImpianto::find($id);
        abort_if(!$record, 404, 'Questo responsabileimpianto non esiste');
        return view('Backend.ResponsabileImpianto.show', [
            'record' => $record,
            'controller' => ResponsabileImpiantoController::class,
            'titoloPagina' => ucfirst(ResponsabileImpianto::NOME_SINGOLARE),
            'breadcrumbs' => [action([ResponsabileImpiantoController::class, 'index']) => 'Torna a elenco ' . ResponsabileImpianto::NOME_PLURALE],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = ResponsabileImpianto::find($id);
        abort_if(!$record, 404, 'Questo responsabileimpianto non esiste');
        if (false) {
            $eliminabile = 'Non eliminabile perchè presente in ...';
        } else {
            $eliminabile = true;
        }
        return view('Backend.ResponsabileImpianto.edit', [
            'record' => $record,
            'controller' => ResponsabileImpiantoController::class,
            'titoloPagina' => 'Modifica ' . ResponsabileImpianto::NOME_SINGOLARE,
            'eliminabile' => $eliminabile,
            'breadcrumbs' => [action([ResponsabileImpiantoController::class, 'index']) => 'Torna a elenco ' . ResponsabileImpianto::NOME_PLURALE],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = ResponsabileImpianto::find($id);
        abort_if(!$record, 404, 'Questo ' . ResponsabileImpianto::NOME_SINGOLARE . ' non esiste');
        $request->validate($this->rules($id));
        $this->salvaDati($record, $request);

        return $this->backToIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = ResponsabileImpianto::find($id);
        abort_if(!$record, 404, 'Questo ' . ResponsabileImpianto::NOME_SINGOLARE . ' non esiste');
        $record->delete();

        return [
            'success' => true,
            'redirect' => action([ResponsabileImpiantoController::class, 'index']),
        ];
    }

    /**
     * @param ResponsabileImpianto $record
     * @param Request $request
     * @return mixed
     */
    protected function salvaDati($record, $request, $userId = null)
    {
        $nuovo = !$record->id;

        if ($userId) {
            $record->user_id = $userId;
        }

        //Ciclo su campi
        $campi = [
            'azienda_servizio_id' => '',
            'cognome' => 'app\getInputUcwords',
            'nome' => 'app\getInputUcwords',
            'codice_fiscale' => 'app\getInputToUpper',
            'telefono' => 'app\getInputTelefono',
            'cellulare' => 'app\getInputTelefono',
            'email' => 'app\getInputToLower',
            'attivo' => 'app\getInputCheckbox',
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
            'azienda_servizio_id' => ['nullable'],
            'cognome' => ['required', 'max:255'],
            'nome' => ['required', 'max:255'],
            'codice_fiscale' => ['nullable', 'max:16', new \App\Rules\CodiceFiscaleRule()],
            'telefono' => ['nullable', 'max:255', new \App\Rules\TelefonoRule()],
            'cellulare' => ['nullable', 'max:255', new \App\Rules\TelefonoRule()],
            'email' => ['required', 'max:255', 'email:strict,dns'],
            'attivo' => ['nullable'],
            'note' => ['nullable'],
        ];

        return $rules;
    }

}
