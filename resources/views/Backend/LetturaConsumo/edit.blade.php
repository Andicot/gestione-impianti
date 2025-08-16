@extends('Metronic._layout._main')
@section('toolbar')
@endsection
@section('content')
    @php($vecchio=$record->id)
    <div class="card">
    <div class="card-body">
        @include('Metronic._components.alertErrori')
<form method="POST"  action="{{\App\storeUpdateHelper($controller,$record?->id)}}" onsubmit="return disableSubmitButton()">
    @csrf
    @method($record->id?'PATCH':'POST')

<div class="row">
<div class="col-md-6">
    @include('Metronic._inputs_v.inputSelect2',['campo'=>'unita_immobiliare_id', 'label' => 'Unita Immobiliare','selected' => \App\Models\UnitaImmobiliare::selected(old('unita_immobiliare_id',$record->unita_immobiliare_id))])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputSelect2',['campo'=>'periodo_id', 'label' => 'Periodo','selected' => \App\Models\Periodo::selected(old('periodo_id',$record->periodo_id))])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputSelect2',['campo'=>'dispositivo_id', 'label' => 'Dispositivo','selected' => \App\Models\Dispositivo::selected(old('dispositivo_id',$record->dispositivo_id))])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'tipo_consumo', 'required'=>true])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'categoria', 'required'=>true])
</div>

<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'udr_attuale', 'required'=>true, 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'udr_precedente', 'required'=>true, 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'differenza_consumi', 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'unita_misura', 'required'=>true])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'costo_unitario', 'required'=>true, 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'costo_totale', 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputSwitch',['campo'=>'errore'])
</div>
<div class="col-12">
    @include('Metronic._inputs_v.inputTextAreaCol',['campo'=>'descrizione_errore', 'col'=>2])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputSwitch',['campo'=>'anomalia'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputSelect2',['campo'=>'importazione_csv_id', 'label' => 'Importazione Csv','selected' => \App\Models\ImportazioneCsv::selected(old('importazione_csv_id',$record->importazione_csv_id))])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputTextData',['campo'=>'data_lettura', 'required'=>true])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'ora_lettura'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'comfort_termico_attuale', 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'temp_massima_sonde', 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputTextData',['campo'=>'data_registrazione_temp_max'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'temp_tecnica_tt16', 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'comfort_termico_periodo_prec', 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'temp_media_calorifero_prec', 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'udr_storico_1', 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputText',['campo'=>'udr_totali', 'classe'=>'importo'])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputTextDataOra',['campo'=>'data_ora_dispositivo'])
</div>
</div>


    <div class="row mt-3">
        <div class="col-md-4 offset-md-4 text-center">
            <button class="btn btn-sm btn-primary fw-bold" type="submit" id="submit">
                {{$vecchio?'Salva modifiche':'Crea '.\App\Models\LetturaConsumo::NOME_SINGOLARE}}
            </button>
        </div>
        @if($vecchio)
            <div class="col-md-4 text-end">
                @if($eliminabile===true)
                    <a class="btn btn-danger mt-3" id="elimina" href="{{action([$controller,'destroy'],$record->id)}}">Elimina</a>
                @elseif(is_string($eliminabile))
                    <span data-bs-toggle="tooltip" title="{{$eliminabile}}">
                        <a class="btn btn-danger mt-3 disabled" href="javascript:void(0)">Elimina</a>
                    </span>
                @endif
            </div>
        @endif
    </div>
</form>
    </div>
</div>
@endsection

@push('customScript')
<script src="/assets_backend/js-miei/select2_it.js"></script>
<script src="/assets_backend/js-miei/autoNumeric.min.js"></script>
<script src="/assets_backend/js-miei/moment_it.js"></script>
<script src="/assets_backend/js-miei/flatPicker_it.js"></script>
<script>
$(function(){
    eliminaHandler('Questa risorsa verrà eliminata definitivamente');
select2Universale('unita_immobiliare_id', 'una UnitaImmobiliare', 1, 'unita_immobiliare_id');
select2Universale('periodo_id', 'una Periodo', 1, 'periodo_id');
select2Universale('dispositivo_id', 'una Dispositivo', 1, 'dispositivo_id');
autonumericImporto('importo');
select2Universale('importazione_csv_id', 'una ImportazioneCsv', 1, 'importazione_csv_id');
moment.locale('it');
$('#data_lettura').flatpickr({
                allowInput: true,
                locale: 'it',
                dateFormat: 'd/m/Y'
            });
$('#data_registrazione_temp_max').flatpickr({
                allowInput: true,
                locale: 'it',
                dateFormat: 'd/m/Y'
            });
$('#data_ora_dispositivo').flatpickr({
                allowInput: true,
                locale: 'it',
                dateFormat: 'd/m/Y H:i',
                enableTime: true,
                confirmDate: true,
                minTime: '08:00',
                maxTime: '22:00'
            });
});
</script>
@endpush
