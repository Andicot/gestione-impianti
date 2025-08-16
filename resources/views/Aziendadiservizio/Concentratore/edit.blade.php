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
                        @include('Metronic._inputs_v.inputText',['campo'=>'marca', 'required'=>true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'modello', 'required'=>true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'matricola', 'required'=>true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2Enum',['campo'=>'frequenza_scansione', 'required'=>true,'classeEnum' => \App\Enums\FrequenzaScansioneDispositivoEnum::class])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputsProgetto.inputSwitchAttivo',['campo'=>'stato'])
                    </div>
                    @if(false)
                        <div class="col-md-6">
                            @include('Metronic._inputs_v.inputText',['campo'=>'ip_connessione'])
                        </div>
                        <div class="col-md-6">
                            @include('Metronic._inputs_v.inputText',['campo'=>'ip_invio_csv'])
                        </div>
                        <div class="col-md-6">
                            @include('Metronic._inputs_v.inputText',['campo'=>'endpoint_csv'])
                        </div>
                        <div class="col-md-6">
                            @include('Metronic._inputs_v.inputText',['campo'=>'token_autenticazione'])
                        </div>
                        <div class="col-md-6">
                            @include('Metronic._inputs_v.inputText',['campo'=>'ultima_comunicazione'])
                        </div>
                        <div class="col-md-6">
                            @include('Metronic._inputs_v.inputText',['campo'=>'ultimo_csv_ricevuto'])
                        </div>
                    @endif
                    <div class="col-12">
                        @include('Metronic._inputs_v.inputTextAreaCol',['campo'=>'note', 'col'=>2])
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-md-4 offset-md-4 text-center">
                        <button class="btn btn-sm btn-primary fw-bold" type="submit" id="submit">
                            {{$vecchio?'Salva modifiche':'Crea '.\App\Models\Concentratore::NOME_SINGOLARE}}
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
    <script>
        $(function () {
            eliminaHandler('Questa risorsa verrà eliminata definitivamente');
        });
    </script>
@endpush
