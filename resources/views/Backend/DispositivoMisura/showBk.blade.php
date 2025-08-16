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
    @include('Metronic._inputs_v.showInput',['campo'=>'matricola',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'nome_dispositivo',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'descrizione_1',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'descrizione_2',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'marca',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'modello',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'tipo',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'offset','valore' => \App\importo($record->offset)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'data_installazione','valore' => $record->$campo?->format('d/m/Y')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'stato',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'ubicazione',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'unita_immobiliare_id','valore' => ($record->unita_immobiliare_id ? \App\Models\Unitaimmobiliare::find($record->unita_immobiliare_id)?->nome ?? \App\Models\Unitaimmobiliare::find($record->unita_immobiliare_id)?->denominazione ?? $record->unita_immobiliare_id : '')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'impianto_id','valore' => ($record->impianto_id ? \App\Models\Impianto::find($record->impianto_id)?->nome ?? \App\Models\Impianto::find($record->impianto_id)?->denominazione ?? $record->impianto_id : '')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'concentratore_id','valore' => ($record->concentratore_id ? \App\Models\Concentratore::find($record->concentratore_id)?->nome ?? \App\Models\Concentratore::find($record->concentratore_id)?->denominazione ?? $record->concentratore_id : '')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'ultimo_valore_rilevato','valore' => \App\importo($record->ultimo_valore_rilevato)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'data_ultima_lettura',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'creato_automaticamente','valore' => ($record->creato_automaticamente ? '<i class="fas fa-check fs-3" style="color: #26C281;"></i>' : '<i class="fas fa-times fs-3" style="color: #F64E60;"></i>')])
</div>
<div class="col-12">
    @include('Metronic._inputs_v.showInput',['campo'=>'note',])
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
