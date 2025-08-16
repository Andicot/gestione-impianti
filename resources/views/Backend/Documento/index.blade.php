@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-2">
        @includeWhen(isset($testoCerca),'Metronic._components.ricerca')

        <button class="btn btn-sm btn-flex @if($conFiltro) btn-success @else btn-secondary @endif"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#filtri-card"
                aria-expanded="false"
                aria-controls="filtri-card"
                id="toggle-filtri">
            <i class="bi bi-funnel fs-3"></i>
            <span class="d-none d-md-block">Filtri</span>
            @if($conFiltro)
                <span class="badge badge-light badge-sm ms-2">Attivi</span>
            @endif
        </button>

        @isset($testoNuovo)
            <a class="btn btn-sm btn-primary fw-bold" data-targetZ="kt_modal" data-toggleZ="modal-ajax" href="{{action([$controller,'create'])}}"><span
                    class="d-md-none">+</span><span class="d-none d-md-block">{{$testoNuovo}}</span></a>
        @endisset

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Card Statistiche --}}
            <div class="card mb-5">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border border-gray-300 border-dashed rounded py-3">
                                <div class="fs-1 fw-bold text-primary">{{$records->total()}}</div>
                                <div class="fs-7 fw-semibold text-muted">Totale Documenti</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border border-gray-300 border-dashed rounded py-3">
                                <div class="fs-1 fw-bold text-success">{{$records->where('pubblico', true)->count()}}</div>
                                <div class="fs-7 fw-semibold text-muted">Pubblici</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border border-gray-300 border-dashed rounded py-3">
                                <div
                                    class="fs-1 fw-bold text-warning">{{$records->where('data_scadenza', '<=', now()->addDays(30))->where('data_scadenza', '>', now())->count()}}</div>
                                <div class="fs-7 fw-semibold text-muted">In Scadenza</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border border-gray-300 border-dashed rounded py-3">
                                <div class="fs-1 fw-bold text-danger">{{$records->where('data_scadenza', '<', now())->count()}}</div>
                                <div class="fs-7 fw-semibold text-muted">Scaduti</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Filtri Collassabili --}}

            <div class="collapse @if($conFiltro) show @endif" id="filtri-card">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Filtri di Ricerca
                            @if($conFiltro)
                                <span class="badge badge-primary ms-2">Filtri Attivi</span>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Impianto</label>
                                <select name="impianto_id" class="form-select form-select-sm form-select-solid" id="impianto_id">
                                    {!! \App\Models\Impianto::selected(request()->input('impianto_id')) !!}
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Visibilità</label>
                                <select name="visibilita" class="form-select form-select-sm form-select-solid " data-control="select2" data-hide-search="true">
                                    <option value="">Tutte</option>
                                    <option value="pubblico" {{ request('visibilita') == 'pubblico' ? 'selected' : '' }}>Pubblici</option>
                                    <option value="riservato" {{ request('visibilita') == 'riservato' ? 'selected' : '' }}>Riservati Amministratori</option>
                                    <option value="privato" {{ request('visibilita') == 'privato' ? 'selected' : '' }}>Privati</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Scadenza</label>
                                <select name="scadenza" class="form-select form-select-sm form-select-solid " data-control="select2" data-hide-search="true">
                                    <option value="">Tutte</option>
                                    <option value="in_scadenza" {{ request('scadenza') == 'in_scadenza' ? 'selected' : '' }}>In Scadenza (30gg)</option>
                                    <option value="scaduti" {{ request('scadenza') == 'scaduti' ? 'selected' : '' }}>Scaduti</option>
                                    <option value="senza_scadenza" {{ request('scadenza') == 'senza_scadenza' ? 'selected' : '' }}>Senza Scadenza</option>
                                </select>
                            </div>

                            <div class="col-md-12 d-flex align-items-end">
                                <button type="submit" class="btn btn-sm btn-primary me-2">
                                    <i class="fas fa-search"></i> Filtra
                                </button>
                                <a href="{{ action([$controller,'index']) }}" class="btn btn-sm btn-secondary me-2">
                                    <i class="fas fa-eraser"></i> Reset
                                </a>
                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#filtri-card">
                                    <i class="fas fa-times"></i> Chiudi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Card Principale --}}
            <div class="card">
                <div class="card-body pt-0" id="div_elenco">
                    @include('Backend.Documento.tabella')
                </div>
            </div>
        </div>
    </div>

@endsection
@push('customScript')
    <script src="/assets_backend/js-miei/select2_it.js"></script>

    <script>
        select2Universale('impianto_id','un impianto',-1);
    </script>
@endpush
