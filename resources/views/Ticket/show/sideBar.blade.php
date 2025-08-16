{{-- Sidebar unificata con informazioni del ticket --}}
<div class="card">
    <div class="card-body">
        {{-- Header con numero ticket --}}
        <div class="text-center mb-6">
            <h2 class="fs-4 text-gray-800 fw-bold mb-1">Ticket #{{ $record->id }}</h2>
            <div class="fs-6 text-muted">{{ $record->created_at->format('d/m/Y H:i') }}</div>
        </div>

        {{-- Stato e Priorità --}}
        <div class="row mb-6">
            <div class="col-6">
                <div class="text-center">
                    <div class="fs-7 text-gray-600 fw-bold mb-2">Stato</div>
                    @php
                        $statoEnum = \App\Enums\StatoTicketEnum::from($record->stato);
                    @endphp
                    <span class="badge badge-light-{{ $statoEnum->colore() }} fs-6">{{ $statoEnum->testo() }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center">
                    <div class="fs-7 text-gray-600 fw-bold mb-2">Priorità</div>
                    @php
                        $prioritaEnum = \App\Enums\PrioritaTicketEnum::from($record->priorita);
                    @endphp
                    <span class="badge badge-light-{{ $prioritaEnum->colore() }} fs-6">{{ $prioritaEnum->testo() }}</span>
                </div>
            </div>
        </div>

        {{-- Separatore --}}
        <div class="separator mb-6"></div>

        {{-- Informazioni principali --}}
        <div class="mb-6">
            {{-- Categoria --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <span class="fs-6 fw-bold text-gray-600">Categoria:</span>
                @php
                    $categoriaEnum = \App\Enums\CategoriaTicketEnum::from($record->categoria);
                @endphp
                <span class="badge badge-light-{{ $categoriaEnum->colore() }}">{{ $categoriaEnum->testo() }}</span>
            </div>

            {{-- Origine --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <span class="fs-6 fw-bold text-gray-600">Origine:</span>
                @php
                    $origineEnum = \App\Enums\OrigineTicketEnum::from($record->origine);
                @endphp
                <span class="badge badge-light-{{ $origineEnum->colore() }}">{{ $origineEnum->testo() }}</span>
            </div>

            {{-- Tempo apertura --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <span class="fs-6 fw-bold text-gray-600">Aperto da:</span>
                <span class="fs-6 text-gray-600">{{ $record->created_at->diffForHumans() }}</span>
            </div>
        </div>

        {{-- Separatore --}}
        <div class="separator mb-6"></div>

        {{-- Persone coinvolte --}}
        <div class="mb-6">
            <h4 class="fs-5 fw-bold text-gray-800 mb-4">Persone coinvolte</h4>

            {{-- Creato da --}}
            <div class="mb-4">
                <div class="fs-7 text-gray-600 fw-bold mb-1">Creatore</div>
                <div class="fs-6 fw-bold text-gray-800">{{ $record->creadoDa->nome }} {{ $record->creadoDa->cognome }}</div>
            </div>

            {{-- Assegnato a --}}
            <div class="mb-4">
                <div class="fs-7 text-gray-600 fw-bold mb-1">Assegnato</div>
                @if($record->assegnatoA)
                    <div class="fs-6 fw-bold text-gray-800 mb-1">{{ $record->assegnatoA->nome }} {{ $record->assegnatoA->cognome }}</div>
                    @if($record->assegnatoA->ruolo)
                        @php
                            $ruoloEnum = \App\Enums\RuoliOperatoreEnum::from($record->assegnatoA->ruolo);
                        @endphp
                        <span class="badge badge-light-{{ $ruoloEnum->colore() }} badge-sm">{{ $ruoloEnum->testo() }}</span>
                    @endif
                @else
                    <span class="badge badge-light-warning">Non Assegnato</span>
                @endif
            </div>

            {{-- Chiuso da (se applicabile) --}}
            @if($record->chiusoDa)
                <div class="mb-4">
                    <div class="fs-7 text-gray-600 fw-bold mb-1">Chiuso da</div>
                    <div class="fs-6 fw-bold text-gray-800">{{ $record->chiusoDa->nome }} {{ $record->chiusoDa->cognome }}</div>
                    @if($record->data_chiusura)
                        <div class="fs-7 text-gray-600">{{ $record->data_chiusura->format('d/m/Y H:i') }}</div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Separatore --}}
        <div class="separator mb-6"></div>

        {{-- Localizzazione --}}
        <div class="mb-6">
            <h4 class="fs-5 fw-bold text-gray-800 mb-4">Localizzazione</h4>

            {{-- Impianto --}}
            @if($record->impianto)
                <div class="mb-3">
                    <div class="fs-7 text-gray-600 fw-bold mb-1">Impianto</div>
                    <div class="fs-6 fw-bold text-gray-800">{{ $record->impianto->nome_impianto }}</div>
                    <div class="fs-7 text-gray-600">{{ $record->impianto->indirizzo }}</div>
                </div>
            @endif

            {{-- Unità Immobiliare --}}
            @if($record->unitaImmobiliare)
                <div class="mb-3">
                    <div class="fs-7 text-gray-600 fw-bold mb-1">Unità Immobiliare</div>
                    <div class="fs-6 fw-bold text-gray-800">
                        {{ $record->unitaImmobiliare->scala ?? 'N/A' }} - {{ $record->unitaImmobiliare->interno ?? 'N/A' }}
                    </div>
                    @if($record->unitaImmobiliare->piano)
                        <div class="fs-7 text-gray-600">Piano: {{ $record->unitaImmobiliare->piano }}</div>
                    @endif
                </div>
            @endif

            {{-- Dispositivo --}}
            @if($record->dispositivo)
                <div class="mb-3">
                    <div class="fs-7 text-gray-600 fw-bold mb-1">Dispositivo</div>
                    <div class="fs-6 fw-bold text-gray-800">{{ $record->dispositivo->matricola }}</div>
                    <div class="fs-7 text-gray-600">{{ $record->dispositivo->tipo ?? 'Tipo non specificato' }}</div>
                </div>
            @endif

            {{-- Anomalia collegata --}}
            @if($record->anomalia)
                <div class="mb-3">
                    <div class="fs-7 text-gray-600 fw-bold mb-1">Anomalia</div>
                    <div class="fs-6 fw-bold text-gray-800">#{{ $record->anomalia->id }}</div>
                    <div class="fs-7 text-gray-600">{{ $record->anomalia->tipo_anomalia }}</div>
                </div>
            @endif
        </div>

        {{-- Separatore --}}
        <div class="separator mb-6"></div>

        {{-- Timeline del ticket --}}
        <div class="mb-6">
            <h4 class="fs-5 fw-bold text-gray-800 mb-4">Timeline</h4>

            <div class="timeline">
                {{-- Creazione ticket --}}
                <div class="timeline-item">
                    <div class="timeline-line w-30px"></div>
                    <div class="timeline-icon symbol symbol-circle symbol-30px">
                        <div class="symbol-label bg-light-primary">
                            <i class="fas fa-plus text-primary fs-7"></i>
                        </div>
                    </div>
                    <div class="timeline-content mb-6 mt-n1">
                        <div class="pe-3">
                            <div class="fs-6 fw-semibold text-gray-800 mb-1">Ticket creato</div>
                            <div class="fs-7 text-muted">{{ $record->created_at->format('d/m/Y H:i') }}</div>
                            <div class="fs-7 text-gray-700">{{ $record->creadoDa->nome }} {{ $record->creadoDa->cognome }}</div>
                        </div>
                    </div>
                </div>

                {{-- Assegnazione (se presente) --}}
                @if($record->assegnatoA)
                    <div class="timeline-item">
                        <div class="timeline-line w-30px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-30px">
                            <div class="symbol-label bg-light-success">
                                <i class="fas fa-user-check text-success fs-7"></i>
                            </div>
                        </div>
                        <div class="timeline-content mb-6 mt-n1">
                            <div class="pe-3">
                                <div class="fs-6 fw-semibold text-gray-800 mb-1">Assegnato</div>
                                <div class="fs-7 text-muted">{{ $record->updated_at->format('d/m/Y H:i') }}</div>
                                <div class="fs-7 text-gray-700">{{ $record->assegnatoA->nome }} {{ $record->assegnatoA->cognome }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Cambio stato in lavorazione --}}
                @if($record->stato !== \App\Enums\StatoTicketEnum::aperto->value)
                    <div class="timeline-item">
                        <div class="timeline-line w-30px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-30px">
                            <div class="symbol-label bg-light-info">
                                <i class="fas fa-cog text-info fs-7"></i>
                            </div>
                        </div>
                        <div class="timeline-content mb-6 mt-n1">
                            <div class="pe-3">
                                <div class="fs-6 fw-semibold text-gray-800 mb-1">Stato cambiato</div>
                                <div class="fs-7 text-muted">{{ $record->updated_at->format('d/m/Y H:i') }}</div>
                                @php
                                    $statoEnum = \App\Enums\StatoTicketEnum::from($record->stato);
                                @endphp
                                <span class="badge badge-light-{{ $statoEnum->colore() }} badge-sm">{{ $statoEnum->testo() }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Chiusura (se presente) --}}
                @if($record->data_chiusura)
                    <div class="timeline-item">
                        <div class="timeline-line w-30px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-30px">
                            <div class="symbol-label bg-light-success">
                                <i class="fas fa-check-circle text-success fs-7"></i>
                            </div>
                        </div>
                        <div class="timeline-content mb-6 mt-n1">
                            <div class="pe-3">
                                <div class="fs-6 fw-semibold text-gray-800 mb-1">Ticket chiuso</div>
                                <div class="fs-7 text-muted">{{ $record->data_chiusura->format('d/m/Y H:i') }}</div>
                                @if($record->chiusoDa)
                                    <div class="fs-7 text-gray-700">{{ $record->chiusoDa->nome }} {{ $record->chiusoDa->cognome }}</div>
                                @endif
                                @if($record->note_chiusura)
                                    <div class="mt-2 p-2 bg-light-success rounded">
                                        <div class="fs-8 fw-bold text-success mb-1">Note:</div>
                                        <div class="fs-8 text-gray-700">{{ Str::limit($record->note_chiusura, 80) }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
