{{-- Dettaglio principale del ticket --}}
<div class="row">
    <div class="col-12">
        {{-- Descrizione del problema --}}
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Descrizione del problema</h3>
            </div>
            <div class="card-body">
                <div class="fs-6 text-gray-700 lh-lg">
                    {!! nl2br(e($record->descrizione)) !!}
                </div>
            </div>
        </div>

        {{-- Timeline del ticket --}}
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Timeline</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    {{-- Creazione ticket --}}
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                            <div class="symbol-label bg-light-primary">
                                <i class="fas fa-plus text-primary fs-6"></i>
                            </div>
                        </div>
                        <div class="timeline-content mb-10 mt-n1">
                            <div class="pe-3 mb-5">
                                <div class="fs-5 fw-semibold text-gray-800 mb-2">Ticket creato</div>
                                <div class="d-flex align-items-center mt-1 fs-6">
                                    <div class="text-muted me-2 fs-7">{{ $record->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-gray-800 fw-bold">{{ $record->creadoDa->nome }} {{ $record->creadoDa->cognome }}</div>
                                </div>
                                @php
                                    $origineEnum = \App\Enums\OrigineTicketEnum::from($record->origine);
                                @endphp
                                <span class="badge badge-light-{{ $origineEnum->colore() }} badge-sm mt-2">{{ $origineEnum->testo() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Assegnazione (se presente) --}}
                    @if($record->assegnatoA)
                        <div class="timeline-item">
                            <div class="timeline-line w-40px"></div>
                            <div class="timeline-icon symbol symbol-circle symbol-40px">
                                <div class="symbol-label bg-light-success">
                                    <i class="fas fa-user-check text-success fs-6"></i>
                                </div>
                            </div>
                            <div class="timeline-content mb-10 mt-n1">
                                <div class="pe-3 mb-5">
                                    <div class="fs-5 fw-semibold text-gray-800 mb-2">Ticket assegnato</div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="text-muted me-2 fs-7">
                                            {{ $record->updated_at->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="text-gray-800 fw-bold">
                                            Assegnato a: {{ $record->assegnatoA->nome }} {{ $record->assegnatoA->cognome }}
                                        </div>
                                    </div>
                                    @if($record->assegnatoA->ruolo)
                                        @php
                                            $ruoloEnum = \App\Enums\RuoliOperatoreEnum::from($record->assegnatoA->ruolo);
                                        @endphp
                                        <span class="badge badge-light-{{ $ruoloEnum->colore() }} badge-sm mt-2">{{ $ruoloEnum->testo() }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Cambio stato in lavorazione --}}
                    @if($record->stato !== \App\Enums\StatoTicketEnum::aperto)
                        <div class="timeline-item">
                            <div class="timeline-line w-40px"></div>
                            <div class="timeline-icon symbol symbol-circle symbol-40px">
                                <div class="symbol-label bg-light-info">
                                    <i class="fas fa-cog text-info fs-6"></i>
                                </div>
                            </div>
                            <div class="timeline-content mb-10 mt-n1">
                                <div class="pe-3 mb-5">
                                    <div class="fs-5 fw-semibold text-gray-800 mb-2">Stato cambiato</div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="text-muted me-2 fs-7">{{ $record->updated_at->format('d/m/Y H:i') }}</div>
                                        <div class="text-gray-800">
                                            Stato:
                                            @php
                                                $statoEnum = \App\Enums\StatoTicketEnum::from($record->stato);
                                            @endphp
                                            <span class="badge badge-light-{{ $statoEnum->colore() }}">{{ $statoEnum->testo() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Chiusura (se presente) --}}
                    @if($record->data_chiusura)
                        <div class="timeline-item">
                            <div class="timeline-line w-40px"></div>
                            <div class="timeline-icon symbol symbol-circle symbol-40px">
                                <div class="symbol-label bg-light-success">
                                    <i class="fas fa-check-circle text-success fs-6"></i>
                                </div>
                            </div>
                            <div class="timeline-content mb-10 mt-n1">
                                <div class="pe-3 mb-5">
                                    <div class="fs-5 fw-semibold text-gray-800 mb-2">Ticket chiuso</div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="text-muted me-2 fs-7">{{ $record->data_chiusura->format('d/m/Y H:i') }}</div>
                                        @if($record->chiusoDa)
                                            <div class="text-gray-800 fw-bold">{{ $record->chiusoDa->nome }} {{ $record->chiusoDa->cognome }}</div>
                                        @endif
                                    </div>
                                    @if($record->note_chiusura)
                                        <div class="mt-3 p-3 bg-light-success rounded">
                                            <div class="fs-7 fw-bold text-success mb-1">Note di chiusura:</div>
                                            <div class="fs-6 text-gray-700">{!! nl2br(e($record->note_chiusura)) !!}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Informazioni tecniche (se presenti) --}}
        @if($record->dispositivi_coinvolti || $record->anomalia || $record->dispositivo)
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Informazioni tecniche</h3>
                </div>
                <div class="card-body">
                    {{-- Dispositivo principale --}}
                    @if($record->dispositivo)
                        <div class="row mb-4">
                            <div class="col-3">
                                <span class="fw-bold text-gray-600">Dispositivo:</span>
                            </div>
                            <div class="col-9">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-microchip text-info me-2"></i>
                                    <span class="fw-bold">{{ $record->dispositivo->matricola }}</span>
                                    @if($record->dispositivo->tipo)
                                        <span class="badge badge-light-info ms-2">{{ $record->dispositivo->tipo }}</span>
                                    @endif
                                </div>
                                @if($record->dispositivo->ubicazione)
                                    <div class="text-muted fs-7 mt-1">Ubicazione: {{ $record->dispositivo->ubicazione }}</div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Dispositivi coinvolti multipli --}}
                    @if($record->dispositivi_coinvolti)
                        <div class="row mb-4">
                            <div class="col-3">
                                <span class="fw-bold text-gray-600">Dispositivi coinvolti:</span>
                            </div>
                            <div class="col-9">
                                @php
                                    $dispositivi = json_decode($record->dispositivi_coinvolti, true);
                                @endphp
                                @if(is_array($dispositivi))
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($dispositivi as $dispositivoId)
                                            <span class="badge badge-light-secondary">#{{ $dispositivoId }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Anomalia collegata --}}
                    @if($record->anomalia)
                        <div class="row mb-4">
                            <div class="col-3">
                                <span class="fw-bold text-gray-600">Anomalia:</span>
                            </div>
                            <div class="col-9">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    <span class="fw-bold">#{{ $record->anomalia->id }}</span>
                                    @if($record->anomalia->tipo_anomalia)
                                        @php
                                            $tipoAnomaliaEnum = \App\Enums\TipoAnomaliaEnum::from($record->anomalia->tipo_anomalia);
                                        @endphp
                                        <span class="badge badge-light-{{ $tipoAnomaliaEnum->colore() }} ms-2">{{ $tipoAnomaliaEnum->testo() }}</span>
                                    @endif
                                </div>
                                @if($record->anomalia->severita)
                                    @php
                                        $severitaEnum = \App\Enums\SeveritaAnomaliaEnum::from($record->anomalia->severita);
                                    @endphp
                                    <div class="mb-2">
                                        <span class="text-muted">Severità: </span>
                                        <span class="badge badge-light-{{ $severitaEnum->colore() }}">{{ $severitaEnum->testo() }}</span>
                                    </div>
                                @endif
                                @if($record->anomalia->descrizione)
                                    <div class="text-muted fs-7">{{ Str::limit($record->anomalia->descrizione, 100) }}</div>
                                @endif
                                <a href="#" class="btn btn-sm btn-light-info mt-2">
                                    <i class="fas fa-external-link-alt"></i> Visualizza anomalia
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Risposte e comunicazioni --}}
        @if(isset($risposte) && $risposte->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Comunicazioni ({{ $risposte->count() }})</h3>
                </div>
                <div class="card-body">
                    @foreach($risposte as $risposta)
                        <div class="d-flex mb-6">
                            {{-- Avatar --}}
                            <div class="symbol symbol-45px symbol-circle me-4">
                                <div class="symbol-label bg-light-primary">
                                    <span class="fs-6 text-primary fw-bold">
                                        {{ strtoupper(substr($risposta->autore->nome, 0, 1)) }}{{ strtoupper(substr($risposta->autore->cognome, 0, 1)) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Contenuto risposta --}}
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-bold text-gray-800 me-2">{{ $risposta->autore->nome }} {{ $risposta->autore->cognome }}</span>
                                    @if($risposta->autore->ruolo)
                                        @php
                                            $ruoloEnum = \App\Enums\RuoliOperatoreEnum::from($risposta->autore->ruolo);
                                        @endphp
                                        <span class="badge badge-light-{{ $ruoloEnum->colore() }} badge-sm me-2">{{ $ruoloEnum->testo() }}</span>
                                    @endif
                                    <span class="text-muted fs-7">{{ $risposta->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="bg-light-gray-200 p-4 rounded">
                                    <div class="fs-6 text-gray-700 lh-lg">
                                        {!! nl2br(e($risposta->messaggio)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Form per nuova risposta (se il ticket può ricevere risposte) --}}
        @if(in_array($record->stato, [\App\Enums\StatoTicketEnum::aperto, \App\Enums\StatoTicketEnum::in_lavorazione, \App\Enums\StatoTicketEnum::risolto]))
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="card-title">Aggiungi risposta</h3>
                </div>
                <div class="card-body">
                    <form id="form-risposta" method="POST" action="{{ action([\App\Http\Controllers\TicketController::class, 'aggiungiRisposta'], $record->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">Messaggio</label>
                            <textarea name="messaggio" class="form-control form-control-solid" rows="4"
                                      placeholder="Scrivi la tua risposta..." required></textarea>
                        </div>

                        {{-- Azioni aggiuntive --}}
                        @if($record->assegnato_a_id === auth()->id() && $record->stato === \App\Enums\StatoTicketEnum::in_lavorazione)
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="segna_risolto" id="segna_risolto">
                                    <label class="form-check-label" for="segna_risolto">
                                        Segna questo ticket come risolto
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Invia risposta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
