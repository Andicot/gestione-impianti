@extends('Metronic._layout._main')
@section('toolbar')
@endsection
@section('content')
    @php($vecchio=$record->id)
    <div class="card">
        <div class="card-body">
            @include('Metronic._components.alertErrori')
            <form method="POST" action="{{\App\storeUpdateHelper($controller,$record?->id)}}" onsubmit="return disableSubmitButton()">
                @csrf
                @method($record->id?'PATCH':'POST')

                <div class="row">

                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'matricola_impianto', 'required'=>true,'classe' => 'uppercase'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'nome_impianto', 'required'=>true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'indirizzo', 'required'=>true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2',['campo'=>'citta', 'required'=>true, 'selected'=>\App\Models\Comune::selected(old('citta',$record->citta))])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'cap', 'required'=>true, 'altro' => 'onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2Enum',['campo'=>'stato_impianto', 'required'=>true,'classeEnum' => \App\Enums\StatoImpiantoEnum::class])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2Enum',['campo'=>'tipologia', 'required'=>true,'classeEnum' => \App\Enums\TipologiaImpiantoEnum::class])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'criterio_ripartizione_numerica', 'required'=>true, 'classe'=>'importo'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'percentuale_quota_fissa', 'required'=>true, 'classe'=>'importo'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'servizio'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2',['campo'=>'amministratore_id', 'label' => 'Amministratore','selected' => \App\Models\Amministratore::selected(old('amministratore_id',$record->amministratore_id))])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2',['campo'=>'responsabile_impianto_id', 'label' => 'Responsabile Impianto','selected' => \App\Models\ResponsabileImpianto::selected(old('responsabile_impianto_id',$record->responsabile_impianto_id))])
                    </div>
                    <div class="col-12">
                        @include('Metronic._inputs_v.inputTextAreaCol',['campo'=>'note', 'col'=>2])
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-md-4 offset-md-4 text-center">
                        <button class="btn btn-sm btn-primary fw-bold" type="submit" id="submit">
                            {{$vecchio?'Salva modifiche':'Crea '.\App\Models\Impianto::NOME_SINGOLARE}}
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
    <script>
        $(function () {
            eliminaHandler('Questa risorsa verrà eliminata definitivamente');
            select2Universale('azienda_servizio_id', 'un AziendaServizio', 1,);
            select2Universale('amministratore_id', 'un Amministratore', 1);
            select2Universale('responsabile_impianto_id', 'un Responsabile Impianto', 1);
            select2Citta('citta', 'il Citta', 1, 'citta');
            autonumericImporto('importo');
        });
    </script>
@endpush
