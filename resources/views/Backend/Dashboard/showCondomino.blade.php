@extends('Metronic._layout._main')

@section('content')
    {{-- Header Condomino --}}
    <div class="row g-5 g-xl-10 mb-5 ">
        <div class="col-xl-12">
            <div class="card card-flush" style="background-color: #17C653;">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h1 class="fs-2hx fw-bold text-white me-2 lh-1">Benvenuto {{ Auth::user()->nome }}</h1>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">{{ Auth::user()->nome }} {{ Auth::user()->cognome }}</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <div class="d-flex align-items-center px-7 pb-5">
                        <div class="symbol symbol-45px me-5">
                        <span class="symbol-label bg-white bg-opacity-20">
                            <i class="fas fa-user text-white fs-1"></i>
                        </span>
                        </div>
                        <div>
                            <span class="fs-6 fw-bolder text-white opacity-75 pb-1">Dashboard Condomino</span><br>
                            @if(Auth::user()->email)
                                <span class="fs-7 fw-bold text-white opacity-75">{{ Auth::user()->email }}</span>
                            @endif
                            @if(Auth::user()->telefono)
                                <span class="fs-7 fw-bold text-white opacity-75 ms-3">{{ Auth::user()->telefono }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Azioni Principali --}}
    <div class="row g-5 g-xl-10  ">
        {{-- I Miei Consumi --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <a href="#" class="card card-flush h-100 text-decoration-none" style="background-color: #F1416C;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">I Miei Consumi</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::miei_consumi->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_consumi['letture_totali'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Letture Disponibili</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_consumi['ultimo_mese'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Ultimo Mese</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                        <span class="text-white opacity-75">
                            Riscaldamento + ACS
                        </span>
                            <span class="text-white opacity-75">
                            Visualizza Dettagli →
                        </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Le Mie Bollette --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <a href="#" class="card card-flush h-100 text-decoration-none" style="background-color: #7239EA;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Le Mie Bollette</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::mie_bollette->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_bollette['totali'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Bollette Totali</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_bollette['non_pagate'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Da Pagare</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                        <span class="text-white opacity-75">
                            Ultima: {{ $statistiche_bollette['ultima_data'] ?? 'N/A' }}
                        </span>
                            <span class="text-white opacity-75">
                            Scarica PDF →
                        </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Documenti --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <a href="{{ action([\App\Http\Controllers\Backend\DocumentoController::class, 'index']) }}" class="card card-flush h-100 text-decoration-none" style="background-color: #50CD89;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">I Miei Documenti</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::documento->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_documenti['totali'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Documenti</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_documenti['nuovi'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Nuovi</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                        <span class="text-white opacity-75">
                            Pubblici e privati
                        </span>
                            <span class="text-white opacity-75">
                            Accedi →
                        </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Comunicazioni --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <a href="{{ action([\App\Http\Controllers\TicketController::class, 'index']) }}" class="card card-flush h-100 text-decoration-none" style="background-color: #F1BC00;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Le Mie Comunicazioni</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::comunicazione->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_comunicazioni['totali'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Ticket Totali</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche_comunicazioni['aperti'] ?? 0 }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Aperti</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                        <span class="text-white opacity-75">
                            Segnalazioni e richieste
                        </span>
                            <span class="text-white opacity-75">
                            Gestisci →
                        </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Contenuto Principale --}}
    <div class="row g-5 g-xl-10 mb-5 ">
        {{-- Le Mie Unità Immobiliari --}}
        <div class="col-xl-8">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Le Mie Unità Immobiliari</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Appartamenti e box di tua proprietà</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    @if(isset($unita_immobiliari) && $unita_immobiliari->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th class="ps-4 min-w-200px rounded-start">Unità</th>
                                    <th class="min-w-125px">Condominio</th>
                                    <th class="min-w-100px">Tipologia</th>
                                    <th class="min-w-100px">Millesimi</th>
                                    <th class="min-w-125px rounded-end">Dispositivi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($unita_immobiliari as $unita)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex flex-column">
                                            <span class="text-dark fw-bold d-block mb-1 fs-6">
                                                @if($unita->scala)Scala {{ $unita->scala }} - @endif
                                                Piano {{ $unita->piano }} - Interno {{ $unita->interno }}
                                            </span>
                                                @if($unita->nominativo_unita)
                                                    <span class="text-muted fw-semibold d-block fs-7">{{ $unita->nominativo_unita }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold fs-7">{{ $unita->impianto->nome_impianto }}</span>
                                                <span class="text-muted fs-7">{{ $unita->impianto->indirizzo }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-primary">{{ ucfirst($unita->tipologia) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold fs-7">Risc: {{ $unita->millesimi_riscaldamento }}</span>
                                                <span class="text-muted fs-7">ACS: {{ $unita->millesimi_acs }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold">{{ $unita->dispositivi_count ?? 0 }}</span>
                                            <span class="text-muted fs-7">dispositivi</span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <h4 class="text-gray-900 fw-bold">Nessuna Unità Immobiliare</h4>
                                    <div class="fs-6 text-gray-700">
                                        Non risultano unità immobiliari associate al tuo account.
                                        <br>
                                        Contatta l'amministratore del condominio per verificare la configurazione.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
                        {{-- Visualizza Consumi --}}
                        <a href="#" class="btn btn-light-primary d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::miei_consumi->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Visualizza Consumi</span>
                                <span class="text-muted fs-7">Storico e grafici dettagliati</span>
                            </div>
                        </a>

                        {{-- Scarica Bollette --}}
                        <a href="#" class="btn btn-light-success d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::mie_bollette->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Scarica Bollette</span>
                                <span class="text-muted fs-7">PDF delle bollette disponibili</span>
                            </div>
                        </a>

                        {{-- Nuovo Ticket --}}
                        <a href="{{ action([\App\Http\Controllers\TicketController::class, 'create']) }}"
                           class="btn btn-light-warning d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::ticket->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Apri Segnalazione</span>
                                <span class="text-muted fs-7">Nuova richiesta o problema</span>
                            </div>
                        </a>

                        {{-- Storico Letture --}}
                        <a href="#" class="btn btn-light-info d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::storico_letture->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Storico Letture</span>
                                <span class="text-muted fs-7">Cronologia dei consumi</span>
                            </div>
                        </a>

                        {{-- Grafici Consumi --}}
                        <a href="#" class="btn btn-light-dark d-flex align-items-center p-3">
                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px;">
                                {!! \App\Enums\IconeEnum::grafici->render('fs-3') !!}
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <span class="fw-bold">Grafici Consumi</span>
                                <span class="text-muted fs-7">Analisi visiva dei dati</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@if(false)
    {{-- Informazioni Utili --}}
    <div class="row g-5 g-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Informazioni Utili</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Guide e contatti per l'utilizzo del sistema</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card bg-light-info">
                                <div class="card-body text-center p-5">
                                    <i class="fas fa-question-circle text-info fs-2x mb-3"></i>
                                    <h5 class="fw-bold text-gray-800">Come Funziona</h5>
                                    <p class="text-muted fs-7">
                                        Scopri come leggere i tuoi consumi e gestire le bollette
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-light-success">
                                <div class="card-body text-center p-5">
                                    <i class="fas fa-phone text-success fs-2x mb-3"></i>
                                    <h5 class="fw-bold text-gray-800">Supporto</h5>
                                    <p class="text-muted fs-7">
                                        Contatta l'amministratore per qualsiasi problema
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-light-warning">
                                <div class="card-body text-center p-5">
                                    <i class="fas fa-bell text-warning fs-2x mb-3"></i>
                                    <h5 class="fw-bold text-gray-800">Novità</h5>
                                    <p class="text-muted fs-7">
                                        Rimani aggiornato sulle nuove funzionalità
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
