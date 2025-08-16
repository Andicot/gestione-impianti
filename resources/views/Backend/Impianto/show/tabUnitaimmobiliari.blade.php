<div class="d-flex justify-content-between gap-5">
    {{-- Contatore Totale Unità --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-primary">
                <i class="fas fa-home text-primary fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Totale Unità</div>
            <div class="fs-4 fw-bold text-gray-800">{{ $statistiche['totale_unita'] ?? $records->total() }}</div>
        </div>
    </div>

    {{-- Contatore Appartamenti --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-success">
                <i class="fas fa-building text-success fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Appartamenti</div>
            <div class="fs-4 fw-bold text-success">{{ $statistiche['totale_appartamenti'] ?? $records->where('tipologia', 'appartamento')->count() }}</div>
        </div>
    </div>

    {{-- Contatore Box/Garage --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-warning">
                <i class="fas fa-car text-warning fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Box/Garage</div>
            <div class="fs-4 fw-bold text-warning">{{ $statistiche['totale_box'] ?? $records->where('tipologia', 'box')->count() }}</div>
        </div>
    </div>

    {{-- Contatore Con Condomino --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-info">
                <i class="fas fa-user-check text-info fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Con Condomino</div>
            <div class="fs-4 fw-bold text-info">{{ $statistiche['con_condomino'] ?? $records->whereNotNull('user_id')->count() }}</div>
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
                    data-bs-target="#filtri-unita-card"
                    aria-expanded="false"
                    aria-controls="filtri-unita-card"
                    id="toggle-filtri-unita">
                <i class="bi bi-funnel fs-3"></i>
                <span class="d-none d-md-block">Filtri</span>
            </button>
        </div>

        <div class="d-flex justify-content-end gap-3">
            {{-- Pulsante Nuova Unità --}}
            @if($record->stato_impianto === \App\Enums\StatoImpiantoEnum::attivo->value)
                <a href="{{action([\App\Http\Controllers\Backend\UnitaImmobiliareController::class,'create'],'impianto_id='.$record->id)}}"
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fs-4"></i>
                    Nuova Unità
                </a>
            @endif
        </div>
    </div>

    <!-- Filtri Collassabili -->
    <div class="collapse" id="filtri-unita-card">
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
                               placeholder="Scala, piano, interno, nominativo..."
                               value="{{ request('cerca_no_ajax') }}">
                    </div>

                    <!-- Filtro Tipologia -->
                    <div class="col-md-2">
                        <label class="form-label">Tipologia</label>
                        <select name="tipologia" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutte le tipologie</option>
                            @foreach(\App\Enums\TipoUnitaImmobiliareEnum::cases() as $tipologia)
                                <option value="{{$tipologia->value}}" {{ request('tipologia') == $tipologia->value ? 'selected' : '' }}>
                                    {{$tipologia->testo()}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Piano -->
                    <div class="col-md-2">
                        <label class="form-label">Piano</label>
                        <select name="piano" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutti i piani</option>
                            @php
                                $pianiDisponibili = $records->pluck('piano')->unique()->sort()->values();
                            @endphp
                            @foreach($pianiDisponibili as $piano)
                                <option value="{{$piano}}" {{ request('piano') == $piano ? 'selected' : '' }}>
                                    Piano {{$piano}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Scala -->
                    <div class="col-md-2">
                        <label class="form-label">Scala</label>
                        <select name="scala" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutte le scale</option>
                            <option value="con_scala" {{ request('scala') == 'con_scala' ? 'selected' : '' }}>Con Scala</option>
                            <option value="senza_scala" {{ request('scala') == 'senza_scala' ? 'selected' : '' }}>Senza Scala</option>
                            @php
                                $scaleDisponibili = $records->whereNotNull('scala')->pluck('scala')->unique()->sort()->values();
                            @endphp
                            @foreach($scaleDisponibili as $scala)
                                <option value="{{$scala}}" {{ request('scala') == $scala ? 'selected' : '' }}>
                                    Scala {{$scala}}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <!-- Filtro Millesimi -->
                    <div class="col-md-3">
                        <label class="form-label">Millesimi</label>
                        <select name="millesimi" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutti</option>
                            <option value="con_riscaldamento" {{ request('millesimi') == 'con_riscaldamento' ? 'selected' : '' }}>Con Risc.</option>
                            <option value="con_acs" {{ request('millesimi') == 'con_acs' ? 'selected' : '' }}>Con ACS</option>
                            <option value="senza_millesimi" {{ request('millesimi') == 'senza_millesimi' ? 'selected' : '' }}>Senza Millesimi</option>
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
                        <button type="button" class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#filtri-unita-card">
                            <i class="fas fa-times"></i> Chiudi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Tabella Unità Immobiliari --}}
<div class="pt-0" id="tabella">
    @include('Backend.Impianto.show.tabUnitaimmobiliariTabella')
</div>

{{-- Script per filtri e ricerca --}}
@push('customScript')
    <script>

    </script>
@endpush
