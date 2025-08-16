@extends('Metronic._layout._main')

@section('content')

    <div class="card">
        <div class="card-body">
            {{-- Statistiche tickets --}}
            <div class="d-flex justify-content-between gap-5 mb-6">
                {{-- Contatore Totale Tickets --}}
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40px symbol-circle me-3">
                        <div class="symbol-label bg-light-primary">
                            <i class="fas fa-ticket-alt text-primary fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fs-7 text-muted fw-bold">Totale Tickets</div>
                        <div class="fs-4 fw-bold text-gray-800">{{ $statistiche['totale'] }}</div>
                    </div>
                </div>

                {{-- Contatore Aperti --}}
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40px symbol-circle me-3">
                        <div class="symbol-label bg-light-warning">
                            <i class="fas fa-exclamation-circle text-warning fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fs-7 text-muted fw-bold">Aperti</div>
                        <div class="fs-4 fw-bold text-warning">{{ $statistiche['aperti'] }}</div>
                    </div>
                </div>

                {{-- Contatore In Lavorazione --}}
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40px symbol-circle me-3">
                        <div class="symbol-label bg-light-info">
                            <i class="fas fa-cog text-info fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fs-7 text-muted fw-bold">In Lavorazione</div>
                        <div class="fs-4 fw-bold text-info">{{ $statistiche['in_lavorazione'] }}</div>
                    </div>
                </div>

                {{-- Contatore Urgenti --}}
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40px symbol-circle me-3">
                        <div class="symbol-label bg-light-danger">
                            <i class="fas fa-fire text-danger fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fs-7 text-muted fw-bold">Urgenti</div>
                        <div class="fs-4 fw-bold text-danger">{{ $statistiche['urgenti'] }}</div>
                    </div>
                </div>
            </div>

            {{-- Header tabella con filtri --}}
            <div class="border-0 pt-6">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        @includeWhen(isset($testoCerca),'Metronic._components.ricerca')

                        <!-- Pulsante Filtri -->
                        <button class="btn btn-sm btn-flex btn-secondary"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#filtri-tickets-card"
                                aria-expanded="false"
                                aria-controls="filtri-tickets-card"
                                id="toggle-filtri-tickets">
                            <i class="bi bi-funnel fs-3"></i>
                            <span class="d-none d-md-block">Filtri</span>
                        </button>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        {{-- Pulsante Nuovo Ticket --}}
                        <a href="{{ action([\App\Http\Controllers\TicketController::class,'create']) }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-plus fs-4"></i>
                            Nuovo Ticket
                        </a>
                    </div>
                </div>

                <!-- Filtri Collassabili -->
                <div class="collapse" id="filtri-tickets-card">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Filtri di Ricerca</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <!-- Campo di ricerca generale -->
                                <div class="col-md-4">
                                    <label class="form-label">Cerca</label>
                                    <input type="text" name="cerca" class="form-control form-control-solid form-control-sm"
                                           placeholder="Titolo, descrizione..."
                                           value="{{ request('cerca') }}">
                                </div>

                                <!-- Filtro Stato -->
                                <div class="col-md-2">
                                    <label class="form-label">Stato</label>
                                    <select name="stato" class="form-select form-select-sm form-select-solid">
                                        <option value="">Tutti gli stati</option>
                                        <option value="aperto" {{ request('stato') == 'aperto' ? 'selected' : '' }}>Aperto</option>
                                        <option value="in_lavorazione" {{ request('stato') == 'in_lavorazione' ? 'selected' : '' }}>In Lavorazione</option>
                                        <option value="risolto" {{ request('stato') == 'risolto' ? 'selected' : '' }}>Risolto</option>
                                        <option value="chiuso" {{ request('stato') == 'chiuso' ? 'selected' : '' }}>Chiuso</option>
                                    </select>
                                </div>

                                <!-- Filtro Priorità -->
                                <div class="col-md-2">
                                    <label class="form-label">Priorità</label>
                                    <select name="priorita" class="form-select form-select-sm form-select-solid">
                                        <option value="">Tutte le priorità</option>
                                        <option value="bassa" {{ request('priorita') == 'bassa' ? 'selected' : '' }}>Bassa</option>
                                        <option value="media" {{ request('priorita') == 'media' ? 'selected' : '' }}>Media</option>
                                        <option value="alta" {{ request('priorita') == 'alta' ? 'selected' : '' }}>Alta</option>
                                        <option value="urgente" {{ request('priorita') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                                    </select>
                                </div>

                                <!-- Filtro Categoria -->
                                <div class="col-md-3">
                                    <label class="form-label">Categoria</label>
                                    <select name="categoria" class="form-select form-select-sm form-select-solid">
                                        <option value="">Tutte le categorie</option>
                                        <option value="errore_dispositivo" {{ request('categoria') == 'errore_dispositivo' ? 'selected' : '' }}>Errore Dispositivo</option>
                                        <option value="letture_anomale" {{ request('categoria') == 'letture_anomale' ? 'selected' : '' }}>Letture Anomale</option>
                                        <option value="bollette" {{ request('categoria') == 'bollette' ? 'selected' : '' }}>Bollette</option>
                                        <option value="pagamenti" {{ request('categoria') == 'pagamenti' ? 'selected' : '' }}>Pagamenti</option>
                                        <option value="comunicazione_concentratore" {{ request('categoria') == 'comunicazione_concentratore' ? 'selected' : '' }}>Comunicazione Concentratore</option>
                                        <option value="manutenzione" {{ request('categoria') == 'manutenzione' ? 'selected' : '' }}>Manutenzione</option>
                                        <option value="tecnico" {{ request('categoria') == 'tecnico' ? 'selected' : '' }}>Tecnico</option>
                                        <option value="altro" {{ request('categoria') == 'altro' ? 'selected' : '' }}>Altro</option>
                                    </select>
                                </div>

                                <!-- Pulsanti azione filtri -->
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="{{ action([\App\Http\Controllers\TicketController::class,'index']) }}"
                                           class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabella tickets --}}
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_tickets">
                    <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-100px">Ticket</th>
                        <th class="min-w-250px">Titolo</th>
                        <th class="min-w-100px">Stato</th>
                        <th class="min-w-100px">Priorità</th>
                        <th class="min-w-150px">Categoria</th>
                        <th class="min-w-150px">Creato Da</th>
                        <th class="min-w-150px">Assegnato A</th>
                        <th class="min-w-100px">Data Creazione</th>
                        <th class="text-end min-w-100px">Azioni</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                    @forelse($records as $record)
                        <tr>
                            {{-- Numero Ticket --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px symbol-circle me-3">
                                        <div class="symbol-label bg-light-primary">
                                                <span class="fs-6 text-primary fw-bold">
                                                    {{ $record->numeroTicket() }}
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Titolo con info aggiuntive --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold mb-1">{{ Str::limit($record->titolo, 40) }}</span>
                                    @if($record->impianto)
                                        <span class="text-muted fs-7">{{ $record->impianto->nome_impianto }}</span>
                                    @endif
                                    @if($record->unitaImmobiliare)
                                        <span class="text-muted fs-7">
                                                Unità: {{ $record->unitaImmobiliare->scala }} - {{ $record->unitaImmobiliare->interno }}
                                            </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Stato --}}
                            <td>
                                {!! $record->badgeStato() !!}
                            </td>

                            {{-- Priorità --}}
                            <td>
                                {!! $record->badgePriorita() !!}
                            </td>

                            {{-- Categoria --}}
                            <td>
                                {!! $record->badgeCategoria() !!}
                            </td>

                            {{-- Creato Da --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold">{{ $record->creadoDa->nominativo() }}</span>
                                    <span class="text-muted fs-7">{{ $record->testoOrigine() }}</span>
                                </div>
                            </td>

                            {{-- Assegnato A --}}
                            <td>
                                @if($record->assegnatoA)
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold">{{ $record->assegnatoA->nominativo() }}</span>
                                        @if($record->assegnatoA->ruolo)
                                            {!! $record->assegnatoA->badgeRuolo() !!}
                                        @endif
                                    </div>
                                @else
                                    <span class="badge badge-light-warning">Non Assegnato</span>
                                @endif
                            </td>

                            {{-- Data Creazione --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold">{{ $record->created_at->format('d/m/Y') }}</span>
                                    <span class="text-muted fs-7">{{ $record->created_at->format('H:i') }}</span>
                                    <span class="text-muted fs-8">{{ $record->tempoApertura() }}</span>
                                </div>
                            </td>

                            {{-- Azioni --}}
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Visualizza --}}
                                    <a href="{{ action([\App\Http\Controllers\TicketController::class,'show'], $record->id) }}"
                                       class="btn btn-icon btn-light btn-active-light-primary btn-sm"
                                       data-bs-toggle="tooltip" title="Visualizza dettagli">
                                        <i class="fas fa-eye fs-5"></i>
                                    </a>

                                    {{-- Prendi in carico (se può) --}}
                                    @if($record->stato === 'aperto' && !$record->assegnato_a_id)
                                        <button type="button"
                                                class="btn btn-icon btn-light btn-active-light-success btn-sm prendiInCarico"
                                                data-ticket-id="{{ $record->id }}"
                                                data-bs-toggle="tooltip" title="Prendi in carico">
                                            <i class="fas fa-hand-holding fs-5"></i>
                                        </button>
                                    @endif

                                    {{-- Modifica (se può) --}}
                                    @if($record->puo_essere_modificato())
                                        <a href="{{ action([\App\Http\Controllers\TicketController::class,'edit'], $record->id) }}"
                                           class="btn btn-icon btn-light btn-active-light-warning btn-sm"
                                           data-bs-toggle="tooltip" title="Modifica">
                                            <i class="fas fa-edit fs-5"></i>
                                        </a>
                                    @endif

                                    {{-- Elimina (se può) --}}
                                    @if($record->puo_essere_eliminato())
                                        <button type="button"
                                                class="btn btn-icon btn-light btn-active-light-danger btn-sm elimina"
                                                data-elimina-url="{{ action([\App\Http\Controllers\TicketController::class,'destroy'], $record->id) }}"
                                                data-bs-toggle="tooltip" title="Elimina">
                                            <i class="fas fa-trash fs-5"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-ticket-alt fs-2x text-muted mb-3"></i>
                                    <span class="text-muted fs-5">Nessun ticket trovato</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginazione --}}
            @if($records->hasPages())
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center py-3">
                        <span class="text-muted">
                            Mostrando {{ $records->firstItem() }} - {{ $records->lastItem() }} di {{ $records->total() }} risultati
                        </span>
                    </div>
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {

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
@endsection
