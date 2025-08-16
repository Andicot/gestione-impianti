<?php

namespace App\Http\Controllers\Backend;

use App\Enums\FrequenzaScansioneDispositivoEnum;
use App\Enums\StatoImpiantoEnum;
use App\Http\Controllers\Controller;
use App\Models\Concentratore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConcentratoreController extends Controller
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
            'matricola' => ['testo' => 'matricola', 'filtro' => function ($q) {
                return $q->orderBy('matricola');
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

        if ($request->ajax()) {
            return [
                'html' => base64_encode(view('Backend.Concentratore.tabella', [
                    'records' => $records,
                    'controller' => $nomeClasse,
                ]))
            ];
        }

        return view('Backend.Concentratore.index', [
            'records' => $records,
            'controller' => $nomeClasse,
            'titoloPagina' => 'Elenco ' . \App\Models\Concentratore::NOME_PLURALE,
            'orderBy' => $orderBy,
            'ordinamenti' => $ordinamenti,
            'filtro' => $filtro ?? 'tutti',
            'conFiltro' => $this->conFiltro,
            'testoNuovo' => 'Nuovo ' . \App\Models\Concentratore::NOME_SINGOLARE,
            'testoCerca' => null,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applicaFiltri($request)
    {
        $queryBuilder = \App\Models\Concentratore::query();
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
        $record = new Concentratore();
        $record->frequenza_scansione = FrequenzaScansioneDispositivoEnum::settimanale->value;
        $record->stato = 'attivo';
        return view('Backend.Concentratore.edit', [
            'record' => $record,
            'titoloPagina' => 'Nuovo ' . Concentratore::NOME_SINGOLARE,
            'controller' => get_class($this),
            'breadcrumbs' => [action([ConcentratoreController::class, 'index']) => 'Torna a elenco ' . Concentratore::NOME_PLURALE],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(null));
        $record = new Concentratore();
        $record->azienda_servizio_id = Auth::user()->aziendaServizio?->id;
        $this->salvaDati($record, $request);
        return $this->backToIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Concentratore::find($id);
        abort_if(!$record, 404, 'Questo concentratore non esiste');
        return view('Backend.Concentratore.show', [
            'record' => $record,
            'controller' => ConcentratoreController::class,
            'titoloPagina' => ucfirst(Concentratore::NOME_SINGOLARE),
            'breadcrumbs' => [action([ConcentratoreController::class, 'index']) => 'Torna a elenco ' . Concentratore::NOME_PLURALE],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = Concentratore::find($id);
        abort_if(!$record, 404, 'Questo concentratore non esiste');
        if (false) {
            $eliminabile = 'Non eliminabile perchè presente in ...';
        } else {
            $eliminabile = true;
        }
        return view('Backend.Concentratore.edit', [
            'record' => $record,
            'controller' => ConcentratoreController::class,
            'titoloPagina' => 'Modifica ' . Concentratore::NOME_SINGOLARE,
            'eliminabile' => $eliminabile,
            'breadcrumbs' => [action([ConcentratoreController::class, 'index']) => 'Torna a elenco ' . Concentratore::NOME_PLURALE],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = Concentratore::find($id);
        abort_if(!$record, 404, 'Questo ' . Concentratore::NOME_SINGOLARE . ' non esiste');
        $request->validate($this->rules($id));
        $this->salvaDati($record, $request);

        return $this->backToIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Concentratore::find($id);
        abort_if(!$record, 404, 'Questo ' . Concentratore::NOME_SINGOLARE . ' non esiste');
        $record->delete();

        return [
            'success' => true,
            'redirect' => action([ConcentratoreController::class, 'index']),
        ];
    }

    /**
     * @param Concentratore $record
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
            'marca' => '',
            'modello' => '',
            'matricola' => '',
            'frequenza_scansione' => '',
            'ip_connessione' => '',
            'ip_invio_csv' => '',
            'endpoint_csv' => '',
            'token_autenticazione' => '',
            'ultima_comunicazione' => '',
            'ultimo_csv_ricevuto' => '',
            'note' => '',
        ];
        foreach ($campi as $campo => $funzione) {
            $valore = $request->$campo;
            if ($funzione != '') {
                $valore = $funzione($valore);
            }
            $record->$campo = $valore;
        }
        $record->stato = $request->input('stato') ? StatoImpiantoEnum::attivo->value : StatoImpiantoEnum::non_attivo->value;

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
            'marca' => ['required', 'max:255'],
            'modello' => ['required', 'max:255'],
            'matricola' => ['required', 'max:255'],
            'frequenza_scansione' => ['required', 'max:20'],
            'ip_connessione' => ['nullable', 'max:45'],
            'ip_invio_csv' => ['nullable', 'max:45'],
            'endpoint_csv' => ['nullable', 'max:255'],
            'token_autenticazione' => ['nullable', 'max:255'],
            'ultima_comunicazione' => ['nullable'],
            'ultimo_csv_ricevuto' => ['nullable'],
            'note' => ['nullable'],
        ];

        return $rules;
    }

}
