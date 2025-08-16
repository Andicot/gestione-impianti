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


        {{-- Risposte e comunicazioni --}}
        @if($record->risposte->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Comunicazioni ({{ $record->risposte->count() }})</h3>
                </div>
                <div class="card-body">
                    @foreach($record->risposte as $risposta)
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
        @if(in_array($record->stato, [\App\Enums\StatoTicketEnum::aperto->value, \App\Enums\StatoTicketEnum::in_lavorazione->value, \App\Enums\StatoTicketEnum::risolto->value]))
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
                        @if($record->assegnato_a_id === auth()->id() && $record->stato === \App\Enums\StatoTicketEnum::in_lavorazione->value)
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
