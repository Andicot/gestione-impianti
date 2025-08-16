<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LetturaConsumo;
use DB;

class LetturaConsumoController extends Controller
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
                'html' => base64_encode(view('Backend.LetturaConsumo.tabella', [
                    'records' => $records,
                    'controller' => $nomeClasse,
                ]))
            ];
        }

        return view('Backend.LetturaConsumo.index', [
            'records' => $records,
            'controller' => $nomeClasse,
            'titoloPagina' => 'Elenco ' . \App\Models\LetturaConsumo::NOME_PLURALE,
            'orderBy' => $orderBy,
            'ordinamenti' => $ordinamenti,
            'filtro' => $filtro ?? 'tutti',
            'conFiltro' => $this->conFiltro,
            'testoNuovo' => 'Nuova ' . \App\Models\LetturaConsumo::NOME_SINGOLARE,
            'testoCerca' => 'Cerca in: descrizione errore',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applicaFiltri($request)
    {
        $queryBuilder = \App\Models\LetturaConsumo::query()
        ->with(['dispositivo'=>function($q){
            return $q->senzaFiltroOperatore()->select('id', 'matricola');
        }]);
        $term = $request->input('cerca');
        if ($term) {
            // Ricerca automatica su 1 campo: descrizione_errore
        $searchColumns = ['descrizione_errore'];

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
    public function create()
    {
                $record=new LetturaConsumo();
        return view('Backend.LetturaConsumo.edit', [
            'record' => $record,
            'titoloPagina' => 'Nuova ' . LetturaConsumo::NOME_SINGOLARE,
            'controller' => get_class($this),
            'breadcrumbs' => [action([LetturaConsumoController::class, 'index']) => 'Torna a elenco ' . LetturaConsumo::NOME_PLURALE],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
                $request->validate($this->rules(null));
        $record=new LetturaConsumo();
        $this->salvaDati($record, $request);
        return $this->backToIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
                $record = LetturaConsumo::find($id);
        abort_if(!$record, 404, 'Questa letturaconsumo non esiste');
        return view('Backend.LetturaConsumo.show', [
            'record' => $record,
            'controller' => LetturaConsumoController::class,
            'titoloPagina' => ucfirst(LetturaConsumo::NOME_SINGOLARE),
            'breadcrumbs' => [action([LetturaConsumoController::class, 'index']) => 'Torna a elenco ' . LetturaConsumo::NOME_PLURALE],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
                $record = LetturaConsumo::find($id);
        abort_if(!$record, 404, 'Questa letturaconsumo non esiste');
        if (false) {
            $eliminabile = 'Non eliminabile perchè presente in ...';
        } else {
            $eliminabile = true;
        }
        return view('Backend.LetturaConsumo.edit', [
            'record' => $record,
            'controller' => LetturaConsumoController::class,
            'titoloPagina' => 'Modifica ' . LetturaConsumo::NOME_SINGOLARE,
            'eliminabile' => $eliminabile,
            'breadcrumbs' => [action([LetturaConsumoController::class, 'index']) => 'Torna a elenco ' . LetturaConsumo::NOME_PLURALE],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
                $record = LetturaConsumo::find($id);
        abort_if(!$record, 404, 'Questa ' . LetturaConsumo::NOME_SINGOLARE . ' non esiste');
        $request->validate($this->rules($id));
        $this->salvaDati($record, $request);

        return $this->backToIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
                $record = LetturaConsumo::find($id);
        abort_if(!$record, 404, 'Questa ' . LetturaConsumo::NOME_SINGOLARE . ' non esiste');
        $record->delete();

        return [
            'success' => true,
            'redirect' => action([LetturaConsumoController::class,'index']),
        ];
    }

    /**
     * @param LetturaConsumo $record
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
            'unita_immobiliare_id'=>'',
            'periodo_id'=>'',
            'dispositivo_id'=>'',
            'tipo_consumo'=>'',
            'categoria'=>'',
            'udr_attuale'=>'app\getInputNumero',
            'udr_precedente'=>'app\getInputNumero',
            'differenza_consumi'=>'app\getInputNumero',
            'unita_misura'=>'',
            'costo_unitario'=>'app\getInputNumero',
            'costo_totale'=>'app\getInputNumero',
            'errore'=>'app\getInputCheckbox',
            'descrizione_errore'=>'',
            'anomalia'=>'app\getInputCheckbox',
            'importazione_csv_id'=>'',
            'data_lettura'=>'app\getInputData',
            'ora_lettura'=>'',
            'comfort_termico_attuale'=>'app\getInputNumero',
            'temp_massima_sonde'=>'app\getInputNumero',
            'data_registrazione_temp_max'=>'app\getInputData',
            'temp_tecnica_tt16'=>'app\getInputNumero',
            'comfort_termico_periodo_prec'=>'app\getInputNumero',
            'temp_media_calorifero_prec'=>'app\getInputNumero',
            'udr_storico_1'=>'app\getInputNumero',
            'udr_totali'=>'app\getInputNumero',
            'data_ora_dispositivo'=>'app\getInputDataOra',
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


    protected function rules($id=null)
    {
        $rules=[
     'unita_immobiliare_id'=>['nullable'],
     'periodo_id'=>['nullable'],
     'dispositivo_id'=>['nullable'],
     'tipo_consumo'=>['required','max:20'],
     'categoria'=>['required','max:30'],
     'udr_attuale'=>['required'],
     'udr_precedente'=>['required'],
     'differenza_consumi'=>['nullable'],
     'unita_misura'=>['required','max:10'],
     'costo_unitario'=>['required'],
     'costo_totale'=>['nullable'],
     'errore'=>['nullable'],
     'descrizione_errore'=>['nullable'],
     'anomalia'=>['nullable'],
     'importazione_csv_id'=>['nullable'],
     'data_lettura'=>['required',new \App\Rules\DataItalianaRule()],
     'ora_lettura'=>['nullable'],
     'comfort_termico_attuale'=>['nullable'],
     'temp_massima_sonde'=>['nullable'],
     'data_registrazione_temp_max'=>['nullable',new \App\Rules\DataItalianaRule()],
     'temp_tecnica_tt16'=>['nullable'],
     'comfort_termico_periodo_prec'=>['nullable'],
     'temp_media_calorifero_prec'=>['nullable'],
     'udr_storico_1'=>['nullable'],
     'udr_totali'=>['nullable'],
     'data_ora_dispositivo'=>['nullable',new \App\Rules\DataOraItalianaRule()],
];

        return $rules;
    }

}
