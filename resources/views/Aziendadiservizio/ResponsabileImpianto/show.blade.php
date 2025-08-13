@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-3 mb-6">
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
    @include('Metronic._inputs_v.inputShow',['campo'=>'azienda_servizio_id','valore' => ($record->azienda_servizio_id ? \App\Models\Aziendaservizio::find($record->azienda_servizio_id)?->nome ?? \App\Models\Aziendaservizio::find($record->azienda_servizio_id)?->denominazione ?? $record->azienda_servizio_id : '')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'cognome',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'nome',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'codice_fiscale',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'telefono',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'cellulare',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'email',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'attivo','valore' => ($record->attivo ? '<i class="fas fa-check fs-3" style="color: #26C281;"></i>' : '<i class="fas fa-times fs-3" style="color: #F64E60;"></i>')])
</div>
<div class="col-12">
    @include('Metronic._inputs_v.inputShow',['campo'=>'note',])
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