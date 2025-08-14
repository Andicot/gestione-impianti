@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-3 mb-6">
        <a href="{{ action([App\Http\Controllers\Aziendadiservizio\DispositivoMisuraController::class, 'index']) }}"
           class="btn btn-sm btn-secondary fw-bold">
            <i class="fas fa-arrow-left"></i> Torna all'Elenco
        </a>
        <a href="{{ action([App\Http\Controllers\Aziendadiservizio\DispositivoMisuraController::class, 'edit'], $record->id) }}"
           class="btn btn-sm btn-primary fw-bold">
            <i class="fas fa-edit"></i> Modifica
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        {{-- Informazioni principali --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Dispositivo {{ $record->matricola }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Matricola</label>
                                <div class="text-dark fw-bolder fs-4">{{ $record->matricola }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Nome Dispositivo</label>
                                <div class="text-dark">{{ $record->nome_dispositivo ?: 'Non specificato' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Descrizione 1</label>
                                <div class="text-dark">{{ $record->descrizione_1 ?: 'Non specificata' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Descrizione 2</label>
                                <div class="text-dark">{{ $record->descrizione_2 ?: 'Non specificata' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Marca</label>
                                <div class="text-dark">{{ $record->marca ?: 'Non specificata' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Modello</label>
                                <div class="text-dark">{{ $record->modello ?: 'Non specificato' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Tipo</label>
                                <div class="text-dark">{{ strtoupper($record->tipo) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Offset</label>
                                <div class="text-dark">{{ \App\importo($record->offset) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Data Installazione</label>
                                <div class="text-dark">
                                    @if($record->data_installazione)
                                        {{ $record->data_installazione->format('d/m/Y') }}
                                        <div class="text-muted fs-7">{{ $record->data_installazione->diffForHumans() }}</div>
                                    @else
                                        Non specificata
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Stato Dispositivo</label>
                                <div>{!! $record->badgeStato() !!}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Ubicazione</label>
                                <div class="text-dark">{{ $record->ubicazione ?: 'Non specificata' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Creato Automaticamente</label>
                                <div>
                                    @if($record->creato_automaticamente)
                                        <span class="badge badge-light-info">
                                            Sì - Da importazione CSV
                                        </span>
                                    @else
                                        <span class="badge badge-light-primary">
                                            No - Inserito manualmente
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($record->note)
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <label class="form-label text-muted">Note</label>
                                    <div class="text-dark">{{ $record->note }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Relazioni e statistiche --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Relazioni
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-muted">Azienda Servizio</label>
                        <div class="text-dark">
                            @if($record->azienda_servizio_id)
                                ID: {{ $record->azienda_servizio_id }}
                            @else
                                <span class="text-muted">Non assegnata</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted">Unità Immobiliare</label>
                        <div class="text-dark">
                            @if($record->unita_immobiliare_id)
                                @if($record->unitaImmobiliare)
                                    <div class="fw-bold">{{ $record->unitaImmobiliare->nominativo_unita ?: 'UI ' . $record->unita_immobiliare_id }}</div>
                                    <div class="text-muted fs-7">
                                        @if($record->unitaImmobiliare->scala)
                                            Scala {{ $record->unitaImmobiliare->scala }} -
                                        @endif
                                        Piano {{ $record->unitaImmobiliare->piano }} -
                                        Interno {{ $record->unitaImmobiliare->interno }}
                                    </div>
                                @else
                                    ID: {{ $record->unita_immobiliare_id }}
                                @endif
                            @else
                                <span class="text-muted">Non assegnata</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted">Impianto</label>
                        <div class="text-dark">
                            @if($record->impianto_id)
                                @if($record->impianto)
                                    <div class="fw-bold">{{ $record->impianto->nome_impianto }}</div>
                                    <div class="text-muted fs-7">{{ $record->impianto->indirizzo }}</div>
                                @else
                                    ID: {{ $record->impianto_id }}
                                @endif
                            @else
                                <span class="text-muted">Non assegnato</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted">Concentratore</label>
                        <div class="text-dark">
                            @if($record->concentratore_id)
                                @if($record->concentratore)
                                    <div class="fw-bold">{{ $record->concentratore->matricola }}</div>
                                    <div class="text-muted fs-7">{{ $record->concentratore->marca }} {{ $record->concentratore->modello }}</div>
                                @else
                                    ID: {{ $record->concentratore_id }}
                                @endif
                            @else
                                <span class="text-muted">Non assegnato</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Letture --}}
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="card-title">
                        Ultime Letture
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-muted">Ultimo Valore Rilevato</label>
                        <div class="text-dark">
                            @if($record->ultimo_valore_rilevato !== null)
                                <span class="fw-bold fs-3 text-primary">{{ \App\importo($record->ultimo_valore_rilevato) }}</span>
                            @else
                                <span class="text-muted">Nessuna lettura</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted">Data Ultima Lettura</label>
                        <div class="text-dark">
                            @if($record->data_ultima_lettura)
                                {{ $record->data_ultima_lettura->format('d/m/Y H:i:s') }}
                                <div class="text-muted fs-7">{{ $record->data_ultima_lettura->diffForHumans() }}</div>
                            @else
                                <span class="text-muted">Mai registrata</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metadati --}}
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="card-title">
                        Metadati
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-muted">Data Creazione</label>
                        <div class="text-dark">
                            {{ $record->created_at->format('d/m/Y H:i:s') }}
                            <div class="text-muted fs-7">{{ $record->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted">Ultimo Aggiornamento</label>
                        <div class="text-dark">
                            {{ $record->updated_at->format('d/m/Y H:i:s') }}
                            <div class="text-muted fs-7">{{ $record->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
