<?php

namespace App\Http\Controllers\Backend;

use App\Enums\RuoliOperatoreEnum;
use App\Http\Controllers\Controller;
use App\Models\Amministratore;
use App\Models\AziendaServizio;
use App\Models\Impianto;
use App\Models\UnitaImmobiliare;
use App\Models\User;
use App\Traits\FunzioniUtente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AziendaServizioController extends Controller
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
            'ragione_sociale' => ['testo' => 'Ragione Sociale', 'filtro' => function ($q) {
                return $q->orderBy('ragione_sociale');
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
                'html' => base64_encode(view('Admin.AziendaServizio.tabella', [
                    'records' => $records,
                    'controller' => $nomeClasse,
                ]))
            ];
        }

        return view('Admin.AziendaServizio.index', [
            'records' => $records,
            'controller' => $nomeClasse,
            'titoloPagina' => 'Elenco ' . \App\Models\AziendaServizio::NOME_PLURALE,
            'orderBy' => $orderBy,
            'ordinamenti' => $ordinamenti,
            'filtro' => $filtro ?? 'tutti',
            'conFiltro' => $this->conFiltro,
            'testoNuovo' => 'Nuova ' . \App\Models\AziendaServizio::NOME_SINGOLARE,
            'testoCerca' => null,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applicaFiltri($request)
    {
        $queryBuilder = \App\Models\AziendaServizio::query()
            ->withCount('amministratori')
            ->withCount('impianti')
            ->with('comune');
        $term = $request->input('cerca');
        if ($term) {
            $searchString = collect(explode(' ', $term))
                ->map(fn($term) => preg_replace('/[+\-*<>@]/', '', $term))
                ->filter()
                ->map(fn($term) => '+' . $term . '*')
                ->implode(' ');
            if ($searchString) {
                $queryBuilder->whereRaw('MATCH(cognome, nome, codice_fiscale) AGAINST(? IN BOOLEAN MODE)', [$searchString]);
            }
        }

        return $queryBuilder;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $record = new AziendaServizio();
        return view('Admin.AziendaServizio.edit', [
            'record' => $record,
            'titoloPagina' => 'Nuova ' . AziendaServizio::NOME_SINGOLARE,
            'controller' => get_class($this),
            'breadcrumbs' => [action([AziendaServizioController::class, 'index']) => 'Torna a elenco ' . AziendaServizio::NOME_PLURALE],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(null));
        $record = new AziendaServizio();

        $user = $this->salvaDatiUtente(new User(), $request, 'referente', RuoliOperatoreEnum::azienda_di_servizio->value);
        $this->salvaDati($record, $request, $user->id);


        return $this->backToIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->tab(\request(), $id, 'tab_impianti');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = AziendaServizio::find($id);
        abort_if(!$record, 404, 'Questa aziendaservizio non esiste');
        if (false) {
            $eliminabile = 'Non eliminabile perchè presente in ...';
        } else {
            $eliminabile = true;
        }
        return view('Admin.AziendaServizio.edit', [
            'record' => $record,
            'controller' => AziendaServizioController::class,
            'titoloPagina' => 'Modifica ' . AziendaServizio::NOME_SINGOLARE,
            'eliminabile' => $eliminabile,
            'breadcrumbs' => [action([AziendaServizioController::class, 'index']) => 'Torna a elenco ' . AziendaServizio::NOME_PLURALE],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = AziendaServizio::find($id);
        abort_if(!$record, 404, 'Questa ' . AziendaServizio::NOME_SINGOLARE . ' non esiste');
        $request->validate($this->rules($id));
        $this->salvaDati($record, $request, null);

        return $this->backToIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = AziendaServizio::find($id);
        abort_if(!$record, 404, 'Questa ' . AziendaServizio::NOME_SINGOLARE . ' non esiste');
        $record->delete();

        return [
            'success' => true,
            'redirect' => action([AziendaServizioController::class, 'index']),
        ];
    }

    public function tab(Request $request, string $id, string $tab)
    {
        $record = AziendaServizio::query()
            ->withCount('amministratori')
            ->withCount('impianti')
            ->find($id);
        abort_if(!$record, 404, 'Questa aziendaservizio non esiste');


        $records = null;
        $tabs = ['tab_impianti', 'tab_amministratori'];
        $testoCerca = null;
        $statistiche = null;

        switch ($tab) {
            case 'tab_impianti':
                $records = $this->tabImpiantiRecords($id, $tab, $request);
                $testoCerca = 'cerca in nome impianto';
                $statistiche = $this->getStatisticheImpianti($id);
                break;

            case 'tab_amministratori':
                $records = $this->tabAmministratoriRecords($id, $tab, $request);
                $testoCerca = 'Cerca in nominativo';
                $statistiche = $this->getStatisticheAmministratori($id);
                break;

            default:
                abort(404, 'Questo tab non esiste');
        }

        // Se è una richiesta AJAX (per la paginazione con filtri), restituisci solo il contenuto del tab
        if ($request->ajax()) {

            return [
                'html' => base64_encode(view('Admin.AziendaServizio.show.tab' . \Str::of($tab)->remove('tab_')->title()->remove('_') . 'tabella', [
                    'record' => $record,
                    'records' => $records,
                    'controller' => ImpiantoController::class,
                ]))
            ];

        }

        return view('Admin.AziendaServizio.show', [
            'record' => $record,
            'records' => $records,
            'controller' => AziendaServizioController::class,
            'titoloPagina' => ucfirst(AziendaServizio::NOME_SINGOLARE),
            'breadcrumbs' => [action([AziendaServizioController::class, 'index']) => 'Torna a elenco ' . AziendaServizio::NOME_PLURALE],
            'tabs' => $tabs,
            'tab' => $tab,
            'testoCerca' => $testoCerca,
            'statistiche' => $statistiche,
        ]);

    }

    /**
     * @param AziendaServizio $record
     * @param Request $request
     * @return mixed
     */
    protected function salvaDati(AziendaServizio $record, Request $request, $userId)
    {
        $nuovo = !$record->id;

        if ($userId) {
            $record->user_id = $userId;
        }

        //Ciclo su campi
        $campi = [
            'ragione_sociale' => 'app\getInputUcwords',
            'codice_fiscale' => 'app\getInputToUpper',
            'partita_iva' => 'strtoupper',
            'telefono' => 'app\getInputTelefono',
            'email_aziendale' => 'app\getInputToLower',
            'indirizzo' => 'app\getInputUcfirst',
            'cap' => '',
            'citta' => '',
            'cognome_referente' => 'app\getInputUcwords',
            'nome_referente' => 'app\getInputUcwords',
            'telefono_referente' => 'app\getInputTelefono',
            'email_referente' => 'app\getInputToLower',
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
        if ($nuovo) {
            $record->attivo = 1;
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
            'ragione_sociale' => ['required', 'max:255'],
            'codice_fiscale' => ['nullable', 'max:16', new \App\Rules\CodiceFiscaleRule()],
            'partita_iva' => ['nullable', 'max:11', new \App\Rules\PartitaIvaRule()],
            'telefono' => ['nullable', 'max:255', new \App\Rules\TelefonoRule()],
            'email_aziendale' => ['nullable', 'max:255', 'email:strict,dns'],
            'indirizzo' => ['nullable', 'max:255'],
            'cap' => ['nullable', 'max:5'],
            'citta' => ['nullable', 'max:255'],
            'cognome_referente' => ['nullable', 'max:255'],
            'nome_referente' => ['nullable', 'max:255'],
            'telefono_referente' => ['nullable', 'max:255', new \App\Rules\TelefonoRule()],
            'email_referente' => ['nullable', 'max:255', 'email:strict,dns'],
            'attivo' => ['nullable'],
            'note' => ['nullable'],
        ];

        return $rules;
    }

    protected function tabImpiantiRecords(int $id, string $tab, Request $request)
    {
        $queryBuilder = Impianto::query()
            ->where('azienda_servizio_id', $id)
            ->with('amministratore')
            ->withCount('unitaImmobiliari')
            ->orderBy('nome_impianto');

        // Filtro per ricerca AJAX (mantieni così)
        $term = $request->input('cerca');
        if ($term) {
            $this->conFiltro = true;

            $searchColumns = ['nome_impianto'];

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
        return $queryBuilder->paginate()->withQueryString()->withPath(
            action([AziendaServizioController::class, 'tab'], ['id' => $id, 'tab' => $tab])
        );
    }

    protected function tabAmministratoriRecords(int $id, string $tab, Request $request)
    {
        $queryBuilder = Amministratore::query()
            ->where('azienda_servizio_id', $id)
            ->withCount('impianti')
            ->orderBy('ragione_sociale');

        // Filtro per ricerca AJAX (mantieni così)
        $term = $request->input('cerca');
        if ($term) {
            $this->conFiltro = true;

            $searchColumns = ['ragione_sociale', 'cognome_referente', 'nome_referente',];

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
        return $queryBuilder->paginate()->withQueryString()->withPath(
            action([AziendaServizioController::class, 'tab'], ['id' => $id, 'tab' => $tab])
        );
    }


    private function getStatisticheImpianti(string $aziendaId)
    {
        $query = Impianto::where('azienda_servizio_id', $aziendaId);

        return [
            'totale_impianti' => $query->count(),
            'totale_attivi' => $query->where('stato', 'attivo')->count(),
            'totale_dismessi' => $query->where('stato', 'dismesso')->count(),
            'totale_unita_immobiliari' => UnitaImmobiliare::whereHas('impianto', function ($query) use ($aziendaId) {
                $query->where('azienda_servizio_id', $aziendaId);
            })->count(),
        ];
    }

    private function getStatisticheAmministratori(string $aziendaId)
    {
        $query = Amministratore::where('azienda_servizio_id', $aziendaId);

        return [
            'totale_amministratori' => $query->count(),
            'totale_attivi' => $query->where('attivo', true)->count(),
            'totale_non_attivi' => $query->where('attivo', false)->count(),
        ];
    }


}
