<div class="d-flex justify-content-between gap-5">
    {{-- Contatore Totale Bollettini --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-primary">
                <i class="fas fa-file-invoice text-primary fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Totale Bollettini</div>
            <div class="fs-4 fw-bold text-gray-800">{{ $statistiche['totale_bollettini'] ?? $records->total() }}</div>
        </div>
    </div>

    {{-- Contatore Pagati --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-success">
                <i class="fas fa-check-circle text-success fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Pagati</div>
            <div class="fs-4 fw-bold text-success">{{ $statistiche['totale_pagati'] ?? $records->where('stato_pagamento', 'pagato')->count() }}</div>
        </div>
    </div>

    {{-- Contatore Non Pagati --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-danger">
                <i class="fas fa-times-circle text-danger fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Non Pagati</div>
            <div class="fs-4 fw-bold text-danger">{{ $statistiche['totale_non_pagati'] ?? $records->where('stato_pagamento', 'non_pagato')->count() }}</div>
        </div>
    </div>

    {{-- Contatore Scaduti --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-warning">
                <i class="fas fa-exclamation-triangle text-warning fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Scaduti</div>
            <div class="fs-4 fw-bold text-warning">{{ $statistiche['scaduti'] ?? $records->where('data_scadenza', '<', now())->where('stato_pagamento', '!=', 'pagato')->count() }}</div>
        </div>
    </div>
</div>

{{-- Header tabella con filtri --}}
<div class="border-0 pt-6">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            @includeWhen(isset($testoCerca),'Metronic._components.ricerca')
            <!-- Pulsante Filtri -->
            <button class="btn btn-sm btn-flex @if($conFiltro) btn-light-success @else btn-secondary @endif "
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#filtri-bollettini-card"
                    aria-expanded="false"
                    aria-controls="filtri-bollettini-card"
                    id="toggle-filtri-bollettini">
                <i class="bi bi-funnel fs-3"></i>
                <span class="d-none d-md-block">Filtri</span>
            </button>
        </div>

        <div class="d-flex justify-content-end gap-3">
            {{-- Pulsante Nuovo Bollettino --}}
            @if($record->stato_impianto === \App\Enums\StatoImpiantoEnum::attivo->value)
                <a href="{{action([\App\Http\Controllers\Backend\BollettinoController::class,'create'],$record->id)}}"
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fs-4"></i>
                    Nuovo Bollettino
                </a>
            @endif
        </div>
    </div>

    <!-- Filtri Collassabili -->
    <div class="collapse" id="filtri-bollettini-card">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Filtri di Ricerca</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <!-- Campo di ricerca generale -->
                    <div class="col-md-4">
                        <label class="form-label">Cerca</label>
                        <input type="text" name="cerca_no_ajax" class="form-control form-control-solid form-control-sm"
                               placeholder="Unità immobiliare, nominativo..."
                               value="{{ request('cerca_no_ajax') }}">
                    </div>

                    <!-- Filtro Stato Pagamento -->
                    <div class="col-md-2">
                        <label class="form-label">Stato Pagamento</label>
                        <select name="stato_pagamento" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutti gli stati</option>
                            @foreach(\App\Enums\StatoPagamentoBollettinoEnum::cases() as $stato)
                                <option value="{{$stato->value}}" {{ request('stato_pagamento') == $stato->value ? 'selected' : '' }}>
                                    {{$stato->testo()}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Periodo -->
                    <div class="col-md-2">
                        <label class="form-label">Periodo</label>
                        <select name="periodo_id" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutti i periodi</option>
                            @php
                                $periodiDisponibili = \App\Models\PeriodoContabilizzazione::where('impianto_id', $record->id)->orderBy('data_inizio', 'desc')->get();
                            @endphp
                            @foreach($periodiDisponibili as $periodo)
                                <option value="{{$periodo->id}}" {{ request('periodo_id') == $periodo->id ? 'selected' : '' }}>
                                    {{$periodo->codice}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Data Scadenza -->
                    <div class="col-md-2">
                        <label class="form-label">Scadenza</label>
                        <select name="scadenza" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutte</option>
                            <option value="scaduti" {{ request('scadenza') == 'scaduti' ? 'selected' : '' }}>Scaduti</option>
                            <option value="in_scadenza" {{ request('scadenza') == 'in_scadenza' ? 'selected' : '' }}>In scadenza (7gg)</option>
                            <option value="future" {{ request('scadenza') == 'future' ? 'selected' : '' }}>Future</option>
                        </select>
                    </div>

                    <!-- Filtro Visualizzazione -->
                    <div class="col-md-2">
                        <label class="form-label">Visualizzazione</label>
                        <select name="visualizzato" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutti</option>
                            <option value="1" {{ request('visualizzato') == '1' ? 'selected' : '' }}>Visualizzati</option>
                            <option value="0" {{ request('visualizzato') == '0' ? 'selected' : '' }}>Non Visualizzati</option>
                        </select>
                    </div>

                    <!-- Pulsanti -->
                    <div class="col-md-12 d-flex align-items-end">
                        <button type="submit" class="btn btn-sm btn-primary me-2">
                            <i class="fas fa-search"></i> Filtra
                        </button>
                        <a href="{{url()->current()}}" class="btn btn-sm btn-secondary me-2">
                            <i class="fas fa-eraser"></i> Reset
                        </a>
                        <button type="button" class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#filtri-bollettini-card">
                            <i class="fas fa-times"></i> Chiudi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Tabella Bollettini --}}
<div class="pt-0" id="tabella">
    @include('Backend.Impianto.show.tabBollettiniTabella')
</div>

{{-- Script per filtri e ricerca --}}
@push('customScript')
    <script>

    </script>
@endpush
