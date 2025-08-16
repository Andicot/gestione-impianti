<?php

namespace App\Http\Controllers\Backend;

use App\Enums\RuoliOperatoreEnum;
use App\Http\Controllers\Controller;
use App\Models\Amministratore;
use App\Models\AziendaServizio;
use App\Models\Impianto;
use App\Models\User;
use App\Traits\FunzioniUtente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AmministratoreController extends Controller
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
                'html' => base64_encode(view('Aziendadiservizio.Amministratore.tabella', [
                    'records' => $records,
                    'controller' => $nomeClasse,
                ]))
            ];
        }

        return view('Aziendadiservizio.Amministratore.index', [
            'records' => $records,
            'controller' => $nomeClasse,
            'titoloPagina' => 'Elenco ' . \App\Models\Amministratore::NOME_PLURALE,
            'orderBy' => $orderBy,
            'ordinamenti' => $ordinamenti,
            'filtro' => $filtro ?? 'tutti',
            'conFiltro' => $this->conFiltro,
            'testoNuovo' => 'Nuovo ' . \App\Models\Amministratore::NOME_SINGOLARE,
            'testoCerca' => 'Cerca in ragione sociale',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applicaFiltri($request)
    {
        $queryBuilder = \App\Models\Amministratore::query()
            ->withCount('impianti');
        $term = $request->input('cerca');

        if ($term) {
            $searchTerms = collect(explode(' ', trim($term)))
                ->map(fn($term) => trim($term))
                ->filter(fn($term) => !empty($term))
                ->values();
            if ($searchTerms->isNotEmpty()) {
                $queryBuilder->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $searchTerm) {
                        $query->where(function ($subQuery) use ($searchTerm) {
                            $subQuery->where('ragione_sociale', 'LIKE', '%' . $searchTerm . '%')
                                ->orWhere('cognome_referente', 'LIKE', '%' . $searchTerm . '%')
                                ->orWhere('nome_referente', 'LIKE', '%' . $searchTerm . '%');
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
        $record = new Amministratore();
        return view('Aziendadiservizio.Amministratore.edit', [
            'record' => $record,
            'titoloPagina' => 'Nuovo ' . Amministratore::NOME_SINGOLARE,
            'controller' => get_class($this),
            'breadcrumbs' => [action([AmministratoreController::class, 'index']) => 'Torna a elenco ' . Amministratore::NOME_PLURALE],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(null));
        $user = $this->salvaDatiUtente(new User(), $request, 'referente', RuoliOperatoreEnum::amministratore_condominio->value);
        $record = new Amministratore();
        $record->azienda_servizio_id = Auth::user()->aziendaServizio?->id;
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
        $record = Amministratore::find($id);
        abort_if(!$record, 404, 'Questo amministratore non esiste');
        if (false) {
            $eliminabile = 'Non eliminabile perchè presente in ...';
        } else {
            $eliminabile = true;
        }
        return view('Aziendadiservizio.Amministratore.edit', [
            'record' => $record,
            'controller' => AmministratoreController::class,
            'titoloPagina' => 'Modifica ' . Amministratore::NOME_SINGOLARE,
            'eliminabile' => $eliminabile,
            'breadcrumbs' => [action([AmministratoreController::class, 'index']) => 'Torna a elenco ' . Amministratore::NOME_PLURALE],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = Amministratore::find($id);
        abort_if(!$record, 404, 'Questo ' . Amministratore::NOME_SINGOLARE . ' non esiste');
        $request->validate($this->rules($id));
        $this->salvaDati($record, $request);

        return $this->backToIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Amministratore::find($id);
        abort_if(!$record, 404, 'Questo ' . Amministratore::NOME_SINGOLARE . ' non esiste');
        $record->delete();

        return [
            'success' => true,
            'redirect' => action([AmministratoreController::class, 'index']),
        ];
    }

    public function tab(Request $request, string $id, string $tab)
    {
        $record = Amministratore::query()
            ->withCount('impianti')
            ->find($id);
        abort_if(!$record, 404, 'Questa aziendaservizio non esiste');


        $records = null;
        $tabs = ['tab_generali', 'tab_impianti'];


        switch ($tab) {
            case 'tab_impianti':
                $records = $this->tabImpiantiRecords($id, $tab, $request);
                break;

            case 'tab_generali':
                $records = [];
                break;

            default:
                abort(404, 'Questo tab non esiste');
        }

        // Se è una richiesta AJAX (per la paginazione con filtri), restituisci solo il contenuto del tab
        if ($request->ajax()) {
            return view('Backend.Pecora.show.tab' . Str::of($tab)->remove('tab_')->title()->remove('_'), [
                'record' => $record,
                'records' => $records,
                'controller' => AmministratoreController::class,
            ]);
        }

        return view('Aziendadiservizio.Amministratore.show', [
            'record' => $record,
            'records' => $records,
            'controller' => AmministratoreController::class,
            'titoloPagina' => ucfirst(Amministratore::NOME_SINGOLARE),
            'breadcrumbs' => [action([AmministratoreController::class, 'index']) => 'Torna a elenco ' . AziendaServizio::NOME_PLURALE],
            'tabs' => $tabs,
            'tab' => $tab
        ]);

    }

    /**
     * @param Amministratore $record
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
            'ragione_sociale' => 'app\getInputUcwords',
            'codice_fiscale' => 'app\getInputToUpper',
            'partita_iva' => 'strtoupper',
            'telefono_ufficio' => 'app\getInputTelefono',
            'indirizzo_ufficio' => 'app\getInputUcfirst',
            'cap_ufficio' => '',
            'citta_ufficio' => '',
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
            'azienda_servizio_id' => ['nullable'],
            'ragione_sociale' => ['required', 'max:255'],
            'codice_fiscale' => ['nullable', 'max:16', new \App\Rules\CodiceFiscaleRule()],
            'partita_iva' => ['nullable', 'max:11', new \App\Rules\PartitaIvaRule()],
            'telefono_ufficio' => ['nullable', 'max:255', new \App\Rules\TelefonoRule()],
            'indirizzo_ufficio' => ['nullable', 'max:255'],
            'cap_ufficio' => ['nullable', 'max:5'],
            'citta_ufficio' => ['nullable', 'max:255'],
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
        $qb = Impianto::query()
            ->where('amministratore_id', $id)
            ->orderBy('nome_impianto');
        return $qb->paginate()->withQueryString()->withPath(
            action([AmministratoreController::class, 'tab'], ['id' => $id, 'tab' => $tab])
        );
    }

}
