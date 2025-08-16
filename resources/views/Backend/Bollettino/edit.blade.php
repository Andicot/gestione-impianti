@extends('Metronic._layout._main')
@section('toolbar')
@endsection
@section('content')
    @php($vecchio=$record->id)
    <div class="card">
        <div class="card-body">
            @include('Metronic._components.alertErrori')
            <form method="POST" action="{{\App\storeUpdateHelper($controller,$record?->id)}}" onsubmit="return disableSubmitButton()" enctype="multipart/form-data">
                @csrf
                @method($record->id?'PATCH':'POST')
                <input type="hidden" id="impianto_id" name="impianto_id" value="{{$record->impianto_id}}">
                <div class="row">
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2',['campo'=>'unita_immobiliare_id', 'required'=>true, 'label' => 'Unita Immobiliare','selected' => \App\Models\UnitaImmobiliare::selected(old('unita_immobiliare_id',$record->unita_immobiliare_id))])
                    </div>
                    @if(false)
                        <div class="col-md-6">
                            @include('Metronic._inputs_v.inputSelect2',['campo'=>'periodo_id', 'label' => 'Periodo','selected' => \App\Models\Periodo::selected(old('periodo_id',$record->periodo_id))])
                        </div>
                    @endif
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'importo', 'required'=>true, 'classe'=>'importo'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'metodo_pagamento'])
                    </div>
                    <div class="col-md-6">

                        @include('Metronic._inputs_v.inputFile',['campo' => 'file_documento','required' => true,'help' => '  Formati supportati: PDF (max 10MB)'])
                    </div>
                    <div class="col-12">
                        @include('Metronic._inputs_v.inputTextAreaCol',['campo'=>'note', 'col'=>2])
                    </div>


                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2Enum',['campo'=>'stato_pagamento', 'required'=>true,'classeEnum' => \App\Enums\StatoPagamentoBollettinoEnum::class])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'importo_pagato', 'required'=>false, 'classe'=>'importo'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputTextData',['campo'=>'data_scadenza'])
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-md-4 offset-md-4 text-center">
                        <button class="btn btn-sm btn-primary fw-bold" type="submit" id="submit">
                            {{$vecchio?'Salva modifiche':'Crea '.\App\Models\Bollettino::NOME_SINGOLARE}}
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
    <script src="/assets_backend/js-progetto/select2Comuni.js"></script>

    <script>
        $(function () {
            eliminaHandler('Questa risorsa verrà eliminata definitivamente');
            select2UniversaleUnita('unita_immobiliare_id', 'un UnitaImmobiliare', -1);
            select2Universale('periodo_id', 'un Periodo', 1, 'periodo_id');
            autonumericImporto('importo');
            select2Universale('caricato_da_id', 'un CaricatoDa', 1, 'caricato_da_id');


            $('#data_scadenza').flatpickr({
                allowInput: true,
                locale: 'it',
                dateFormat: 'd/m/Y'
            });
        });
    </script>
@endpush
