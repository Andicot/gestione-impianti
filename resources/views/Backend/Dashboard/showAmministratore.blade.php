@extends('Metronic._layout._main')

@section('content')
    {{-- Header Amministratore --}}
    <div class="row g-5 g-xl-10 mb-5 ">
        <div class="col-xl-12">
            <div class="card card-flush" style="background-color: #009EF7;">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h1 class="fs-2hx fw-bold text-white me-2 lh-1">{{ $amministratore->ragione_sociale }}</h1>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Dashboard Amministratore Condominio</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <div class="d-flex align-items-center px-7 pb-5">
                        <div class="symbol symbol-45px me-5">
                        <span class="symbol-label bg-white bg-opacity-20">
                            {!! \App\Enums\IconeEnum::amministratore->render('text-white fs-1') !!}
                        </span>
                        </div>
                        <div>
                            @if($amministratore->nome_referente && $amministratore->cognome_referente)
                                <span class="fs-6 fw-bolder text-white opacity-75 pb-1">{{ $amministratore->nome_referente }} {{ $amministratore->cognome_referente }}</span><br>
                            @endif
                            @if($amministratore->telefono_ufficio)
                                <span class="fs-7 fw-bold text-white opacity-75">{{ $amministratore->telefono_ufficio }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiche Overview --}}
    <div class="row g-5 g-xl-10  ">
        {{-- Condomini Gestiti --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-5 ">
            <div class="card card-flush h-100" style="background-color: #F1416C;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Condomini Gestiti</h3>
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
                            Dismessi: {{ $statistiche['impianti']['totale'] - $statistiche['impianti']['attivi'] }}
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

        {{-- Dispositivi Monitorati --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-5 ">
            <div class="card card-flush h-100" style="background-color: #F1BC00;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Dispositivi Monitorati</h3>
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
        {{-- I Tuoi Condomini --}}
        <div class="col-xl-8">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">I Tuoi Condomini</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Impianti sotto la tua amministrazione</span>
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
                                <th class="ps-4 min-w-200px rounded-start">Nome Condominio</th>
                                <th class="min-w-125px">Stato</th>
                                <th class="min-w-100px">Unità</th>
                                <th class="min-w-125px rounded-end">Ultima Attività</th>
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
                                            <span class="badge badge-light-success">Attivo</span>
                                        @else
                                            <span class="badge badge-light-danger">Dismesso</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-gray-800 fw-bold">{{ $impianto->unita_immobiliari_count ?? 0 }}</span>
                                        <span class="text-muted fs-7">unità</span>
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
                                        Nessun condominio gestito
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
                        {{-- Gestisci Impianti --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\ImpiantoController::class, 'index']) }}"
                           class="btn btn-light-primary d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::impianto->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Gestisci Impianti</span>
                                <span class="text-muted fs-7">Visualizza i tuoi condomini</span>
                            </div>
                        </a>

                        {{-- Unità Immobiliari --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\UnitaImmobiliareController::class, 'index']) }}"
                           class="btn btn-light-success d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                <i class="fas fa-home fs-3"></i>
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Unità Immobiliari</span>
                                <span class="text-muted fs-7">Gestisci appartamenti</span>
                            </div>
                        </a>

                        {{-- Letture Consumi --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\LetturaConsumoController::class, 'index']) }}"
                           class="btn btn-light-info d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::storico_letture->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Letture Consumi</span>
                                <span class="text-muted fs-7">Monitora consumi</span>
                            </div>
                        </a>

                        {{-- Documenti --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\DocumentoController::class, 'index']) }}"
                           class="btn btn-light-warning d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::documento->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Documenti</span>
                                <span class="text-muted fs-7">Gestisci documenti</span>
                            </div>
                        </a>

                        {{-- Comunicazioni --}}
                        <a href="{{ action([\App\Http\Controllers\TicketController::class, 'index']) }}"
                           class="btn btn-light-dark d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::ticket->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Comunicazioni</span>
                                <span class="text-muted fs-7">Gestisci ticket</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riepilogo Dispositivi per Tipo --}}
    <div class="row g-5 g-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Riepilogo Dispositivi</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Panoramica dei dispositivi per tipologia</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="row g-3">
                        {{-- UDR --}}
                        <div class="col-md-6">
                            <div class="card bg-light-primary">
                                <div class="card-body p-5">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                        <span class="symbol-label bg-primary">
                                            <i class="fas fa-thermometer-half text-white fs-4"></i>
                                        </span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-gray-800 fs-6">Dispositivi UDR</div>
                                            <div class="text-muted fs-7">Ripartitori per riscaldamento</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="fs-2hx fw-bold text-primary">{{ $statistiche['dispositivi']['per_tipo']['udr'] ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contatori ACS --}}
                        <div class="col-md-6">
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
                                            <div class="text-muted fs-7">Monitoraggio acqua calda sanitaria</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="fs-2hx fw-bold text-info">{{ $statistiche['dispositivi']['per_tipo']['contatore_acs'] ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
