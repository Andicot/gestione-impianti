<div class="d-flex justify-content-between gap-5">
    {{-- Contatore Totale Impianti --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-primary">
                <i class="fas fa-building text-primary fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Totale Impianti</div>
            <div class="fs-4 fw-bold text-gray-800">{{ $statistiche['totale_impianti'] }}</div>
        </div>
    </div>

    {{-- Contatore Impianti Attivi --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-success">
                <i class="fas fa-check-circle text-success fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Impianti Attivi</div>
            <div class="fs-4 fw-bold text-success">{{ $statistiche['totale_attivi'] }}</div>
        </div>
    </div>

    {{-- Contatore Impianti Dismessi --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-warning">
                <i class="fas fa-exclamation-triangle text-warning fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Dismessi</div>
            <div class="fs-4 fw-bold text-warning">{{ $statistiche['totale_dismessi'] }}</div>
        </div>
    </div>

    {{-- Contatore Unità Immobiliari Totali --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-info">
                <i class="fas fa-home text-info fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Unità Immobiliari</div>
            <div class="fs-4 fw-bold text-info">{{ $statistiche['totale_unita_immobiliari'] }}</div>
        </div>
    </div>
</div>


{{-- Header tabella con filtri --}}
<div class="border-0 pt-6">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            @includeWhen(isset($testoCerca),'Metronic._components.ricerca')
        </div>
        <div class="d-flex justify-content-end">
            {{-- Pulsante Nuovo Impianto a destra --}}
            @if($record->attivo)
                <a href="{{action([\App\Http\Controllers\Backend\ImpiantoController::class,'create'],'azienda_id='.$record->id)}}"
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fs-4"></i>
                    Nuovo Impianto
                </a>
            @endif
        </div>
    </div>
</div>

{{-- Tabella Impianti --}}
<div class="pt-0" id="tabella">
    @include('Admin.AziendaServizio.show.tabImpiantiTabella')
</div>

{{-- Script per filtri e ricerca --}}
@push('customScript')
    <script>
        $(document).ready(function () {
            // TODO: Implementare filtri quando saranno pronti i dati

            // Ricerca
            $('[data-kt-customer-table-filter="search"]').on('keyup', function () {
                console.log('Ricerca:', this.value);
                // Implementare logica di ricerca
            });

            // Filtro stato
            $('select[data-control="select2"]').on('change', function () {
                console.log('Filtro cambiato:', this.value);
                // Implementare logica di filtro
            });
        });
    </script>
@endpush
