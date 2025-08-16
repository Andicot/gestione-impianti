@extends('Metronic._layout._main')

@section('content')
    {{-- Header Condomino --}}
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end" style="background-color: #17C653;background-image:url('assets/media/patterns/vector-3.png')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h1 class="fs-2hx fw-bold text-white me-2 lh-1">Benvenuto</h1>
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
                            <span class="fs-7 fw-bold text-white opacity-75">Accesso ai tuoi consumi e documenti</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Azioni Principali --}}
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        {{-- I Miei Consumi --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <a href="#" class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10 text-decoration-none" style="background-color: #F1416C;background-image:url('assets/media/patterns/vector-1.png')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">I Miei</span>
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Consumi</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <span class="fs-6 fw-bolder text-white opacity-75 pb-1 px-7">Visualizza dettagli</span>
                    <div class="d-flex align-items-center px-7">
                        <div class="symbol symbol-30px me-5 mb-8">
                        <span class="symbol-label">
                            <i class="fas fa-chart-line text-white fs-1"></i>
                        </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Le Mie Bollette --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <a href="#" class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10 text-decoration-none" style="background-color: #7239EA;background-image:url('assets/media/patterns/vector-2.png')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Le Mie</span>
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Bollette</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <span class="fs-6 fw-bolder text-white opacity-75 pb-1 px-7">Scarica e visualizza</span>
                    <div class="d-flex align-items-center px-7">
                        <div class="symbol symbol-30px me-5 mb-8">
                        <span class="symbol-label">
                            <i class="fas fa-file-invoice text-white fs-1"></i>
                        </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Documenti --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <a href="{{ action([\App\Http\Controllers\Backend\DocumentoController::class, 'index']) }}" class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10 text-decoration-none" style="background-color: #50CD89;background-image:url('assets/media/patterns/vector-3.png')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">I Miei</span>
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Documenti</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <span class="fs-6 fw-bolder text-white opacity-75 pb-1 px-7">Accedi ai documenti</span>
                    <div class="d-flex align-items-center px-7">
                        <div class="symbol symbol-30px me-5 mb-8">
                        <span class="symbol-label">
                            <i class="fas fa-file-alt text-white fs-1"></i>
                        </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Comunicazioni --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <a href="{{ action([\App\Http\Controllers\TicketController::class, 'index']) }}" class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10 text-decoration-none" style="background-color: #F1BC00;background-image:url('assets/media/patterns/vector-4.png')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Le Mie</span>
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Comunicazioni</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-end pe-0">
                    <span class="fs-6 fw-bolder text-white opacity-75 pb-1 px-7">Messaggi e ticket</span>
                    <div class="d-flex align-items-center px-7">
                        <div class="symbol symbol-30px me-5 mb-8">
                        <span class="symbol-label">
                            <i class="fas fa-comments text-white fs-1"></i>
                        </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Contenuto Principale --}}
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        {{-- Informazioni Unità --}}
        <div class="col-xl-6">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Le Mie Unità Immobiliari</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Appartamenti e box di tua proprietà</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    {{-- Qui andrà la lista delle unità immobiliari del condomino --}}
                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-semibold">
                                <h4 class="text-gray-900 fw-bold">Funzionalità in Sviluppo</h4>
                                <div class="fs-6 text-gray-700">
                                    Le informazioni sui tuoi appartamenti e box saranno presto disponibili qui.
                                    <br>
                                    Potrai visualizzare:
                                    <ul class="mt-3">
                                        <li>Dettagli delle tue unità immobiliari</li>
                                        <li>Millesimi di proprietà</li>
                                        <li>Dispositivi installati</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ultime Attività --}}
        <div class="col-xl-6">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Ultime Attività</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Novità e aggiornamenti per te</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    {{-- Timeline delle attività --}}
                    <div class="timeline-label">
                        <div class="timeline-item">
                            <div class="timeline-label fw-bold text-gray-800 fs-6">Oggi</div>
                            <div class="timeline-badge">
                                <i class="fas fa-genderless text-success fs-1"></i>
                            </div>
                            <div class="timeline-content d-flex">
                                <span class="fw-bold text-gray-800 ps-3">Benvenuto nella nuova dashboard!</span>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-label fw-bold text-gray-800 fs-6"></div>
                            <div class="timeline-badge">
                                <i class="fas fa-genderless text-warning fs-1"></i>
                            </div>
                            <div class="timeline-content d-flex">
                                <span class="fw-bold text-gray-800 ps-3">Presto potrai visualizzare i tuoi consumi in tempo reale</span>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-label fw-bold text-gray-800 fs-6"></div>
                            <div class="timeline-badge">
                                <i class="fas fa-genderless text-info fs-1"></i>
                            </div>
                            <div class="timeline-content d-flex">
                                <span class="fw-bold text-gray-800 ps-3">Sistema di comunicazione attivo per segnalazioni</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                        Scopri come utilizzare il sistema di monitoraggio dei consumi
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

@endsection
