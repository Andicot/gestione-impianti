@extends('Metronic._layout._main')

@section('content')
    {{-- Header Azienda --}}
    <div class="row g-5 g-xl-10 mb-5 ">
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
                            <span class="fs-6 fw-bolder text-white opacity-75 pb-1">{{ $azienda->indirizzo }}</span><br>
                            <span class="fs-7 fw-bold text-white opacity-75">{{ $azienda->cap }} {{ $azienda->citta }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiche Overview --}}
    <div class="row g-5 g-xl-10 ">
        {{-- Impianti Gestiti --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-4 mb-md-5 ">
            <div class="card card-flush h-100" style="background-color: #F1416C;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Impianti Gestiti</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::impianto->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['impianti']['totale'] }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Totali</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['impianti']['attivi'] }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Attivi</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                        <span class="text-white opacity-75">
                            Dismessi: {{ $statistiche['impianti']['dismessi'] }}
                        </span>
                            <span class="text-white opacity-75">
                            {{ $statistiche['impianti']['totale'] > 0 ? round(($statistiche['impianti']['attivi'] / $statistiche['impianti']['totale']) * 100) : 0 }}%
                        </span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="rounded h-8px bg-white" role="progressbar"
                                 style="width: {{ $statistiche['impianti']['totale'] > 0 ? ($statistiche['impianti']['attivi'] / $statistiche['impianti']['totale']) * 100 : 0 }}%;"
                                 aria-valuenow="{{ $statistiche['impianti']['totale'] > 0 ? ($statistiche['impianti']['attivi'] / $statistiche['impianti']['totale']) * 100 : 0 }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Amministratori --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-4 mb-md-5 ">
            <div class="card card-flush h-100" style="background-color: #50CD89;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Amministratori</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::amministratore->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['amministratori']['totale'] }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Totali</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['amministratori']['attivi'] }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Attivi</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                        <span class="text-white opacity-75">
                            Non attivi: {{ $statistiche['amministratori']['totale'] - $statistiche['amministratori']['attivi'] }}
                        </span>
                            <span class="text-white opacity-75">
                            {{ $statistiche['amministratori']['totale'] > 0 ? round(($statistiche['amministratori']['attivi'] / $statistiche['amministratori']['totale']) * 100) : 0 }}%
                        </span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="rounded h-8px bg-white" role="progressbar"
                                 style="width: {{ $statistiche['amministratori']['totale'] > 0 ? ($statistiche['amministratori']['attivi'] / $statistiche['amministratori']['totale']) * 100 : 0 }}%;"
                                 aria-valuenow="{{ $statistiche['amministratori']['totale'] > 0 ? ($statistiche['amministratori']['attivi'] / $statistiche['amministratori']['totale']) * 100 : 0 }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dispositivi --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-4 mb-md-5 ">
            <div class="card card-flush h-100" style="background-color: #F1BC00;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Dispositivi</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::dispositivo_misura->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['dispositivi']['totale'] }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Totali</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['dispositivi']['attivi'] }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Attivi</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                        <span class="text-white opacity-75">
                            Non attivi: {{ $statistiche['dispositivi']['totale'] - $statistiche['dispositivi']['attivi'] }}
                        </span>
                            <span class="text-white opacity-75">
                            {{ $statistiche['dispositivi']['totale'] > 0 ? round(($statistiche['dispositivi']['attivi'] / $statistiche['dispositivi']['totale']) * 100) : 0 }}%
                        </span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="rounded h-8px bg-white" role="progressbar"
                                 style="width: {{ $statistiche['dispositivi']['totale'] > 0 ? ($statistiche['dispositivi']['attivi'] / $statistiche['dispositivi']['totale']) * 100 : 0 }}%;"
                                 aria-valuenow="{{ $statistiche['dispositivi']['totale'] > 0 ? ($statistiche['dispositivi']['attivi'] / $statistiche['dispositivi']['totale']) * 100 : 0 }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5 g-xl-10 mb-5 ">
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
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::impianto->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Nuovo Impianto</span>
                                <span class="text-muted fs-7">Registra nuovo impianto</span>
                            </div>
                        </a>

                        {{-- Nuovo Amministratore --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\AmministratoreController::class, 'create']) }}"
                           class="btn btn-light-success d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::amministratore->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Nuovo Amministratore</span>
                                <span class="text-muted fs-7">Registra amministratore</span>
                            </div>
                        </a>

                        {{-- Dispositivi --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\DispositivoMisuraController::class, 'index']) }}"
                           class="btn btn-light-info d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::dispositivo_misura->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Gestisci Dispositivi</span>
                                <span class="text-muted fs-7">Visualizza e configura</span>
                            </div>
                        </a>

                        {{-- Concentratori --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\ConcentratoreController::class, 'index']) }}"
                           class="btn btn-light-warning d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::concentratore->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Concentratori</span>
                                <span class="text-muted fs-7">Gestisci concentratori</span>
                            </div>
                        </a>

                        {{-- Letture Consumi --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\LetturaConsumoController::class, 'index']) }}"
                           class="btn btn-light-dark d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::storico_letture->render('fs-3') !!}
                            </div>
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
