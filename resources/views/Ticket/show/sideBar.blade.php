{{-- Sidebar con informazioni del ticket --}}
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
    </div>
</div>
