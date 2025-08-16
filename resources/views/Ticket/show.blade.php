@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-3 mb-6">

        {{-- Pulsanti di azione in base allo stato --}}
        @if($record->stato === \App\Enums\StatoTicketEnum::aperto && !$record->assegnato_a_id)
            <button type="button" class="btn btn-sm btn-success prendiInCarico" data-ticket-id="{{ $record->id }}">
                <i class="fas fa-hand-holding"></i> Prendi in carico
            </button>
        @endif

        @if(in_array($record->stato, [\App\Enums\StatoTicketEnum::aperto, \App\Enums\StatoTicketEnum::in_lavorazione]))
            <a href="{{ action([\App\Http\Controllers\TicketController::class,'edit'], $record->id) }}"
               class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> Modifica
            </a>
        @endif

        @if($record->stato === \App\Enums\StatoTicketEnum::in_lavorazione && $record->assegnato_a_id === auth()->id())
            <button type="button" class="btn btn-sm btn-primary risolviTicket" data-ticket-id="{{ $record->id }}">
                <i class="fas fa-check-circle"></i> Segna come risolto
            </button>
        @endif

        @if($record->stato === \App\Enums\StatoTicketEnum::risolto)
            <button type="button" class="btn btn-sm btn-success chiudiTicket" data-ticket-id="{{ $record->id }}">
                <i class="fas fa-lock"></i> Chiudi definitivamente
            </button>
        @endif

        @if($record->stato === \App\Enums\StatoTicketEnum::aperto)
            <button type="button" class="btn btn-sm btn-danger elimina"
                    data-elimina-url="{{ action([\App\Http\Controllers\TicketController::class,'destroy'], $record->id) }}">
                <i class="fas fa-trash"></i> Elimina
            </button>
        @endif
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4">
            @include('Ticket.show.sideBar')
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    {{-- Header del ticket --}}
                    <div class="d-flex flex-column mb-8">
                        <div class="d-flex align-items-center mb-2">
                            <h1 class="text-gray-800 fw-bold me-3">Ticket #{{ $record->id }}</h1>
                            {!! $record->badgeStato() !!}
                        </div>
                        <h3 class="text-gray-700 fw-semibold">{{ $record->titolo }}</h3>
                    </div>

                    {{-- Tabs di navigazione --}}
                    @if(isset($tabs) && count($tabs) > 1)
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                            @foreach($tabs as $item)
                                <li class="nav-item">
                                    <a class="nav-link @if($tab==$item) active @endif"
                                       href="{{action([$controller,'tab'],[$record->id,$item])}}">
                                        <span class="nav-text fw-semibold fs-6">
                                            {{\Illuminate\Support\Str::of($item)->remove('tab_')->title()->replace('_',' ')}}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Contenuto delle tabs --}}
                        <div class="tab-content" id="tabContent">
                            <div class="tab-pane fade show active" role="tabpanel">
                                @include('Ticket.show.tab'.Str::of($tab)->remove('tab_')->title()->remove('_'))
                            </div>
                        </div>
                    @else
                        {{-- Vista singola senza tabs --}}
                        @include('Ticket.show.dettaglio')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script>
        $(function () {
            // Gestione "Prendi in carico"
            $('.prendiInCarico').click(function() {
                let ticketId = $(this).data('ticket-id');

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

            // Gestione "Risolvi ticket"
            $('.risolviTicket').click(function() {
                let ticketId = $(this).data('ticket-id');

                Swal.fire({
                    title: 'Risolvi Ticket',
                    text: 'Aggiungi note di risoluzione:',
                    input: 'textarea',
                    inputPlaceholder: 'Descrivi come è stato risolto il problema...',
                    showCancelButton: true,
                    confirmButtonText: 'Segna come risolto',
                    cancelButtonText: 'Annulla',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Le note di risoluzione sono obbligatorie!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('tickets') }}/${ticketId}/risolvi`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                note_risoluzione: result.value
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Risolto!',
                                        text: 'Il ticket è stato marcato come risolto.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                            }
                        });
                    }
                });
            });

            // Gestione "Chiudi ticket"
            $('.chiudiTicket').click(function() {
                let ticketId = $(this).data('ticket-id');

                Swal.fire({
                    title: 'Chiudi Ticket',
                    text: 'Sei sicuro di voler chiudere definitivamente questo ticket?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sì, chiudi',
                    cancelButtonText: 'Annulla'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('tickets') }}/${ticketId}/chiudi`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Chiuso!',
                                        text: 'Il ticket è stato chiuso definitivamente.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
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
                    text: 'Sei sicuro di voler eliminare questo ticket?',
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
                                            window.location.href = '{{ action([\App\Http\Controllers\TicketController::class, "index"]) }}';
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
