@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-3 mb-6">
        <a href="{{ action([\App\Http\Controllers\Backend\DispositivoMisuraController::class, 'index']) }}"
           class="btn btn-sm btn-secondary fw-bold">
            <i class="fas fa-arrow-left"></i> Torna all'Elenco
        </a>
        <a href="{{ action([\App\Http\Controllers\Backend\DispositivoMisuraController::class, 'edit'], $record->id) }}"
           class="btn btn-sm btn-primary fw-bold">
            <i class="fas fa-edit"></i> Modifica
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        {{-- Sidebar con tutti i dati --}}
        <div class="col-lg-4">
            <div class="card mb-5 mb-xl-8">
                <div class="card-body pt-15">
                    <div class="d-flex flex-center flex-column mb-5">
                        <div class="fs-3 text-gray-800 fw-bolder mb-1">
                            {{ $record->matricola }}
                        </div>
                        <div class="fs-4 text-gray-800 fw-bolder mb-1">
                            {{ $record->nome_dispositivo ?: 'Dispositivo ' . $record->id }}
                        </div>
                        <div class="fs-5 fw-bold text-muted mb-6">
                            {!! $record->badgeStato() !!}
                        </div>
                    </div>

                    {{-- Dettagli Dispositivo --}}
                    <div class="fw-bolder mb-3">
                        <h3>Dettagli Dispositivo</h3>
                    </div>

                    <div id="kt_dispositivo_view_details" class="collapse show">
                        <div class="fs-6">
                            {{-- Tipo --}}
                            <div class="fw-bolder mt-5">Tipo</div>
                            <div class="text-gray-600">
                                <span class="badge badge-light-info">{{ strtoupper($record->tipo) }}</span>
                            </div>

                            {{-- Marca e Modello --}}
                            @if($record->marca || $record->modello)
                                <div class="fw-bolder mt-5">Marca e Modello</div>
                                <div class="text-gray-600">
                                    {{ $record->marca }} {{ $record->modello }}
                                </div>
                            @endif

                            {{-- Descrizioni --}}
                            @if($record->descrizione_1)
                                <div class="fw-bolder mt-5">Descrizione 1</div>
                                <div class="text-gray-600">{{ $record->descrizione_1 }}</div>
                            @endif

                            @if($record->descrizione_2)
                                <div class="fw-bolder mt-5">Descrizione 2</div>
                                <div class="text-gray-600">{{ $record->descrizione_2 }}</div>
                            @endif

                            {{-- Offset --}}
                            <div class="fw-bolder mt-5">Offset</div>
                            <div class="text-gray-600">{{ \App\importo($record->offset) }}</div>

                            {{-- Data Installazione --}}
                            @if($record->data_installazione)
                                <div class="fw-bolder mt-5">Data Installazione</div>
                                <div class="text-gray-600">
                                    {{ $record->data_installazione->format('d/m/Y') }}
                                    <div class="text-muted fs-6">{{ $record->data_installazione->diffForHumans() }}</div>
                                </div>
                            @endif

                            {{-- Ubicazione --}}
                            @if($record->ubicazione)
                                <div class="fw-bolder mt-5">Ubicazione</div>
                                <div class="text-gray-600">{{ $record->ubicazione }}</div>
                            @endif

                            {{-- Creato Automaticamente --}}
                            <div class="fw-bolder mt-5">Origine</div>
                            <div class="text-gray-600">
                                {!! $record->badgeOrigine() !!}
                            </div>

                            {{-- Separatore --}}
                            <div class="separator separator-dashed my-3"></div>

                            {{-- Relazioni --}}
                            <div class="fw-bolder mt-6">
                                <h3>Relazioni</h3>
                            </div>

                            {{-- Azienda Servizio --}}
                            @if($record->azienda_servizio_id)
                                <div class="fw-bolder mt-5">Azienda Servizio</div>
                                <div class="text-gray-600">ID: {{ $record->azienda_servizio_id }}</div>
                            @endif

                            {{-- Unità Immobiliare --}}
                            @if($record->unita_immobiliare_id)
                                <div class="fw-bolder mt-5">Unità Immobiliare</div>
                                <div class="text-gray-600">
                                    @if($record->unitaImmobiliare)
                                        <div class="fw-bold">{{ $record->unitaImmobiliare->nominativo_unita ?: 'UI ' . $record->unita_immobiliare_id }}</div>
                                        <div class="text-muted fs-6">
                                            @if($record->unitaImmobiliare->scala)
                                                Scala {{ $record->unitaImmobiliare->scala }} -
                                            @endif
                                            Piano {{ $record->unitaImmobiliare->piano }} -
                                            Interno {{ $record->unitaImmobiliare->interno }}
                                        </div>
                                    @else
                                        ID: {{ $record->unita_immobiliare_id }}
                                    @endif
                                </div>
                            @endif

                            {{-- Impianto --}}
                            @if($record->impianto_id)
                                <div class="fw-bolder mt-5">Impianto</div>
                                <div class="text-gray-600">
                                    @if($record->impianto)
                                        <div class="fw-bold">{{ $record->impianto->nome_impianto }}</div>
                                        <div class="text-muted fs-6">{{ $record->impianto->indirizzo }}</div>
                                    @else
                                        ID: {{ $record->impianto_id }}
                                    @endif
                                </div>
                            @endif

                            {{-- Concentratore --}}
                            @if($record->concentratore_id)
                                <div class="fw-bolder mt-5">Concentratore</div>
                                <div class="text-gray-600">
                                    @if($record->concentratore)
                                        <div class="fw-bold">{{ $record->concentratore->matricola }}</div>
                                        <div class="text-muted fs-6">{{ $record->concentratore->marca }} {{ $record->concentratore->modello }}</div>
                                    @else
                                        ID: {{ $record->concentratore_id }}
                                    @endif
                                </div>
                            @endif

                            {{-- Separatore --}}
                            <div class="separator separator-dashed my-3"></div>

                            {{-- Letture --}}
                            <div class="fw-bolder mt-6">
                                <h3>Letture</h3>
                            </div>

                            {{-- Ultimo Valore Rilevato --}}
                            <div class="fw-bolder mt-5">Ultimo Valore Rilevato</div>
                            <div class="text-gray-600">
                                @if($record->ultimo_valore_rilevato !== null)
                                    <span class="fw-bold fs-3 text-primary">{{ \App\importo($record->ultimo_valore_rilevato) }}</span>
                                @else
                                    <span class="text-muted">Nessuna lettura</span>
                                @endif
                            </div>

                            {{-- Data Ultima Lettura --}}
                            @if($record->data_ultima_lettura)
                                <div class="fw-bolder mt-5">Data Ultima Lettura</div>
                                <div class="text-gray-600">
                                    {{ $record->data_ultima_lettura->format('d/m/Y H:i:s') }}
                                    <div class="text-muted fs-6">{{ $record->data_ultima_lettura->diffForHumans() }}</div>
                                </div>
                            @endif

                            {{-- Note --}}
                            @if($record->note)
                                <div class="separator separator-dashed my-3"></div>
                                <div class="fw-bolder mt-5">Note</div>
                                <div class="text-gray-600">
                                    {!! nl2br(e($record->note)) !!}
                                </div>
                            @endif

                            {{-- Data registrazione --}}
                            <div class="separator separator-dashed my-3"></div>
                            <div class="fw-bolder mt-5">Data Registrazione</div>
                            <div class="text-gray-600">{{ $record->created_at->format('d/m/Y H:i') }}</div>

                            @if($record->updated_at != $record->created_at)
                                <div class="fw-bolder mt-3">Ultima Modifica</div>
                                <div class="text-gray-600">{{ $record->updated_at->format('d/m/Y H:i') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Area principale per le tabelle --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Letture Dispositivo</h3>
                </div>
                <div class="card-body">
                    @include('Backend.DispositivoMisura.show.tabLetture')
                </div>
            </div>
        </div>
    </div>
@endsection
