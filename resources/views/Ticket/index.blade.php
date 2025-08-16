@extends('Metronic._layout._main')
@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-3 mb-6">

        @includeWhen(isset($testoCerca),'Metronic._components.ricerca')
        <!-- Pulsante Filtri -->

        <button class="btn btn-sm btn-flex @if($conFiltro) btn-success @else btn-secondary @endif"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#filtri-card"
                aria-expanded="false"
                aria-controls="filtri-card"
                id="toggle-filtri">
            <i class="bi bi-funnel fs-3"></i>
            <span class="d-none d-md-block">Filtri</span>
            @if($conFiltro)
                <span class="badge badge-light badge-sm ms-2">Attivi</span>
            @endif
        </button>
        @includeWhen(isset($ordinamenti),'Metronic._components.ordinamento')
        @isset($testoNuovo)
            <a class="btn btn-sm btn-primary fw-bold" data-targetZ="kt_modal" data-toggleZ="modal-ajax" href="{{action([$controller,'create'])}}"><span
                    class="d-md-none">+</span><span class="d-none d-md-block">{{$testoNuovo}}</span></a>
        @endisset
    </div>
@endsection

@section('content')
    <div class="row mb-4">
        <!-- Statistiche -->
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="fs-3 fw-bolder text-primary">{{ $statistiche['totale'] ?? 0 }}</h3>
                            <p class="mb-0 fs-2 fw-bolder">Totale Tickets</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="fs-3 fw-bolder text-warning">{{ $statistiche['aperti'] ?? 0 }}</h3>
                            <p class="mb-0 fs-2 fw-bolder">Aperti</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="fs-3 fw-bolder text-info">{{ $statistiche['in_lavorazione'] ?? 0 }}</h3>
                            <p class="mb-0 fs-2 fw-bolder">In Lavorazione</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="fs-3 fw-bolder text-danger">{{ $statistiche['urgenti'] ?? 0 }}</h3>
                            <p class="mb-0 fs-2 fw-bolder">Urgenti</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Ticket.indexFiltri')
    <!-- Tabella -->
    <div class="card pt-4">
        <div class="card-body pt-0 pb-5 fs-6 px-2 px-md-6" id="tabella">
            @include('Ticket.tabella')
        </div>
    </div>
@endsection
@push('customScript')
    <script src="/assets_backend/js-miei/select2_it.js"></script>
    <script src="/assets_backend/js-miei/flatPicker_it.js"></script>

    <script>
        var indexUrl = '{{action([$controller,'index'])}}';
        $(function () {
            searchHandler();

            select2Universale('assegnato_a_id', 'un responsabile', -1);
            select2Universale('impianto_id', 'un impianto', -1);
            $('#data_da').flatpickr({
                allowInput: true,
                locale: 'it',
                dateFormat: 'd/m/Y'
            });
            $('#data_a').flatpickr({
                allowInput: true,
                locale: 'it',
                dateFormat: 'd/m/Y'
            });

            // Gestione "Prendi in carico"
            $('.prendiInCarico').click(function() {
                let ticketId = $(this).data('ticket-id');
                let button = $(this);

                Swal.fire({
                    title: 'Conferma',
                    text: 'Vuoi prendere in carico questo ticket?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sì, prendi in carico',
                    cancelButtonText: 'Annulla'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('tickets') }}/${ticketId}/prendi-in-carico`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Successo!',
                                        text: response.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Errore!',
                                    text: 'Si è verificato un errore durante l\'operazione.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Gestione eliminazione
            $('.elimina').click(function() {
                let url = $(this).data('elimina-url');

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
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Eliminato!',
                                        text: 'Il ticket è stato eliminato con successo.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        if (response.redirect) {
                                            window.location.href = response.redirect;
                                        } else {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Errore!',
                                        text: response.message || 'Impossibile eliminare il ticket.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Errore!',
                                    text: 'Si è verificato un errore durante l\'eliminazione.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Inizializza tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endpush
