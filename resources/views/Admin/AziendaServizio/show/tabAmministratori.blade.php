<div class="d-flex justify-content-between gap-5">
    {{-- Contatore Totale Amministratori --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-primary">
                {!! \App\Enums\IconeEnum::amministratore->render(' fs-4','text-primary') !!}
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Totale Amministratori</div>
            <div class="fs-4 fw-bold text-gray-800">{{ $statistiche['totale_amministratori'] }}</div>
        </div>
    </div>

    {{-- Contatore Amministratori Attivi --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-success">
                <i class="fas fa-check-circle text-success fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Amministratori Attivi</div>
            <div class="fs-4 fw-bold text-success">{{ $statistiche['totale_attivi'] }}</div>
        </div>
    </div>

    {{-- Contatore Amministratori Non Attivi --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-warning">
                <i class="fas fa-exclamation-triangle text-warning fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Non Attivi</div>
            <div class="fs-4 fw-bold text-warning">{{ $statistiche['totale_non_attivi'] }}</div>
        </div>
    </div>
</div>

<div class="border-0 pt-6">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            @includeWhen(isset($testoCerca),'Metronic._components.ricerca')
        </div>
        <div class="d-flex justify-content-end">
            @if($record->attivo)
                <a href="{{action([\App\Http\Controllers\Backend\AmministratoreController::class,'create'],'azienda_id='.$record->id)}}"
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fs-4"></i>
                    Nuovo Amministratore
                </a>
            @endif
        </div>
    </div>
</div>

<div class="pt-0" id="tabella">
    @include('Admin.AziendaServizio.show.tabAmministratoriTabella')
</div>
