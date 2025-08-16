@extends('Metronic._layout._main')

@section('content')
    {{-- Header Amministratore --}}
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end" style="background-color: #009EF7;background-image:url('assets/media/patterns/vector-2.png')">
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
                            <i class="fas fa-user-tie text-white fs-1"></i>
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
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        {{-- Impianti Gestiti --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-5 mb-xl-10">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10" style="background-color: #F1416C;background-image:url('assets/media/patterns/vector-1.png')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['impianti']['totale'] }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Condomini Gestiti</span>
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

        {{-- Dispositivi Monitorati --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-5 mb-xl-10">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10" style="background-color: #F1BC00;background-image:url('assets/media/patterns/vector-4.png')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['dispositivi']['totale'] }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Dispositivi Monitorati</span>
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
                            <i class="fas fa-building fs-3 me-3"></i>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Gestisci Impianti</span>
                                <span class="text-muted fs-7">Visualizza i tuoi condomini</span>
                            </div>
                        </a>

                        {{-- Unità Immobiliari --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\UnitaImmobiliareController::class, 'index']) }}"
                           class="btn btn-light-success d-flex align-items-center p-3">
                            <i class="fas fa-home fs-3 me-3"></i>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Unità Immobiliari</span>
                                <span class="text-muted fs-7">Gestisci appartamenti</span>
                            </div>
                        </a>

                        {{-- Letture Consumi --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\LetturaConsumoController::class, 'index']) }}"
                           class="btn btn-light-info d-flex align-items-center p-3">
                            <i class="fas fa-chart-line fs-3 me-3"></i>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Letture Consumi</span>
                                <span class="text-muted fs-7">Monitora consumi</span>
                            </div>
                        </a>

                        {{-- Documenti --}}
                        <a href="{{ action([\App\Http\Controllers\Backend\DocumentoController::class, 'index']) }}"
                           class="btn btn-light-warning d-flex align-items-center p-3">
                            <i class="fas fa-file-alt fs-3 me-3"></i>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Documenti</span>
                                <span class="text-muted fs-7">Gestisci documenti</span>
                            </div>
                        </a>

                        {{-- Comunicazioni --}}
                        @if(class_exists(\App\Http\Controllers\TicketController::class))
                            <a href="{{ action([\App\Http\Controllers\TicketController::class, 'index']) }}"
                               class="btn btn-light-dark d-flex align-items-center p-3">
                                <i class="fas fa-comments fs-3 me-3"></i>
                                <div class="d-flex flex-column align-items-start">
                                    <span class="fw-bold">Comunicazioni</span>
                                    <span class="text-muted fs-7">Gestisci ticket</span>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riepilogo Mensile --}}
    <div class="row g-5 g-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Riepilogo Attività</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Panoramica delle attività recenti</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="row g-3">
                        {{-- Statistiche Dispositivi per Tipo --}}
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
                                            <div class="fw-bold text-gray-800 fs-6">Dispositivi Riscaldamento</div>
                                            <div class="text-muted fs-7">UDR attivi per il monitoraggio</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="fs-2hx fw-bold text-primary">{{ $statistiche['dispositivi']['per_tipo']['udr'] ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                            <div class="text-muted fs-7">Monitoraggio acqua calda</div>
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
