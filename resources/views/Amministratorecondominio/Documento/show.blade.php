@extends('Metronic._layout._main')

@section('toolbar')

        <div class="d-flex align-items-center py-1 gap-3">
            {{-- Download --}}
            <a href="{{route('documento.download', $record->id)}}"
               class="btn btn-success btn-sm"
              >
                <i class="fas fa-download fs-4"></i>
                Scarica
            </a>

            {{-- Modifica (solo se caricato dall'utente corrente) --}}
            @if($record->caricato_da_id === Auth::id())
                <a href="{{action([$controller, 'edit'], $record->id)}}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit fs-4"></i>
                    Modifica
                </a>
            @endif
        </div>
@endsection

@section('content')
    <div class="row">
        {{-- Colonna Sinistra - Informazioni Documento --}}
        <div class="col-md-8">
            {{-- Card Principale --}}
            <div class="card mb-5">
                <div class="card-body">
                    <div class="row">
                        {{-- Nome File --}}
                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Nome File</label>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-{{\App\Enums\TipoDocumentoEnum::from($record->tipo_documento)->colore()}}">
                                        <i class="{{\App\Enums\TipoDocumentoEnum::from($record->tipo_documento)->icona()}} text-{{\App\Enums\TipoDocumentoEnum::from($record->tipo_documento)->colore()}} fs-2"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold text-gray-800">{{$record->nome_originale}}</div>
                                    <div class="text-muted fs-7">{{$record->dimensione_leggibile()}} • {{strtoupper(pathinfo($record->nome_originale, PATHINFO_EXTENSION))}}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Tipo Documento --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Tipo Documento</label>
                            <div>

                                    {{\App\Enums\TipoDocumentoEnum::from($record->tipo_documento)->testo()}}
                            </div>
                        </div>

                        {{-- Data Caricamento --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Data Caricamento</label>
                            <div class="text-gray-800">{{$record->created_at->format('d/m/Y H:i')}}</div>
                        </div>

                        {{-- Impianto --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Impianto</label>
                            @if($record->impianto)
                                <div class="text-gray-800">{{$record->impianto->nome_impianto}}</div>
                                <div class="text-muted fs-7">{{$record->impianto->indirizzo}}, {{$record->impianto->citta}}</div>
                            @else
                                <div class="text-muted">Non specificato</div>
                            @endif
                        </div>

                        {{-- Unità Immobiliare --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Unità Immobiliare</label>
                            @if($record->unitaImmobiliare)
                                <div class="text-gray-800">
                                    {{$record->unitaImmobiliare->scala ? 'Scala ' . $record->unitaImmobiliare->scala . ', ' : ''}}
                                    Piano {{$record->unitaImmobiliare->piano}},
                                    Int. {{$record->unitaImmobiliare->interno}}
                                </div>
                                @if($record->unitaImmobiliare->nominativo_unita)
                                    <div class="text-muted fs-7">{{$record->unitaImmobiliare->nominativo_unita}}</div>
                                @endif
                            @else
                                <div class="text-muted">Tutte le unità</div>
                            @endif
                        </div>

                        {{-- Descrizione --}}
                        @if($record->descrizione)
                            <div class="col-12 mb-4">
                                <label class="form-label fw-bold">Descrizione</label>
                                <div class="text-gray-800">{{$record->descrizione}}</div>
                            </div>
                        @endif

                        {{-- Note --}}
                        @if($record->note)
                            <div class="col-12 mb-4">
                                <label class="form-label fw-bold">Note</label>
                                <div class="text-gray-800">{{$record->note}}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Colonna Destra - Informazioni Aggiuntive --}}
        <div class="col-md-4">
            {{-- Card Visibilità --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">
                       Visibilità
                    </h3>
                </div>
                <div class="card-body">
                    @if($record->pubblico)
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <div class="fw-bold">Pubblico</div>
                                <div class="text-muted fs-7">Visibile a tutti i condomini</div>
                            </div>
                        </div>
                    @elseif($record->riservato_amministratori)
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <div class="fw-bold">Riservato</div>
                                <div class="text-muted fs-7">Solo amministratori</div>
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <div class="fw-bold">Privato</div>
                                <div class="text-muted fs-7">Accesso limitato</div>
                            </div>
                        </div>
                    @endif

                    {{-- Scadenza --}}
                    @if($record->data_scadenza)
                        <div class="separator my-4"></div>
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="fw-bold">Scadenza</div>
                                <div class="text-gray-800">{{$record->data_scadenza->format('d/m/Y')}}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Statistiche --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">
                   Statistiche
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Visualizzazioni</span>
                        <span class="fw-bold">{{$record->numero_visualizzazioni ?? 0}}</span>
                    </div>

                    @if($record->ultima_visualizzazione)
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Ultima visualizzazione</span>
                            <span class="text-gray-800 fs-7">{{$record->ultima_visualizzazione->format('d/m/Y H:i')}}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Caricato da --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                      Caricato da
                    </h3>
                </div>
                <div class="card-body">
                    @if($record->caricatoDa)

                            <div>
                                <div class="fw-bold">{{$record->caricatoDa->nome}} {{$record->caricatoDa->cognome}}</div>
                                <div class="text-muted fs-7">{{$record->created_at->format('d/m/Y H:i')}}</div>
                            </div>
                    @else
                        <div class="text-muted">Utente non disponibile</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
