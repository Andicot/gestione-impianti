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
                        @include('Metronic._inputs_v.inputText',['campo'=>'matricola', 'required'=>true])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'nome_dispositivo'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'descrizione_1'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'descrizione_2'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'marca'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'modello'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2Enum',['campo'=>'tipo', 'required'=>true,'classeEnum' => \App\Enums\TipoDispositivoEnum::class])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'offset', 'required'=>true, 'classe'=>'importo'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputTextData',['campo'=>'data_installazione'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2Enum',['campo'=>'stato', 'required'=>true,'classeEnum' => \App\Enums\StatoDispositivoEnum::class])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'ubicazione'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2',['campo'=>'impianto_id', 'label' => 'Impianto','selected' => \App\Models\Impianto::selected(old('impianto_id',$record->impianto_id))])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2',['campo'=>'unita_immobiliare_id', 'label' => 'Unita Immobiliare','selected' => \App\Models\UnitaImmobiliare::selected(old('unita_immobiliare_id',$record->unita_immobiliare_id))])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2',['campo'=>'concentratore_id', 'label' => 'Concentratore','selected' => \App\Models\Concentratore::selected(old('concentratore_id',$record->concentratore_id))])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputText',['campo'=>'ultimo_valore_rilevato', 'classe'=>'importo'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputTextData',['campo'=>'data_ultima_lettura'])
                    </div>
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSwitch',['campo'=>'creato_automaticamente'])
                    </div>
                    <div class="col-12">
                        @include('Metronic._inputs_v.inputTextAreaCol',['campo'=>'note', 'col'=>2])
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-md-4 offset-md-4 text-center">
                        <button class="btn btn-sm btn-primary fw-bold" type="submit" id="submit">
                            {{$vecchio?'Salva modifiche':'Crea '.\App\Models\DispositivoMisura::NOME_SINGOLARE}}
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
    <script src="/assets_backend/js-miei/autoNumeric.min.js"></script>
    <script src="/assets_backend/js-miei/moment_it.js"></script>
    <script src="/assets_backend/js-miei/flatPicker_it.js"></script>
    <script src="/assets_backend/js-miei/select2_it.js"></script>
    <script>
        $(function () {
            eliminaHandler('Questa risorsa verrà eliminata definitivamente');
            autonumericImporto('importo');
            moment.locale('it');

            $('#data_installazione').flatpickr({
                allowInput: true,
                locale: 'it',
                dateFormat: 'd/m/Y'
            });

            // Inizializza select2 per impianto
            select2Universale('impianto_id', 'un Impianto', 1, 'impianto_id');

            // Inizializza select2 per unità immobiliarie (inizialmente senza filtro)
            initSelect2UnitaImmobiliare();

            select2Universale('concentratore_id', 'un Concentratore', 1, 'concentratore_id');

            $('#data_ultima_lettura').flatpickr({
                allowInput: true,
                locale: 'it',
                dateFormat: 'd/m/Y'
            });

            // Event listener per quando cambia l'impianto
            $('#impianto_id').on('change', function() {
                // Reset del select delle unità immobiliari
                $('#unita_immobiliare_id').val(null).trigger('change');
                // Reinizializza il select2 con il nuovo filtro
                initSelect2UnitaImmobiliare();
            });

            function initSelect2UnitaImmobiliare() {
                // Distruggi l'istanza esistente se presente
                if ($('#unita_immobiliare_id').hasClass("select2-hidden-accessible")) {
                    $('#unita_immobiliare_id').select2('destroy');
                }

                select2UniversaleUnita('unita_immobiliare_id', 'un UnitaImmobiliare', 1, 'unita_immobiliare_id');
            }

            function select2UniversaleUnita(idSenzaCancelletto, testo, minimumInputLength, select2) {
                var url = urlSelect2;
                if (minimumInputLength === undefined) {
                    minimumInputLength = 2;
                }
                if (select2 === undefined) {
                    select2 = idSenzaCancelletto;
                }
                $obj = $('#' + idSenzaCancelletto);
                if ($obj.data('url')) {
                    url = $obj.data('url');
                }
                return $obj.select2({
                    placeholder: 'Seleziona ' + testo,
                    minimumInputLength: minimumInputLength,
                    allowClear: true,
                    width: '100%',
                    dropdownParent: dentroLaModal($obj),
                    ajax: {
                        quietMillis: 150,
                        url: url + "?" + select2,
                        dataType: 'json',
                        data: function (term, page) {
                            var impiantoId = $('#impianto_id').val();
                            return {
                                term: term.term,
                                impianto_id: impiantoId // Passa l'impianto_id come parametro
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        }
                    }
                });
            }
        });
    </script>
@endpush
