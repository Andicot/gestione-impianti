@extends('Metronic._layout._main')

@section('content')
    {{-- Header Azienda --}}
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end" style="background-color: #1B84FF;background-image:url('assets/media/patterns/vector-1.png')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h1 class="fs-2hx fw-bold text-white me-2 lh-1">{{ $azienda->ragione_sociale }}</h1>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Dashboard Azienda di Servizio</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <div class="d-flex align-items-center px-7 pb-5">
                        <div class="symbol symbol-45px me-5">
                        <span class="symbol-label bg-white bg-opacity-20">
                            <i class="fas fa-industry text-white fs-1"></i>
                        </span>
                        </div>
                        <div>
                            <span class="fs-6 fw-bolder text-white opacity-75 pb-1">{{ $azienda->indirizzo_ufficio }}</span><br>
                            <span class="fs-7 fw-bold text-white opacity-75">{{ $azienda->cap_ufficio }} {{ $azienda->citta_ufficio }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiche Overview --}}
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        {{-- Impianti Gestiti --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-4 mb-md-5 mb-xl-10">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10" style="background-color: #F1416C;background-image:url('assets/media/patterns/vector-1.png')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['impianti']['totale'] }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Impianti Gestiti</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <span class="fs-6 fw-bolder text-white opacity-75 pb-1 px-7">Attivi: {{ $statistiche['impianti']['attivi'] }}</span>
                    <div class="d-flex align-items-center px-7">
                        <div class="symbol symbol-30px me-5 mb-8">
                        <span class="symbol-label">
                            <i class="fas fa-building text-white fs-1"></i>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Amministratori --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-4 mb-md-5 mb-xl-10">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10" style="background-color: #50CD89;background-image:url('assets/media/patterns/vector-3.png')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['amministratori']['totale'] }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Amministratori</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <span class="fs-6 fw-bolder text-white opacity-75 pb-1 px-7">Attivi: {{ $statistiche['amministratori']['attivi'] }}</span>
                    <div class="d-flex align-items-center px-7">
                        <div class="symbol symbol-30px me-5 mb-8">
                        <span class="symbol-label">
                            <i class="fas fa-users text-white fs-1"></i>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dispositivi --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-4 mb-md-5 mb-xl-10">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10" style="background-color: #F1BC00;background-image:url('assets/media/patterns/vector-4.png')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['dispositivi']['totale'] }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Dispositivi</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <span class="fs-6 fw-bolder text-white opacity-75 pb-1 px-7">Attivi: {{ $statistiche['dispositivi']['attivi'] }}</span>
                    <div class="d-flex align-items-center px-7">
                        <div class="symbol symbol-30px me-5 mb-8">
                        <span class="symbol-label">
                            <i class="fas fa-microchip text-white fs-1"></i>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        {{-- Impianti Recenti --}}
        <div class="col-xl-8">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">I Tuoi Impianti</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Impianti gestiti dalla tua azienda</span>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ action([\App\Http\Controllers\Backend\ImpiantoController::class, 'index']) }}" class="btn btn-sm btn-light">
                            Vedi Tutti
                        </a>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                            <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-200px rounded-start">Nome Impianto</th>
                                <th class="min-w-125px">Stato</th>
                                <th class="min-w-125px">Amministratore</th>
                                <th class="min-w-125px rounded-end">Unità</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($impianti_recenti as $impianto)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ action([\App\Http\Controllers\Backend\ImpiantoController::class, 'show'], $impianto->id) }}"
                                           class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                            {{ $impianto->nome_impianto }}
                                        </a>
                                        <span class="text-muted fw-semibold d-block fs-7">
                                        {{ $impianto->indirizzo }}
                                    </span>
                                    </td>
                                    <td>
                                        @if($impianto->stato_impianto === 'attivo')
                                            <span class="badge badge-light-success">Attivo</span>
                                        @else
                                            <span class="badge badge-light-danger">Dismesso</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($impianto->amministratore)
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-circle symbol-30px me-3">
                                                <span class="symbol-label bg-light-primary text-primary fw-bold">
                                                    {{ substr($impianto->amministratore->user->nome, 0, 1) }}{{ substr($impianto->amministratore->user->cognome, 0, 1) }}
                                                </span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800 fw-bold fs-7">{{ $impianto->amministratore->ragione_sociale }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Non assegnato</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-gray-800 fw-bold">{{ $impianto->unita_immobiliari_count ?? 0 }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Nessun impianto gestito
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Azioni Rapide --}}
        <div class="col-xl-4">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Azioni Rapide</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Operazioni frequenti</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="d-flex flex-column gap-3">
                        {{-- Nuovo Impianto --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\ImpiantoController::class, 'create']) }}"
                           class="btn btn-light-primary d-flex align-items-center p-3">
                            <i class="fas fa-plus fs-3 me-3"></i>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Nuovo Impianto</span>
                                <span class="text-muted fs-7">Registra nuovo impianto</span>
                            </div>
                        </a>

                        {{-- Nuovo Amministratore --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\AmministratoreController::class, 'create']) }}"
                           class="btn btn-light-success d-flex align-items-center p-3">
                            <i class="fas fa-user-tie fs-3 me-3"></i>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Nuovo Amministratore</span>
                                <span class="text-muted fs-7">Registra amministratore</span>
                            </div>
                        </a>

                        {{-- Dispositivi --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\DispositivoMisuraController::class, 'index']) }}"
                           class="btn btn-light-info d-flex align-items-center p-3">
                            <i class="fas fa-microchip fs-3 me-3"></i>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Gestisci Dispositivi</span>
                                <span class="text-muted fs-7">Visualizza e configura</span>
                            </div>
                        </a>

                        {{-- Concentratori --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\ConcentratoreController::class, 'index']) }}"
                           class="btn btn-light-warning d-flex align-items-center p-3">
                            <i class="fas fa-wifi fs-3 me-3"></i>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Concentratori</span>
                                <span class="text-muted fs-7">Gestisci concentratori</span>
                            </div>
                        </a>

                        {{-- Letture Consumi --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\LetturaConsumoController::class, 'index']) }}"
                           class="btn btn-light-dark d-flex align-items-center p-3">
                            <i class="fas fa-chart-line fs-3 me-3"></i>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Letture Consumi</span>
                                <span class="text-muted fs-7">Monitora consumi</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
