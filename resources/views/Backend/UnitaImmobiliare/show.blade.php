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
    @include('Metronic._inputs_v.showInput',['campo'=>'azienda_servizio_id','valore' => ($record->azienda_servizio_id ? \App\Models\Aziendaservizio::find($record->azienda_servizio_id)?->nome ?? \App\Models\Aziendaservizio::find($record->azienda_servizio_id)?->denominazione ?? $record->azienda_servizio_id : '')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'impianto_id','valore' => ($record->impianto_id ? \App\Models\Impianto::find($record->impianto_id)?->nome ?? \App\Models\Impianto::find($record->impianto_id)?->denominazione ?? $record->impianto_id : '')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'scala',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'piano',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'interno',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'nominativo_unita',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'tipologia',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'millesimi_riscaldamento','valore' => \App\importo($record->millesimi_riscaldamento)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'millesimi_acs','valore' => \App\importo($record->millesimi_acs)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'metri_quadri','valore' => \App\importo($record->metri_quadri)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'corpo_scaldante',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'contatore_acs_numero',])
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
