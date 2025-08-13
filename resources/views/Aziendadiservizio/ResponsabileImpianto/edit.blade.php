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
                        @include('Metronic._inputs_v.inputText',['campo'=>'cognome', 'required'=>true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'nome', 'required'=>true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'codice_fiscale',  'classe'=>'uppercase'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'telefono', 'altro' => 'onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 43)"'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'cellulare', 'altro' => 'onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 43)"'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'email', 'required'=>true, 'classe'=>'lowercase'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSwitch',['campo'=>'attivo'])
                    </div>
                    <div class="col-12">
                        @include('Metronic._inputs_v.inputTextAreaCol',['campo'=>'note', 'col'=>2])
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-md-4 offset-md-4 text-center">
                        <button class="btn btn-sm btn-primary fw-bold" type="submit" id="submit">
                            {{$vecchio?'Salva modifiche':'Crea '.\App\Models\ResponsabileImpianto::NOME_SINGOLARE}}
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
    <script>
        $(function () {
            eliminaHandler('Questa risorsa verrà eliminata definitivamente');
        });
    </script>
@endpush
