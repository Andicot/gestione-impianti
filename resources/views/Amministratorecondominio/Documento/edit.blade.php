@extends('Metronic._layout._main')

@section('toolbar')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{action([$controller, 'store'])}}" method="POST" enctype="multipart/form-data" id="form_documento">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            {{-- Upload File --}}
                            <div class="col-12 mb-6">

                                @include('Metronic._inputs_v.inputFile',['campo' => 'file_documento','required' => true,'help' => '  Formati supportati: PDF (max 10MB)'])
                            </div>

                            {{-- Tipo Documento --}}
                            <div class="col-md-6 mb-4">
                                @include('Metronic._inputs_v.inputSelect2Enum',['campo' => 'tipo_documento','required' => true,'classeEnum' => \App\Enums\TipoDocumentoEnum::class])
                            </div>

                            {{-- Impianto --}}
                            <div class="col-md-6 mb-4">
                                @include('Metronic._inputs_v.inputSelect2',['campo' => 'impianto_id','required' => true,'selected' => \App\Models\Impianto::selected(old('impianto_id',$record->impianto_id)),'label' => 'Impianto'])

                            </div>

                            {{-- Unità Immobiliare --}}
                            <div class="col-md-6 mb-4">
                                @include('Metronic._inputs_v.inputSelect2',['campo' => 'unita_immobiliare_id','required' => false,'selected' => \App\Models\UnitaImmobiliare::selected(old('unita_immobiliare_id',$record->unita_immobiliare_id)),'label' => 'Unità Immobiliare'])
                            </div>

                            {{-- Data Scadenza --}}
                            <div class="col-md-6 mb-4">
                                @include('Metronic._inputs_v.inputTextData',['campo' => 'data_scadenza'])
                            </div>

                            {{-- Descrizione --}}
                            <div class="col-12 mb-4">
                                <label class="form-label">Descrizione</label>
                                <textarea name="descrizione"
                                          class="form-control form-control-solid @error('descrizione') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Descrizione del documento...">{{old('descrizione')}}</textarea>
                                @error('descrizione')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>

                            {{-- Opzioni Visibilità --}}
                            <div class="col-12 mb-4">
                                <label class="form-label">Visibilità</label>
                                <div class="d-flex flex-column gap-3">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="pubblico"
                                               id="pubblico"
                                               value="1"
                                            {{ old('pubblico') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pubblico">
                                            <div class="fw-semibold">Pubblico</div>
                                            <div class="text-muted fs-7">Visibile a tutti i condomini dell'impianto</div>
                                        </label>
                                    </div>

                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="riservato_amministratori"
                                               id="riservato_amministratori"
                                               value="1"
                                            {{ old('riservato_amministratori') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="riservato_amministratori">
                                            <div class="fw-semibold">Riservato Amministratori</div>
                                            <div class="text-muted fs-7">Visibile solo agli amministratori</div>
                                        </label>
                                    </div>

                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="notifica_scadenza"
                                               id="notifica_scadenza"
                                               value="1"
                                            {{ old('notifica_scadenza') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notifica_scadenza">
                                            <div class="fw-semibold">Notifica Scadenza</div>
                                            <div class="text-muted fs-7">Invia notifica prima della scadenza</div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Note --}}
                            <div class="col-12 mb-4">
                                <label class="form-label">Note</label>
                                <textarea name="note"
                                          class="form-control form-control-solid @error('note') is-invalid @enderror"
                                          rows="2"
                                          placeholder="Note aggiuntive...">{{old('note')}}</textarea>
                                @error('note')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{action([$controller, 'index'])}}" class="btn btn-light">
                                <i class="fas fa-times"></i>
                                Annulla
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Salva Documento
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script src="/assets_backend/js-miei/flatPicker_it.js"></script>
    <script src="/assets_backend/js-miei/select2_it.js"></script>

    <script>
        $(function () {
            select2Universale('impianto_id', 'un impianto', -1);
            select2UnitaImmobiliare('unita_immobiliare_id', 'un unità immobiliare', -1);
            $('#data_scadenza').flatpickr({
                allowInput: true,
                locale: 'it',
                dateFormat: 'd/m/Y'
            });
        });

        // Aggiorna unità immobiliari quando cambia l'impianto
        function select2UnitaImmobiliare(idSenzaCancelletto, testo, minimumInputLength, select2) {
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
                    url: function () {
                        return url + "?" + select2 + "&impianto_id=" + $('#impianto_id').val();
                    },
                    dataType: 'json',
                    data: function (term, page) {
                        return {
                            term: term.term
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


    </script>
@endpush
