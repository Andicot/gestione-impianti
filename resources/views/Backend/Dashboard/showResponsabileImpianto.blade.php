@extends('Metronic._layout._main')

@section('content')
    {{-- Header Responsabile Impianto --}}
    <div class="row g-5 g-xl-10 mb-5 ">
        <div class="col-xl-12">
            <div class="card card-flush" style="background-color: #8950FC;">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h1 class="fs-2hx fw-bold text-white me-2 lh-1">{{ $responsabile->nome }} {{ $responsabile->cognome }}</h1>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Dashboard Responsabile Tecnico Impianti</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <div class="d-flex align-items-center px-7 pb-5">
                        <div class="symbol symbol-45px me-5">
                        <span class="symbol-label bg-white bg-opacity-20">
                            {!! \App\Enums\IconeEnum::responsabile_impianto->render('text-white fs-1') !!}
                        </span>
                        </div>
                        <div>
                            @if($responsabile->email)
                                <span class="fs-6 fw-bolder text-white opacity-75 pb-1">{{ $responsabile->email }}</span><br>
                            @endif
                            @if($responsabile->telefono)
                                <span class="fs-7 fw-bold text-white opacity-75">Tel: {{ $responsabile->telefono }}</span>
                            @endif
                            @if($responsabile->cellulare)
                                <span class="fs-7 fw-bold text-white opacity-75 ms-3">Cell: {{ $responsabile->cellulare }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiche Tecniche --}}
    <div class="row g-5 g-xl-10  ">
        {{-- Impianti Supervisionati --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <div class="card card-flush h-100" style="background-color: #3699FF;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Impianti Supervisionati</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::impianto->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $impianti_gestiti->count() }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Totali</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $impianti_gestiti->where('stato_impianto', 'attivo')->count() }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Attivi</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                        <span class="text-white opacity-75">
                            Dismessi: {{ $impianti_gestiti->where('stato_impianto', 'dismesso')->count() }}
                        </span>
                            <span class="text-white opacity-75">
                            {{ $impianti_gestiti->count() > 0 ? round(($impianti_gestiti->where('stato_impianto', 'attivo')->count() / $impianti_gestiti->count()) * 100) : 0 }}%
                        </span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="rounded h-8px bg-white" role="progressbar"
                                 style="width: {{ $impianti_gestiti->count() > 0 ? ($impianti_gestiti->where('stato_impianto', 'attivo')->count() / $impianti_gestiti->count()) * 100 : 0 }}%;"
                                 aria-valuenow="{{ $impianti_gestiti->count() > 0 ? ($impianti_gestiti->where('stato_impianto', 'attivo')->count() / $impianti_gestiti->count()) * 100 : 0 }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dispositivi sotto controllo --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <div class="card card-flush h-100" style="background-color: #1BC5BD;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Dispositivi Controllo</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::dispositivo_misura->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_dispositivi['totale'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Totali</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_dispositivi['attivi'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Attivi</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                        <span class="text-white opacity-75">
                            Malfunzionanti: {{ ($statistiche_dispositivi['totale'] ?? 0) - ($statistiche_dispositivi['attivi'] ?? 0) }}
                        </span>
                            <span class="text-white opacity-75">
                            {{ ($statistiche_dispositivi['totale'] ?? 0) > 0 ? round((($statistiche_dispositivi['attivi'] ?? 0) / $statistiche_dispositivi['totale']) * 100) : 0 }}%
                        </span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="rounded h-8px bg-white" role="progressbar"
                                 style="width: {{ ($statistiche_dispositivi['totale'] ?? 0) > 0 ? (($statistiche_dispositivi['attivi'] ?? 0) / $statistiche_dispositivi['totale']) * 100 : 0 }}%;"
                                 aria-valuenow="{{ ($statistiche_dispositivi['totale'] ?? 0) > 0 ? (($statistiche_dispositivi['attivi'] ?? 0) / $statistiche_dispositivi['totale']) * 100 : 0 }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Concentratori Gestiti --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <div class="card card-flush h-100" style="background-color: #F64E60;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Concentratori</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::concentratore->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_concentratori['totale'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Totali</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_concentratori['attivi'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Online</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                        <span class="text-white opacity-75">
                            Offline: {{ ($statistiche_concentratori['totale'] ?? 0) - ($statistiche_concentratori['attivi'] ?? 0) }}
                        </span>
                            <span class="text-white opacity-75">
                            {{ ($statistiche_concentratori['totale'] ?? 0) > 0 ? round((($statistiche_concentratori['attivi'] ?? 0) / $statistiche_concentratori['totale']) * 100) : 0 }}%
                        </span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="rounded h-8px bg-white" role="progressbar"
                                 style="width: {{ ($statistiche_concentratori['totale'] ?? 0) > 0 ? (($statistiche_concentratori['attivi'] ?? 0) / $statistiche_concentratori['totale']) * 100 : 0 }}%;"
                                 aria-valuenow="{{ ($statistiche_concentratori['totale'] ?? 0) > 0 ? (($statistiche_concentratori['attivi'] ?? 0) / $statistiche_concentratori['totale']) * 100 : 0 }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Anomalie da Verificare --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <div class="card card-flush h-100" style="background-color: #FFA800;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Anomalie</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::anomalia->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_anomalie['totale'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Rilevate</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_anomalie['aperte'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Da Verificare</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                        <span class="text-white opacity-75">
                            Critiche: {{ $statistiche_anomalie['critiche'] ?? 0 }}
                        </span>
                            @if(($statistiche_anomalie['aperte'] ?? 0) > 0)
                                <span class="text-white opacity-75 badge badge-danger">
                                Attenzione!
                            </span>
                            @else
                                <span class="text-white opacity-75">
                                OK
                            </span>
                            @endif
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="rounded h-8px bg-danger" role="progressbar"
                                 style="width: {{ ($statistiche_anomalie['totale'] ?? 0) > 0 ? (($statistiche_anomalie['aperte'] ?? 0) / $statistiche_anomalie['totale']) * 100 : 0 }}%;"
                                 aria-valuenow="{{ ($statistiche_anomalie['totale'] ?? 0) > 0 ? (($statistiche_anomalie['aperte'] ?? 0) / $statistiche_anomalie['totale']) * 100 : 0 }}"
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
        {{-- Impianti Sotto Supervisione --}}
        <div class="col-xl-8">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Impianti Sotto Supervisione</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Impianti di cui sei responsabile tecnico</span>
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
                                <th class="min-w-125px">Stato Tecnico</th>
                                <th class="min-w-100px">Dispositivi</th>
                                <th class="min-w-125px rounded-end">Ultima Verifica</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($impianti_gestiti as $impianto)
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
                                            <span class="badge badge-light-success">Operativo</span>
                                        @else
                                            <span class="badge badge-light-danger">Fuori Servizio</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-gray-800 fw-bold">{{ $impianto->dispositivi_count ?? 0 }}</span>
                                        <span class="text-muted fs-7">dispositivi</span>
                                    </td>
                                    <td>
                                    <span class="text-muted fw-semibold d-block fs-7">
                                        {{ $impianto->updated_at->format('d/m/Y') }}
                                    </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Nessun impianto assegnato
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Azioni Tecniche Rapide --}}
        <div class="col-xl-4">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Azioni Tecniche</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Operazioni tecniche frequenti</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="d-flex flex-column gap-3">
                        {{-- Verifica Dispositivi --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\DispositivoMisuraController::class, 'index']) }}"
                           class="btn btn-light-primary d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::dispositivo_misura->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Verifica Dispositivi</span>
                                <span class="text-muted fs-7">Controlla stato e funzionamento</span>
                            </div>
                        </a>

                        {{-- Gestisci Concentratori --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\ConcentratoreController::class, 'index']) }}"
                           class="btn btn-light-success d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::concentratore->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Gestisci Concentratori</span>
                                <span class="text-muted fs-7">Configurazione e monitoraggio</span>
                            </div>
                        </a>

                        {{-- Anomalie --}}
                        <a href="#" class="btn btn-light-warning d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::anomalia->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Gestisci Anomalie</span>
                                <span class="text-muted fs-7">Verifica e risoluzione</span>
                            </div>
                        </a>

                        {{-- Manutenzioni --}}
                        <a href="#" class="btn btn-light-info d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::manutenzione->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Programma Manutenzioni</span>
                                <span class="text-muted fs-7">Pianifica interventi</span>
                            </div>
                        </a>

                        {{-- Storico Letture --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\LetturaConsumoController::class, 'index']) }}"
                           class="btn btn-light-dark d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::storico_letture->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Storico Letture</span>
                                <span class="text-muted fs-7">Analisi dati tecnici</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@if(false)
    {{-- Panoramica Tecnica --}}
    <div class="row g-5 g-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Panoramica Tecnica</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Stato generale degli impianti supervisionati</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="row g-3">
                        {{-- Stato Dispositivi UDR --}}
                        <div class="col-md-4">
                            <div class="card bg-light-primary">
                                <div class="card-body p-5">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                        <span class="symbol-label bg-primary">
                                            <i class="fas fa-thermometer-half text-white fs-4"></i>
                                        </span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-gray-800 fs-6">UDR Funzionanti</div>
                                            <div class="text-muted fs-7">Ripartitori di calore attivi</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="fs-2hx fw-bold text-primary">{{ $statistiche_dispositivi['udr_attivi'] ?? 0 }}</div>
                                        <div class="text-muted fs-7">su {{ $statistiche_dispositivi['udr_totali'] ?? 0 }} totali</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contatori ACS --}}
                        <div class="col-md-4">
                            <div class="card bg-light-info">
                                <div class="card-body p-5">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                        <span class="symbol-label bg-info">
                                            <i class="fas fa-tint text-white fs-4"></i>
                                        </span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-gray-800 fs-6">Contatori ACS</div>
                                            <div class="text-muted fs-7">Monitoraggio acqua calda</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="fs-2hx fw-bold text-info">{{ $statistiche_dispositivi['acs_attivi'] ?? 0 }}</div>
                                        <div class="text-muted fs-7">su {{ $statistiche_dispositivi['acs_totali'] ?? 0 }} totali</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Comunicazioni Concentratori --}}
                        <div class="col-md-4">
                            <div class="card bg-light-success">
                                <div class="card-body p-5">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                        <span class="symbol-label bg-success">
                                            <i class="fas fa-wifi text-white fs-4"></i>
                                        </span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-gray-800 fs-6">Comunicazioni</div>
                                            <div class="text-muted fs-7">Ultima ricezione dati</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="fs-2hx fw-bold text-success">{{ $ore_ultima_comunicazione ?? '--' }}</div>
                                        <div class="text-muted fs-7">ore fa (media)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

@endsection
