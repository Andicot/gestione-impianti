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
    @include('Metronic._inputs_v.showInput',['campo'=>'importo','valore' => \App\importo($record->importo)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'metodo_pagamento',])
</div>
<div class="col-12">
    @include('Metronic._inputs_v.showInput',['campo'=>'note',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'pdf_allegato',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'nome_file_originale',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'mime_type',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'dimensione_file',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'caricato_da_id','valore' => ($record->caricato_da_id ? \App\Models\Caricatoda::find($record->caricato_da_id)?->nome ?? \App\Models\Caricatoda::find($record->caricato_da_id)?->denominazione ?? $record->caricato_da_id : '')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'data_caricamento',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'visualizzato','valore' => ($record->visualizzato ? '<i class="fas fa-check fs-3" style="color: #26C281;"></i>' : '<i class="fas fa-times fs-3" style="color: #F64E60;"></i>')])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'data_visualizzazione',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'stato_pagamento',])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'importo_pagato','valore' => \App\importo($record->importo_pagato)])
</div>
<div class="col-md-6">
    @include('Metronic._inputs_v.showInput',['campo'=>'data_scadenza','valore' => $record->$campo?->format('d/m/Y')])
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