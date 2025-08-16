<?php

namespace App\Http\Controllers\Backend;

use App\Enums\RuoliOperatoreEnum;
use App\Enums\StatoImpiantoEnum;
use App\Http\Controllers\Controller;
use App\Models\Bollettino;
use App\Models\DispositivoMisura;
use App\Models\Impianto;
use App\Models\UnitaImmobiliare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpiantoController extends Controller
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
            'nome_impianti' => ['testo' => 'Nome Impianti', 'filtro' => function ($q) {
                return $q->orderBy('nome_impianto');
            }],
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

        $mostraAmministratore = $this->mostraAmministratore();
        $mostraResponsabile = $this->mostraResponsabileImpianto();
        if ($request->ajax()) {
            return [
                'html' => base64_encode(view('Backend.Impianto.tabella', [
                    'records' => $records,
                    'controller' => $nomeClasse,
                    'mostraAmministratore' => $mostraAmministratore,
                    'mostraResponsabile' => $mostraResponsabile,
                ]))
            ];
        }

        return view('Backend.Impianto.index', [
            'records' => $records,
            'controller' => $nomeClasse,
            'titoloPagina' => 'Elenco ' . \App\Models\Impianto::NOME_PLURALE,
            'orderBy' => $orderBy,
            'ordinamenti' => $ordinamenti,
            'filtro' => $filtro ?? 'tutti',
            'conFiltro' => $this->conFiltro,
            'testoNuovo' => $this->puoCreareImpianto() ? 'Nuovo ' . \App\Models\Impianto::NOME_SINGOLARE : null,
            'testoCerca' => 'Cerca in nome impianto',
            'statistiche' => $this->statisticheImpianti(),
            'mostraAmministratore' => $mostraAmministratore,
            'mostraResponsabile' => $mostraResponsabile,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applicaFiltri($request)
    {
        $queryBuilder = \App\Models\Impianto::query()
            ->with('amministratore:id,ragione_sociale')
            ->with('responsabileImpianto')
            ->with('comune');
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

        // Filtro stato_impianto
        if ($request->filled('stato_impianto')) {
            $queryBuilder->where('stato_impianto', $request->get('stato_impianto'));
            $this->conFiltro = true;
        }

        // Filtro tipologia
        if ($request->filled('tipologia')) {
            $queryBuilder->where('tipologia', $request->get('tipologia'));
            $this->conFiltro = true;

        }


        // Filtro amministratore
        if ($request->filled('amministratore_id')) {
            $queryBuilder->where('amministratore_id', $request->get('amministratore_id'));
            $this->conFiltro = true;

        }

        // Filtro città
        if ($request->filled('citta')) {
            $queryBuilder->where('citta', $request->get('citta'));
            $this->conFiltro = true;
        }

        // Filtro concentratore
        if ($request->filled('concentratore_id')) {
            $concentratore_id = $request->get('concentratore_id');

            if ($concentratore_id === 'con_concentratore') {
                $queryBuilder->whereNotNull('concentratore_id');
            } elseif ($concentratore_id === 'senza_concentratore') {
                $queryBuilder->whereNull('concentratore_id');
            } else {
                $queryBuilder->where('concentratore_id', $concentratore_id);
            }
            $this->conFiltro = true;

        }

        // Filtri servizi
        if ($request->filled('servizio_riscaldamento')) {
            $queryBuilder->where('servizio_riscaldamento', true);
            $this->conFiltro = true;

        }

        if ($request->filled('servizio_acs')) {
            $queryBuilder->where('servizio_acs', true);
            $this->conFiltro = true;

        }

        if ($request->filled('servizio_gas')) {
            $queryBuilder->where('servizio_gas', true);
            $this->conFiltro = true;

        }

        if ($request->filled('servizio_luce')) {
            $queryBuilder->where('servizio_luce', true);
            $this->conFiltro = true;

        }

        return $queryBuilder;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $record = new Impianto();
        $record->stato_impianto = StatoImpiantoEnum::attivo->value;
        return view('Backend.Impianto.edit', [
            'record' => $record,
            'titoloPagina' => 'Nuovo ' . Impianto::NOME_SINGOLARE,
            'controller' => get_class($this),
            'breadcrumbs' => [action([ImpiantoController::class, 'index']) => 'Torna a elenco ' . Impianto::NOME_PLURALE],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(null));
        $record = new Impianto();
        $record->azienda_servizio_id = Auth::user()->aziendaServizio?->id;
        $this->salvaDati($record, $request);
        return $this->backToIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->tab(\request(), $id, 'tab_unita_immobiliari');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = Impianto::find($id);
        abort_if(!$record, 404, 'Questo impianto non esiste');
        if (false) {
            $eliminabile = 'Non eliminabile perchè presente in ...';
        } else {
            $eliminabile = true;
        }
        return view('Backend.Impianto.edit', [
            'record' => $record,
            'controller' => ImpiantoController::class,
            'titoloPagina' => 'Modifica ' . Impianto::NOME_SINGOLARE,
            'eliminabile' => $eliminabile,
            'breadcrumbs' => [action([ImpiantoController::class, 'index']) => 'Torna a elenco ' . Impianto::NOME_PLURALE],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = Impianto::find($id);
        abort_if(!$record, 404, 'Questo ' . Impianto::NOME_SINGOLARE . ' non esiste');
        $request->validate($this->rules($id));
        $this->salvaDati($record, $request);

        return $this->backToIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Impianto::find($id);
        abort_if(!$record, 404, 'Questo ' . Impianto::NOME_SINGOLARE . ' non esiste');
        $record->delete();

        return [
            'success' => true,
            'redirect' => action([ImpiantoController::class, 'index']),
        ];
    }

    public function tab(Request $request, string $id, string $tab)
    {
        $record = Impianto::query()
            ->withCount(['unitaImmobiliari', 'dispositivi'])
            //->withCount(['unitaImmobiliari', 'dispositiviMisura', 'periodiContabilizzazione'])
            ->find($id);

        abort_if(!$record, 404, 'Questo impianto non esiste');

        $records = null;
        $tabs = ['tab_unita_immobiliari', 'tab_dispositivi', 'tab_periodi', 'tab_bollettini'];
        $testoCerca = null;
        $statistiche = null;
        switch ($tab) {
            case 'tab_unita_immobiliari':
                $records = $this->tabUnitaImmobiliariRecords($id, $tab, $request);
                break;

            case 'tab_dispositivi':
                $records = $this->tabDispositiviRecords($id, $tab, $request);
                $statistiche = $this->getStatisticheDispositivi($id);
                $testoCerca = 'Cerca in';
                break;

            case 'tab_periodi':
                $records = $this->tabPeriodiRecords($id, $tab, $request);
                break;

            case 'tab_bollettini':
                $records = $this->tabBollettiniRecords($id, $tab, $request);
                break;

            case 'tab_generali':
                $records = [];
                break;

            default:
                abort(404, 'Questo tab non esiste');
        }

        // Se è una richiesta AJAX (per la paginazione con filtri), restituisci solo il contenuto del tab
        if ($request->ajax()) {

            return [
                'html' => base64_encode(view('Backend.Impianto.show.tab' . \Str::of($tab)->remove('tab_')->title()->remove('_') . 'tabella', [
                    'record' => $record,
                    'records' => $records,
                    'controller' => ImpiantoController::class,
                ]))
            ];

        }

        return view('Backend.Impianto.show', [
            'record' => $record,
            'records' => $records,
            'controller' => ImpiantoController::class,
            'titoloPagina' => ucfirst(Impianto::NOME_SINGOLARE),
            'breadcrumbs' => [action([ImpiantoController::class, 'index']) => 'Torna a elenco ' . Impianto::NOME_PLURALE],
            'tabs' => $tabs,
            'tab' => $tab,
            'testoCerca' => $testoCerca,
            'conFiltro' => $this->conFiltro,
            'statistiche' => $statistiche,
        ]);
    }

    private function tabUnitaImmobiliariRecords(string $id, string $tab, Request $request)
    {
        $query = UnitaImmobiliare::query()
            ->where('impianto_id', $id)
            ->with(['user']);

        // Filtro di ricerca generale
        if ($request->filled('cerca_no_ajax')) {
            $cerca = $request->get('cerca_no_ajax');
            $query->where(function ($q) use ($cerca) {
                $q->where('scala', 'like', "%{$cerca}%")
                    ->orWhere('piano', 'like', "%{$cerca}%")
                    ->orWhere('interno', 'like', "%{$cerca}%")
                    ->orWhere('nominativo_unita', 'like', "%{$cerca}%");
            });
        }

        // Filtro per tipologia
        if ($request->filled('tipologia')) {
            $this->conFiltro = true;
            $query->where('tipologia', $request->get('tipologia'));
        }

        // Filtro per piano
        if ($request->filled('piano')) {
            $query->where('piano', $request->get('piano'));
            $this->conFiltro = true;
        }

        // Filtro per scala
        if ($request->filled('scala')) {
            $scala = $request->get('scala');

            if ($scala === 'con_scala') {
                $query->whereNotNull('scala');
            } elseif ($scala === 'senza_scala') {
                $query->whereNull('scala');
            } else {
                $query->where('scala', $scala);
            }
            $this->conFiltro = true;
        }

        // Filtro per millesimi
        if ($request->filled('millesimi')) {
            $millesimi = $request->get('millesimi');

            switch ($millesimi) {
                case 'con_riscaldamento':
                    $query->where('millesimi_riscaldamento', '>', 0);
                    break;
                case 'con_acs':
                    $query->where('millesimi_acs', '>', 0);
                    break;
                case 'senza_millesimi':
                    $query->where('millesimi_riscaldamento', '<=', 0)
                        ->where('millesimi_acs', '<=', 0);
                    break;
            }
            $this->conFiltro = true;
        }

        return $query->orderBy('scala')->orderBy('piano')->orderBy('interno')->paginate(20);
    }


    private function tabDispositiviRecords(string $id, string $tab, Request $request)
    {
        $query = DispositivoMisura::query()
            ->where('impianto_id', $id)
            ->with(['unitaImmobiliare', 'concentratore']);


        // Filtro per ricerca AJAX (mantieni così)
        if ($request->filled('cerca')) {
            $search = $request->get('cerca');
            $query->where(function ($q) use ($search) {
                $q->where('matricola', 'like', "%{$search}%");
            });
        }


        if ($request->filled('cerca_no_ajax')) {
            $search = $request->get('cerca_no_ajax');
            $query->where(function ($q) use ($search) {
                $q->where('matricola', 'like', "%{$search}%")
                    ->orWhere('nome_dispositivo', 'like', "%{$search}%")
                    ->orWhere('descrizione_1', 'like', "%{$search}%")
                    ->orWhere('descrizione_2', 'like', "%{$search}%")
                    ->orWhere('marca', 'like', "%{$search}%")
                    ->orWhere('modello', 'like', "%{$search}%");
            });
        }

        // Filtro per tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->get('tipo'));
            $this->conFiltro = true;
        }

        // Filtro per stato_impianto
        if ($request->filled('stato_impianto')) {
            $query->where('stato_impianto', $request->get('stato_impianto'));
            $this->conFiltro = true;
        }

        // Filtro per concentratore
        if ($request->filled('concentratore_id')) {
            $filtroConcentratore = $request->get('concentratore_id');

            if ($filtroConcentratore === 'con_concentratore') {
                $query->whereNotNull('concentratore_id');
            } elseif ($filtroConcentratore === 'senza_concentratore') {
                $query->whereNull('concentratore_id');
            }
            $this->conFiltro = true;
        }

        // Filtro per unità immobiliare
        if ($request->filled('unita_immobiliare_id')) {
            $filtroUnita = $request->get('unita_immobiliare_id');

            if ($filtroUnita === 'con_unita') {
                $query->whereNotNull('unita_immobiliare_id');
            } elseif ($filtroUnita === 'senza_unita') {
                $query->whereNull('unita_immobiliare_id');
            }
            $this->conFiltro = true;
        }

        return $query->orderBy('matricola')->paginate(20);
    }


    private function tabPeriodiRecords(string $id, string $tab, Request $request)
    {
        $query = PeriodoContabilizzazione::query()
            ->where('impianto_id', $id);

        // Filtro per ricerca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('codice', 'like', "%{$search}%")
                    ->orWhere('operatore_letture', 'like', "%{$search}%");
            });
        }

        // Filtro per stato
        if ($request->filled('stato')) {
            $query->where('stato', $request->get('stato'));
        }

        return $query->orderBy('data_inizio', 'desc')->paginate(20);
    }

    private function tabBollettiniRecords(string $id, string $tab, Request $request)
    {
        $query = Bollettino::query()
            ->where('impianto_id', $id)
            ->with(['unitaImmobiliare', 'periodo', 'caricatoDa']);

        // Filtro di ricerca generale
        if ($request->filled('cerca_no_ajax')) {
            $cerca = $request->get('cerca_no_ajax');
            $query->where(function ($q) use ($cerca) {
                $q->whereHas('unitaImmobiliare', function ($subQ) use ($cerca) {
                    $subQ->where('nominativo_unita', 'like', "%{$cerca}%")
                        ->orWhere('scala', 'like', "%{$cerca}%")
                        ->orWhere('piano', 'like', "%{$cerca}%")
                        ->orWhere('interno', 'like', "%{$cerca}%");
                })
                    ->orWhereHas('periodo', function ($subQ) use ($cerca) {
                        $subQ->where('codice', 'like', "%{$cerca}%");
                    });
            });
            $this->conFiltro = true;
        }

        // Filtro per stato pagamento
        if ($request->filled('stato_pagamento')) {
            $query->where('stato_pagamento', $request->get('stato_pagamento'));
            $this->conFiltro = true;
        }

        // Filtro per periodo
        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->get('periodo_id'));
            $this->conFiltro = true;
        }

        // Filtro per scadenza
        if ($request->filled('scadenza')) {
            $scadenza = $request->get('scadenza');

            switch ($scadenza) {
                case 'scaduti':
                    $query->where('data_scadenza', '<', now())
                        ->where('stato_pagamento', '!=', 'pagato');
                    break;
                case 'in_scadenza':
                    $query->where('data_scadenza', '>=', now())
                        ->where('data_scadenza', '<=', now()->addDays(7))
                        ->where('stato_pagamento', '!=', 'pagato');
                    break;
                case 'future':
                    $query->where('data_scadenza', '>', now()->addDays(7));
                    break;
            }
            $this->conFiltro = true;
        }

        // Filtro per visualizzazione
        if ($request->filled('visualizzato')) {
            $visualizzato = $request->get('visualizzato');
            $query->where('visualizzato', $visualizzato === '1');
            $this->conFiltro = true;
        }

        return $query->orderByDesc('id')->paginate(20);
    }

    /**
     * @param Impianto $record
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
            'amministratore_id' => '',
            'matricola_impianto' => 'strtoupper',
            'nome_impianto' => 'app\getInputUcwords',
            'indirizzo' => 'app\getInputUcfirst',
            'cap' => '',
            'citta' => '',
            'stato_impianto' => '',
            'responsabile_impianto_id' => '',
            'tipologia' => '',
            'criterio_ripartizione_numerica' => 'app\getInputNumero',
            'percentuale_quota_fissa' => 'app\getInputNumero',
            'servizio' => '',
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
            'amministratore_id' => ['nullable'],
            'matricola_impianto' => [
                'required',
                'max:255',
                "unique:impianti,matricola_impianto,$id",
            ],
            'nome_impianto' => ['required', 'max:255'],
            'indirizzo' => ['required', 'max:255'],
            'cap' => ['required', 'max:5'],
            'citta' => ['required', 'max:255'],
            'stato_impianto' => ['required', 'max:20'],
            'tipologia' => ['required', 'max:20'],
            'criterio_ripartizione_numerica' => ['required'],
            'percentuale_quota_fissa' => ['required'],
            'servizio' => ['nullable', 'max:255'],
            'note' => ['nullable'],
        ];

        return $rules;
    }

    private function getStatisticheDispositivi(string $impiantoId)
    {
        $query = DispositivoMisura::where('impianto_id', $impiantoId);

        return [
            'totale_dispositivi' => $query->count(),
            'totale_udr' => $query->where('tipo', 'udr')->count(),
            'totale_contatori_acs' => $query->where('tipo', 'contatore_acs')->count(),
            'totale_attivi' => $query->where('stato_dispositivo', 'attivo')->count(),
            // Statistiche aggiuntive utili
            'totale_con_concentratore' => $query->whereNotNull('concentratore_id')->count(),
            'totale_con_unita' => $query->whereNotNull('unita_immobiliare_id')->count(),
        ];
    }


    /**
     * Metodo per estrarre le statistiche degli impianti per la vista index
     * Da inserire nel Controller degli Impianti
     */
    public function statisticheImpianti()
    {
        $query = Impianto::query();


        $totale = $query->count();
        $attivi = (clone $query)->where('stato_impianto', 'attivo')->count();
        $dismessi = (clone $query)->where('stato_impianto', 'dismesso')->count();

        // Conta impianti che hanno concentratori attivi associati
        $conConcentratore = (clone $query)->whereHas('concentratori', function ($q) {
            $q->where('stato_impianto', 'attivo');
        })->count();

        return [
            'totale' => $totale,
            'attivi' => $attivi,
            'con_concentratore' => $conConcentratore,
            'dismessi' => $dismessi,
        ];
    }

    private function mostraAmministratore()
    {
        return Auth::user()->ruolo !== RuoliOperatoreEnum::amministratore_condominio->value;
    }

    private function mostraResponsabileImpianto()
    {
        return Auth::user()->ruolo !== RuoliOperatoreEnum::responsabile_impianto->value;
    }

    private function puoCreareImpianto()
    {
        return in_array(Auth::user()->ruolo, [RuoliOperatoreEnum::admin->value, RuoliOperatoreEnum::azienda_di_servizio->value]);
    }

}
