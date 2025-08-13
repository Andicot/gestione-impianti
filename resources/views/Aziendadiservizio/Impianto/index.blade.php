@extends('Metronic._layout._main')
@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-3 mb-6">

        @includeWhen(isset($testoCerca),'Metronic._components.ricerca')
        <!-- Pulsante Filtri -->

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
        @includeWhen(isset($ordinamenti),'Metronic._components.ordinamento')
        @isset($testoNuovo)
            <a class="btn btn-sm btn-primary fw-bold" data-targetZ="kt_modal" data-toggleZ="modal-ajax" href="{{action([$controller,'create'])}}"><span
                    class="d-md-none">+</span><span class="d-none d-md-block">{{$testoNuovo}}</span></a>
        @endisset
    </div>
@endsection
@section('content')
    <div class="row mb-4">
        <!-- Statistiche -->
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="fs-3 fw-bolder text-primary">{{ $statistiche['totale'] ?? 0 }}</h3>
                            <p class="mb-0 fs-2 fw-bolder">Totale Impianti</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="fs-3 fw-bolder text-success">{{ $statistiche['attivi'] ?? 0 }}</h3>
                            <p class="mb-0 fs-2 fw-bolder">Attivi</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="fs-3 fw-bolder text-warning">{{ $statistiche['con_concentratore'] ?? 0 }}</h3>
                            <p class="mb-0 fs-2 fw-bolder">Con Concentratore</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="fs-3 fw-bolder text-danger">{{ $statistiche['dismessi'] ?? 0 }}</h3>
                            <p class="mb-0 fs-2 fw-bolder">Dismessi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Aziendadiservizio.Impianto.indexFiltri')

    <!-- Tabella -->
    <div class="card pt-4">
        <div class="card-body pt-0 pb-5 fs-6 px-2 px-md-6" id="tabella">
            @include('Aziendadiservizio.Impianto.tabella')
        </div>
    </div>

@endsection
@push('customScript')
    <script src="/assets_backend/js-miei/select2_it.js"></script>

    <script>
        var indexUrl = '{{action([$controller,'index'])}}';
        $(function () {
            searchHandler();

            select2Universale('amministratore_id', 'un amministratore', -1);
            select2Universale('citta', 'una città', -1);

            // Gestione filtri rapidi automatici
            document.querySelectorAll('input[name="filtro_rapido"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    // Reset altri filtri quando si usa un filtro rapido
                    if (this.checked) {
                        switch (this.value) {
                            case 'attivi':
                                document.querySelector('select[name="stato"]').value = 'attivo';
                                break;
                            case 'con_concentratore':
                                document.querySelector('select[name="concentratore_id"]').value = 'con_concentratore';
                                break;
                            case 'senza_concentratore':
                                document.querySelector('select[name="concentratore_id"]').value = 'senza_concentratore';
                                break;
                        }
                    }
                });
            });
        });
    </script>
@endpush
