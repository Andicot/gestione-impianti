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
                        @include('Metronic._inputs_v.inputText',['campo'=>'ragione_sociale', 'required'=>true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'codice_fiscale', 'classe'=>'uppercase'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'partita_iva', 'classe'=>'uppercase','label'=>'Partita IVA'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'telefono', 'altro' => 'onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 43)"'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'email_aziendale', 'classe'=>'lowercase'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'indirizzo'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'cap', 'altro' => 'onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2',['campo'=>'citta', 'selected'=>\App\Models\Comune::selected(old('citta',$record->citta))])
                    </div>
                </div>
                <div class="separator separator-content mt-15 mb-6 flex-grow-1 me-3">
                    <h4 class="w-300px">Dati Referente</h4>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'cognome_referente','autocomplete'=>"off",'required' => true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'nome_referente','autocomplete'=>"off",'required' => true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'telefono_referente', 'altro' => 'onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 43)"'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'email_referente','autocomplete'=>"off",'required' => true,'classe' => 'lowercase'])
                    </div>
                    @if($vecchio)
                        <div class="col-md-6">
                            @include('Metronic._inputs_v.inputSwitch',['campo'=>'attivo','autocomplete'=>"off"])
                        </div>
                    @endif
                    <div class="col-12">
                        @include('Metronic._inputs_v.inputTextAreaCol',['campo'=>'note', 'col'=>2])
                    </div>
                </div>

                @if(!$record->id)
                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">

                        <!--begin::Icon-->
                        <!--begin::Svg Icon | path: assets/media/icons/duotone/Communication/Mail-notification.svg-->
                        <span class="svg-icon svg-icon-2tx svg-icon-primary me-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                 version="1.1">
                                <path
                                    d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z"
                                    fill="#000000"/>
                                <circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5"/>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <!--end::Icon-->
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                            <!--begin::Content-->
                            <div class="mb-3 mb-md-0 fw-bold">
                                <h4 class="text-gray-900 fw-bolder">creazione Utente</h4>
                                <div class="fs-6 text-gray-700 pe-7">I dati del referente verranno usati per creare l'account di accesso.<br>
                                    Verrà inviato un messaggio all'indirizzo mail del referente
                                    con il link per impostare la password di accesso
                                </div>
                            </div>
                            <!--end::Content-->
                            <!--begin::Action-->
                            <button class="btn btn-primary mt-3"
                                    type="submit">{{$record->id?'Salva modifiche':'Crea'}}</button>
                            <!--end::Action-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-4 offset-md-4">
                            <button class="btn btn-primary mt-3"
                                    type="submit">{{$record->id?'Salva modifiche':'Crea'}}</button>
                        </div>
                        <div class="col-md-4 text-end">
                            @if($eliminabile===true)
                                <a class="btn btn-danger mt-3" id="elimina"
                                   href="{{action([$controller,'destroy'],$record->id)}}">Elimina</a>
                            @elseif(is_string($eliminabile))
                                <span data-bs-toggle="tooltip" title="{{$eliminabile}}">
                                    <a class="btn btn-danger mt-3 disabled" href="javascript:void(0)">Elimina</a>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@push('customScript')
    <script src="/assets_backend/js-miei/select2_it.js"></script>
    <script>
        $(function(){
            eliminaHandler('Questa risorsa verrà eliminata definitivamente');
            select2Citta('citta', 'la Citta', 1, 'citta');
        });
    </script>
@endpush
