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
                <input type="hidden" name="impianto_id" value="{{$record->impianto_id}}">
                <div class="row">
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'scala'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'piano', 'required'=>true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'interno', 'required'=>true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'nominativo_unita','label' => 'Nominativo Unità'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2Enum',['campo'=>'tipologia', 'required'=>true,'classeEnum' => \App\Enums\TipoUnitaImmobiliareEnum::class])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'millesimi_riscaldamento', 'required'=>true, 'classe'=>'importo'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'millesimi_acs', 'required'=>true, 'classe'=>'importo'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'metri_quadri', 'classe'=>'importo'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'corpo_scaldante'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'contatore_acs_numero'])
                    </div>
                    <div class="col-12">
                        @include('Metronic._inputs_v.inputTextAreaCol',['campo'=>'note', 'col'=>2])
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-md-4 offset-md-4 text-center">
                        <button class="btn btn-sm btn-primary fw-bold" type="submit" id="submit">
                            {{$vecchio?'Salva modifiche':'Crea '.\App\Models\UnitaImmobiliare::NOME_SINGOLARE}}
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
            select2Universale('azienda_servizio_id', 'una AziendaServizio', 1, 'azienda_servizio_id');
            select2Universale('impianto_id', 'una Impianto', 1, 'impianto_id');
            autonumericImporto('importo');
            select2Universale('user_id', 'una User', 1, 'user_id');
        });
    </script>
@endpush
