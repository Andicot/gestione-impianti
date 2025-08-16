@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-2">

        {{-- Pulsanti di azione in base allo stato --}}
        @if($record->stato === \App\Enums\StatoTicketEnum::aperto->value && !$record->assegnato_a_id)
            <a href="{{action([\App\Http\Controllers\TicketController::class,'azioni'],[$record->id,'prendi-in-carico'])}}"
               class="btn btn-sm btn-success azione">
                <i class="fas fa-hand-holding"></i> Prendi in carico
            </a>
        @endif

        @if(in_array($record->stato, [\App\Enums\StatoTicketEnum::aperto->value, \App\Enums\StatoTicketEnum::in_lavorazione->value]))
            <a href="{{ action([\App\Http\Controllers\TicketController::class,'edit'], $record->id) }}"
               class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> Modifica
            </a>
        @endif

        @if($record->stato === \App\Enums\StatoTicketEnum::in_lavorazione->value && $record->assegnato_a_id === auth()->id())
            <a href="{{action([\App\Http\Controllers\TicketController::class,'azioni'],[$record->id,'risolvi'])}}"
               class="btn btn-sm btn-primary azione risolviTicket" data-ticket-id="{{ $record->id }}">
                <i class="fas fa-check-circle"></i> Segna come risolto
            </a>
        @endif

        @if($record->stato === \App\Enums\StatoTicketEnum::risolto->value)
            <a href="{{action([\App\Http\Controllers\TicketController::class,'azioni'],[$record->id,'chiudi'])}}" class="btn btn-sm btn-success azione">
                <i class="fas fa-lock"></i> Chiudi definitivamente
            </a>
        @endif

        @if($record->stato === \App\Enums\StatoTicketEnum::aperto->value)
            <a href="{{route('tickets.destroy',$record->id)}}" class="btn btn-sm btn-danger " id="elimina"
            >
                <i class="fas fa-trash"></i> Elimina
            </a>
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


            azioneAjax();

            eliminaHandler('Vuoi eliminare questo ticket?');
// Gestione speciale per "Risolvi ticket" con prompt per note
            $('.risolviTicket').click(function (e) {
                e.preventDefault(); // Impedisce l'azione di default di azioneAjax

                let url = $(this).attr('href');

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
                        // Effettua la chiamata AJAX con le note
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                note_risoluzione: result.value
                            },
                            success: function (resp) {
                                if (resp.success) {
                                    eseguiAzioniResp(resp);
                                } else {
                                    Swal.fire("Errore!", resp.message, "error");
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                visualizzaErroreAjax(xhr, ajaxOptions, thrownError);
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush
