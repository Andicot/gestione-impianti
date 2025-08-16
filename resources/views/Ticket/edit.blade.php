@extends('Metronic._layout._main')

@section('content')
    @php($vecchio = $record->id ?? false)

    <div class="card">
        <div class="card-body">
            @include('Metronic._components.alertErrori')

            <form method="POST" action="{{ \App\storeUpdateHelper($controller, $record?->id) }}" onsubmit="return disableSubmitButton()">
                @csrf
                @method($record->id ? 'PATCH' : 'POST')

                <div class="row">
                    {{-- Titolo --}}
                    <div class="col-md-8">
                        @include('Metronic._inputs_v.inputText', [
                            'campo' => 'titolo',
                            'required' => true,
                            'label' => 'Titolo del ticket'
                        ])
                    </div>

                    {{-- Priorità --}}
                    <div class="col-md-4">
                        @include('Metronic._inputs_v.inputSelect2Enum', [
                            'campo' => 'priorita',
                            'required' => true,
                            'classeEnum' => \App\Enums\PrioritaTicketEnum::class,
                            'selected' => old('priorita', $record->priorita ?? 'media')
                        ])
                    </div>

                    {{-- Categoria --}}
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2Enum', [
                            'campo' => 'categoria',
                            'required' => true,
                            'classeEnum' => \App\Enums\CategoriaTicketEnum::class,
                            'selected' => old('categoria', $record->categoria ?? 'errore_dispositivo')
                        ])
                    </div>

                    {{-- Impianto --}}
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2', [
                            'campo' => 'impianto_id',
                            'elementi' => $impianti->pluck('nome_impianto', 'id')->toArray(),
                            'selected' => old('impianto_id', $record->impianto_id ?? ''),
                            'label' => 'Impianto (opzionale)'
                        ])
                    </div>

                    {{-- Unità Immobiliare --}}
                    <div class="col-md-6">
                        @include('Metronic._inputs_v.inputSelect2', [
                            'campo' => 'unita_immobiliare_id',
                            'elementi' => $unitaImmobiliari->mapWithKeys(function($unita) {
                                return [$unita->id => $unita->nominativo_unita . ' - Scala ' . $unita->scala . ' Int. ' . $unita->interno];
                            })->toArray(),
                            'selected' => old('unita_immobiliare_id', $record->unita_immobiliare_id ?? ''),
                            'label' => 'Unità Immobiliare (opzionale)',
                            'disabled' => true
                        ])
                    </div>

                    {{-- Dispositivo (solo per categorie tecniche) --}}
                    <div class="col-md-6" id="sezione-dispositivo" style="display: none;">
                        @include('Metronic._inputs_v.inputSelect2', [
                            'campo' => 'dispositivo_id',
                            'elementi' => $dispositivi->mapWithKeys(function($dispositivo) {
                                return [$dispositivo->id => $dispositivo->matricola . ' - ' . ucfirst($dispositivo->tipo)];
                            })->toArray(),
                            'selected' => old('dispositivo_id', $record->dispositivo_id ?? ''),
                            'label' => 'Dispositivo (opzionale)',
                            'disabled' => true
                        ])
                    </div>

                    {{-- Descrizione --}}
                    <div class="col-md-12">
                        @include('Metronic._inputs_v.inputTextarea', [
                            'campo' => 'descrizione',
                            'required' => true,
                            'rows' => 5,
                            'label' => 'Descrizione del problema'
                        ])
                    </div>
                </div>

                {{-- Sezione Anomalia (solo se edit e presente) --}}
                @if($vecchio && $record->anomalia_id)
                    <div class="mt-6">
                        <div class="separator separator-content mb-6 flex-grow-1 me-3">
                            <h4 class="w-300px">Anomalia Collegata</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6 class="mb-2">Anomalia #{{ $record->anomalia_id }}</h6>
                                    @if($record->anomalia)
                                        <p class="mb-1"><strong>Tipo:</strong> {{ ucfirst(str_replace('_', ' ', $record->anomalia->tipo_anomalia)) }}</p>
                                        <p class="mb-1"><strong>Severità:</strong> {{ ucfirst($record->anomalia->severita) }}</p>
                                        <p class="mb-0"><strong>Descrizione:</strong> {{ $record->anomalia->descrizione }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Pulsanti azione --}}
                @if($vecchio)
                    {{-- Alert per modifica --}}
                    <div class="alert alert-primary d-flex align-items-center p-5 mt-10">
                        <span class="svg-icon svg-icon-2hx svg-icon-primary me-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3"
                                      d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.9829 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z"
                                      fill="#000000"/>
                                <path
                                    d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z"
                                    fill="#000000"/>
                            </svg>
                        </span>
                        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                            <div class="mb-3 mb-md-0 fw-bold">
                                <h4 class="text-gray-900 fw-bolder">Modifica Ticket</h4>
                                <div class="fs-6 text-gray-700 pe-7">
                                    Stai modificando il ticket {{ $record->numeroTicket() }}.
                                    Le modifiche saranno visibili a tutti gli utenti coinvolti.
                                </div>
                            </div>
                            <button class="btn btn-primary mt-3" type="submit">
                                Salva Modifiche
                            </button>
                        </div>
                    </div>
                @else
                    {{-- Alert per creazione --}}
                    <div class="alert alert-success d-flex align-items-center p-5 mt-10">
                        <span class="svg-icon svg-icon-2hx svg-icon-success me-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3"
                                      d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.9829 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z"
                                      fill="#000000"/>
                                <path
                                    d="M14.8563 9.1903C15.0606 8.94984 15.0606 8.65016 14.8563 8.4097C14.6521 8.16924 14.3871 8.16924 14.1828 8.4097L12.4828 10.297L11.4828 9.1903C11.2785 8.94984 11.0135 8.94984 10.8092 9.1903C10.6049 9.43076 10.6049 9.73044 10.8092 9.9709L12.1425 11.4709C12.3468 11.7114 12.6118 11.7114 12.8161 11.4709L14.8563 9.1903Z"
                                    fill="#000000"/>
                            </svg>
                        </span>
                        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                            <div class="mb-3 mb-md-0 fw-bold">
                                <h4 class="text-gray-900 fw-bolder">Nuovo Ticket</h4>
                                <div class="fs-6 text-gray-700 pe-7">
                                    Compila i campi per creare un nuovo ticket.
                                    Il ticket verrà automaticamente assegnato in base alla categoria selezionata.
                                </div>
                            </div>
                            <button class="btn btn-primary mt-3" type="submit">
                                Crea Ticket
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Pulsante Elimina (solo se può essere eliminato) --}}
                @if($vecchio && $record->puo_essere_eliminato())
                    <div class="mt-6">
                        <div class="card border-danger">
                            <div class="card-body">
                                <h5 class="card-title text-danger">Zona Pericolosa</h5>
                                <p class="card-text">
                                    Eliminando questo ticket perderai tutte le informazioni associate.
                                    Questa azione non può essere annullata.
                                </p>
                                <button type="button"
                                        class="btn btn-danger"
                                        onclick="confermaEliminazione('{{ action([\App\Http\Controllers\TicketController::class,'destroy'], $record->id) }}')">
                                    <i class="fas fa-trash me-2"></i>
                                    Elimina Ticket
                                </button>
                            </div>
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

        $(function () {
            select2Universale('impianto_id', 'un impianto', -1);
            select2UnitaImmobiliare('unita_immobiliare_id', 'una unità immobiliare', -1);
            select2Dispositivo('dispositivo_id', 'un dispositivo', -1);

            // Controlla categoria iniziale al caricamento della pagina
            toggleSezioneDispositivo($('#categoria').val());

            // Controlla impianto iniziale per abilitare/disabilitare campi dipendenti
            toggleCampiDipendentiDaImpianto($('#impianto_id').val());
        });

        $(document).ready(function () {

            // Gestione categoria per mostrare/nascondere sezione dispositivo
            $('#categoria').change(function () {
                toggleSezioneDispositivo($(this).val());
            });

            // Gestione impianto per abilitare/disabilitare campi dipendenti
            $('#impianto_id').change(function () {
                toggleCampiDipendentiDaImpianto($(this).val());
            });
        });

        // Funzione per mostrare/nascondere sezione dispositivo
        function toggleSezioneDispositivo(categoria) {
            const categorieTecniche = ['errore_dispositivo', 'comunicazione_concentratore', 'manutenzione', 'tecnico', 'letture_anomale'];
            if (categorieTecniche.includes(categoria)) {
                $('#sezione-dispositivo').show();
            } else {
                $('#sezione-dispositivo').hide();
                // Reset del valore quando si nasconde
                $('#dispositivo_id').val('').trigger('change');
            }
        }

        // Funzione per abilitare/disabilitare campi dipendenti dall'impianto
        function toggleCampiDipendentiDaImpianto(impiantoId) {
            if (impiantoId && impiantoId !== '') {
                // Abilita unità immobiliare
                $('#unita_immobiliare_id').prop('disabled', false);

                // Abilita dispositivo se la sezione è visibile
                $('#dispositivo_id').prop('disabled', false);

            } else {
                // Disabilita e resetta unità immobiliare
                $('#unita_immobiliare_id').prop('disabled', true).val('').trigger('change');

                // Disabilita e resetta dispositivo
                $('#dispositivo_id').prop('disabled', true).val('').trigger('change');
            }
        }

        // Funzione per conferma eliminazione
        function confermaEliminazione(url) {
            Swal.fire({
                title: 'Conferma eliminazione',
                text: 'Sei sicuro di voler eliminare questo ticket? L\'operazione non può essere annullata.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sì, elimina',
                cancelButtonText: 'Annulla',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crea form per eliminazione
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

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

        function select2Dispositivo(idSenzaCancelletto, testo, minimumInputLength, select2) {
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
                        return url + "?" + select2 + "&unita_immobiliare_id=" + $('#unita_immobiliare_id').val();
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
