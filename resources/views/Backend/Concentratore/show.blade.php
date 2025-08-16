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
    @include('Metronic._inputs_v.inputShow',['campo'=>'marca',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'modello',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'matricola',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'frequenza_scansione',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'stato',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'ip_connessione',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'ip_invio_csv',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'endpoint_csv',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'token_autenticazione',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'ultima_comunicazione',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.inputShow',['campo'=>'ultimo_csv_ricevuto',])
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
