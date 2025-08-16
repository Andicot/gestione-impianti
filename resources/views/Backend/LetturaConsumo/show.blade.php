@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-2">
        @isset($eliminabile)
            @if($eliminabile === true)
                <a href="{{action([$controller,'destroy'],$record->id)}}" class="btn btn-danger mt-3 btn-elimina" data-method="DELETE"><i class="fas fa-trash"></i> Elimina</a>
            @endif
        @endisset
        <a href="{{action([$controller,'edit'],$record->id)}}" class="btn btn-sm btn-primary fw-bold"><i class="fas fa-edit"></i> Modifica</a>
    </div>
@endsection

@section('content')
   <div class="card">
    <div class="card-header">
        <h3 class="card-title">Dettagli</h3>
    </div>
    <div class="card-body">
        <div class="row">
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'unita_immobiliare_id','valore' => ($record->unita_immobiliare_id ? \App\Models\Unitaimmobiliare::find($record->unita_immobiliare_id)?->nome ?? \App\Models\Unitaimmobiliare::find($record->unita_immobiliare_id)?->denominazione ?? $record->unita_immobiliare_id : '')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'periodo_id','valore' => ($record->periodo_id ? \App\Models\Periodo::find($record->periodo_id)?->nome ?? \App\Models\Periodo::find($record->periodo_id)?->denominazione ?? $record->periodo_id : '')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'dispositivo_id','valore' => ($record->dispositivo_id ? \App\Models\Dispositivo::find($record->dispositivo_id)?->nome ?? \App\Models\Dispositivo::find($record->dispositivo_id)?->denominazione ?? $record->dispositivo_id : '')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'tipo_consumo',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'categoria',])
</div>

<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'udr_attuale','valore' => \App\importo($record->udr_attuale)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'udr_precedente','valore' => \App\importo($record->udr_precedente)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'differenza_consumi','valore' => \App\importo($record->differenza_consumi)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'unita_misura',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'costo_unitario','valore' => \App\importo($record->costo_unitario)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'costo_totale','valore' => \App\importo($record->costo_totale)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'errore','valore' => ($record->errore ? '<i class="fas fa-check fs-3" style="color: #26C281;"></i>' : '<i class="fas fa-times fs-3" style="color: #F64E60;"></i>')])
</div>
<div class="col-12">
    @include('Metronic._inputs_v.showInput',['campo'=>'descrizione_errore',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'anomalia','valore' => ($record->anomalia ? '<i class="fas fa-check fs-3" style="color: #26C281;"></i>' : '<i class="fas fa-times fs-3" style="color: #F64E60;"></i>')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'importazione_csv_id','valore' => ($record->importazione_csv_id ? \App\Models\Importazionecsv::find($record->importazione_csv_id)?->nome ?? \App\Models\Importazionecsv::find($record->importazione_csv_id)?->denominazione ?? $record->importazione_csv_id : '')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'data_lettura','valore' => $record->$campo?->format('d/m/Y')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'ora_lettura',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'comfort_termico_attuale','valore' => \App\importo($record->comfort_termico_attuale)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'temp_massima_sonde','valore' => \App\importo($record->temp_massima_sonde)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'data_registrazione_temp_max','valore' => $record->$campo?->format('d/m/Y')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'temp_tecnica_tt16','valore' => \App\importo($record->temp_tecnica_tt16)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'comfort_termico_periodo_prec','valore' => \App\importo($record->comfort_termico_periodo_prec)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'temp_media_calorifero_prec','valore' => \App\importo($record->temp_media_calorifero_prec)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'udr_storico_1','valore' => \App\importo($record->udr_storico_1)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'udr_totali','valore' => \App\importo($record->udr_totali)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'data_ora_dispositivo',])
</div>
</div>

    </div>
</div>
@endsection

@push('customScript')
<script>
$(function(){
    eliminaHandler('Questa risorsa verrà eliminata definitivamente');
eliminaHandler('Sei sicuro di voler eliminare questa risorsa?');
});
</script>
@endpush
