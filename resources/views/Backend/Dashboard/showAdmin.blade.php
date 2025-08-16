@extends('Metronic._layout._main')

@section('content')
    {{-- Overview Cards --}}
    <div class="row g-5 g-xl-10  ">
        {{-- Impianti --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <div class="card card-flush h-100 bgi-no-repeat bgi-size-contain bgi-position-x-end" style="background-color: #F1416C;background-image:url('assets_backend/media/patterns/vector-1.png')">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Impianti</h3>
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

        {{-- Aziende di Servizio --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <div class="card card-flush h-100 bgi-no-repeat bgi-size-contain bgi-position-x-end" style="background-color: #7239EA;">
                <div class="card-header pb-1 px-4">
                    <h3 class="card-title text-gray-100 mb-0">Aziende di Servizio</h3>
                    <div class="card-toolbar">
                        {!! \App\Enums\IconeEnum::azienda_servizio->render('fs-2','text-gray-100') !!}
                    </div>
                </div>
                <div class="card-body pt-1 pb-4 px-4">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['aziende_servizio']['totale'] }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Totali</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column text-end">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistiche['aziende_servizio']['attive'] }}</span>
                            <div class="d-flex justify-content-between">
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Attive</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-column mt-2 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 w-100 mt-auto mb-1">
                    <span class="text-white opacity-75">
                        Non attive: {{ $statistiche['aziende_servizio']['non_attive'] }}
                    </span>
                            <span class="text-white opacity-75">
                        {{ $statistiche['aziende_servizio']['totale'] > 0 ? round(($statistiche['aziende_servizio']['attive'] / $statistiche['aziende_servizio']['totale']) * 100) : 0 }}%
                    </span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="rounded h-8px bg-white" role="progressbar"
                                 style="width: {{ $statistiche['aziende_servizio']['totale'] > 0 ? ($statistiche['aziende_servizio']['attive'] / $statistiche['aziende_servizio']['totale']) * 100 : 0 }}%;"
                                 aria-valuenow="{{ $statistiche['aziende_servizio']['totale'] > 0 ? ($statistiche['aziende_servizio']['attive'] / $statistiche['aziende_servizio']['totale']) * 100 : 0 }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Amministratori --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <div class="card card-flush h-100 bgi-no-repeat bgi-size-contain bgi-position-x-end" style="background-color: #50CD89;background-image:url('assets_backend/media/patterns/vector-3.png')">
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
                        Non attivi: {{ $statistiche['amministratori']['non_attivi'] }}
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
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 ">
            <div class="card card-flush h-100 bgi-no-repeat bgi-size-contain bgi-position-x-end" style="background-color: #F1BC00;background-image:url('assets_backend/media/patterns/vector-4.png')">
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
        {{-- Tabella Ultimi Impianti Creati --}}
        <div class="col-xl-6">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Ultimi Impianti Creati</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Impianti registrati di recente</span>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ action([\App\Http\Controllers\Backend\ImpiantoController::class, 'index']) }}" class="btn btn-sm btn-light">
                            Vedi Tutti
                        </a>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                            <thead>
                            <tr class="border-0">
                                <th class="p-0 w-50px"></th>
                                <th class="p-0 min-w-150px"></th>
                                <th class="p-0 min-w-100px"></th>
                                <th class="p-0 min-w-40px"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($ultimi_impianti as $impianto)
                                <tr>
                                    <td>
                                        <div class="symbol symbol-45px me-2">
                                        <span class="symbol-label">
                                            <i class="fas fa-building text-primary fs-3"></i>
                                        </span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ action([\App\Http\Controllers\Backend\ImpiantoController::class, 'show'], $impianto->id) }}"
                                           class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                            {{ $impianto->nome_impianto }}
                                        </a>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">
                                        {{ $impianto->indirizzo }}
                                    </span>
                                    </td>
                                    <td class="text-end">
                                        @if($impianto->stato_impianto === 'attivo')
                                            <span class="badge badge-light-success">Attivo</span>
                                        @else
                                            <span class="badge badge-light-danger">Dismesso</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                    <span class="text-muted fw-semibold d-block fs-7">
                                        {{ $impianto->created_at->format('d/m/Y') }}
                                    </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Nessun impianto registrato
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Distribuzione per Tipologia --}}
        <div class="col-xl-6">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Distribuzione Impianti</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Per tipologia e stato</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    {{-- Tipologie --}}
                    <div class="mb-7">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fw-semibold fs-6 text-gray-800 flex-1">Per Tipologia</span>
                        </div>

                        @foreach($statistiche['impianti']['per_tipologia'] as $tipologia => $count)
                            <div class="d-flex align-items-center mb-3">
                                <span class="fw-semibold fs-6 text-gray-800 flex-1">{{ ucfirst($tipologia) }}</span>
                                <span class="fw-bolder fs-6 text-gray-800 px-2">{{ $count }}</span>
                                <div class="w-100px">
                                    <div class="progress h-6px w-100">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                             style="width: {{ $statistiche['impianti']['totale'] > 0 ? ($count / $statistiche['impianti']['totale']) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Stati --}}
                    <div class="separator my-4"></div>
                    <div class="mb-0">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fw-semibold fs-6 text-gray-800 flex-1">Per Stato</span>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <span class="fw-semibold fs-6 text-gray-800 flex-1">Attivi</span>
                            <span class="fw-bolder fs-6 text-success px-2">{{ $statistiche['impianti']['attivi'] }}</span>
                            <div class="w-100px">
                                <div class="progress h-6px w-100">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: {{ $statistiche['impianti']['totale'] > 0 ? ($statistiche['impianti']['attivi'] / $statistiche['impianti']['totale']) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <span class="fw-semibold fs-6 text-gray-800 flex-1">Dismessi</span>
                            <span class="fw-bolder fs-6 text-danger px-2">{{ $statistiche['impianti']['dismessi'] }}</span>
                            <div class="w-100px">
                                <div class="progress h-6px w-100">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                         style="width: {{ $statistiche['impianti']['totale'] > 0 ? ($statistiche['impianti']['dismessi'] / $statistiche['impianti']['totale']) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Azioni Rapide --}}
    <div class="row g-5 g-xl-10 mb-5 ">
        <div class="col-xl-12">
            <div class="card card-flush">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold text-dark">Azioni Rapide</h3>
                        <span class="text-muted pt-1 fw-semibold fs-6">Accesso veloce alle funzioni principali</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="row g-3">
                        {{-- Nuova Azienda --}}
                        <div class="col-md-3">
                            <a href="{{ action([\App\Http\Controllers\Backend\AziendaServizioController::class, 'create']) }}"
                               class="btn btn-light-success w-100 d-flex flex-column align-items-center p-4">
                                {!! \App\Enums\IconeEnum::azienda_servizio->render('fs-2','mb-2') !!}
                                <span class="fw-bold">Nuova Azienda</span>
                                <span class="text-muted fs-7">Registra azienda di servizio</span>
                            </a>
                        </div>

                        {{-- Nuovo Impianto --}}
                        <div class="col-md-3">
                            <a href="{{ action([\App\Http\Controllers\Backend\ImpiantoController::class, 'create']) }}"
                               class="btn btn-light-primary w-100 d-flex flex-column align-items-center p-4">
                                {!! \App\Enums\IconeEnum::impianto->render('fs-2','mb-2') !!}
                                <span class="fw-bold">Nuovo Impianto</span>
                                <span class="text-muted fs-7">Registra nuovo impianto</span>
                            </a>
                        </div>



                        {{-- Nuovo Amministratore --}}
                        <div class="col-md-3">
                            <a href="{{ action([\App\Http\Controllers\Backend\AmministratoreController::class, 'create']) }}"
                               class="btn btn-light-info w-100 d-flex flex-column align-items-center p-4">
                                {!! \App\Enums\IconeEnum::amministratore->render('fs-2','mb-2') !!}
                                <span class="fw-bold">Nuovo Amministratore</span>
                                <span class="text-muted fs-7">Registra amministratore</span>
                            </a>
                        </div>

                        {{-- Visualizza Reports --}}
                        <div class="col-md-3">
                            <a href="{{ action([\App\Http\Controllers\Backend\RegistriController::class, 'index'], 'impianti') }}"
                               class="btn btn-light-warning w-100 d-flex flex-column align-items-center p-4">
                                {!! \App\Enums\IconeEnum::ticket->render('fs-2','mb-2') !!}
                                <span class="fw-bold">Reports</span>
                                <span class="text-muted fs-7">Visualizza registri</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ultimi Ticket / Comunicazioni --}}
    @if(isset($ultimi_tickets) && $ultimi_tickets && $ultimi_tickets->count() > 0)
        <div class="row g-5 g-xl-10">
            <div class="col-xl-12">
                <div class="card card-flush">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <h3 class="fw-bold text-dark">Ultime Comunicazioni</h3>
                            <span class="text-muted pt-1 fw-semibold fs-6">Ticket e comunicazioni recenti</span>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ action([\App\Http\Controllers\TicketController::class, 'index']) }}" class="btn btn-sm btn-light">
                                Vedi Tutti
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th class="ps-4 min-w-200px rounded-start">Titolo</th>
                                    <th class="min-w-125px">Stato</th>
                                    <th class="min-w-125px">Priorità</th>
                                    <th class="min-w-125px">Utente</th>
                                    <th class="min-w-125px rounded-end">Data</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($ultimi_tickets as $ticket)
                                    <tr>
                                        <td class="ps-4">
                                            <a href="{{ action([\App\Http\Controllers\TicketController::class, 'show'], $ticket->id) }}"
                                               class="text-dark fw-bold text-hover-primary d-block mb-1 fs-6">
                                                {{ $ticket->titolo }}
                                            </a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">
                                        {{ Str::limit($ticket->descrizione, 50) }}
                                    </span>
                                        </td>
                                        <td>
                                            @switch($ticket->stato)
                                                @case('aperto')
                                                    <span class="badge badge-light-warning">Aperto</span>
                                                    @break
                                                @case('in_lavorazione')
                                                    <span class="badge badge-light-primary">In Lavorazione</span>
                                                    @break
                                                @case('risolto')
                                                    <span class="badge badge-light-success">Risolto</span>
                                                    @break
                                                @case('chiuso')
                                                    <span class="badge badge-light-success">Chiuso</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-light-secondary">{{ ucfirst($ticket->stato) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($ticket->priorita)
                                                @case('urgente')
                                                    <span class="badge badge-light-danger">Urgente</span>
                                                    @break
                                                @case('alta')
                                                    <span class="badge badge-light-danger">Alta</span>
                                                    @break
                                                @case('media')
                                                    <span class="badge badge-light-warning">Media</span>
                                                    @break
                                                @case('bassa')
                                                    <span class="badge badge-light-success">Bassa</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-light-secondary">{{ ucfirst($ticket->priorita) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($ticket->creato_da_id && $ticket->user)
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-circle symbol-30px me-3">
                                                <span class="symbol-label bg-light-primary text-primary fw-bold">
                                                    {{ substr($ticket->user->nome, 0, 1) }}{{ substr($ticket->user->cognome, 0, 1) }}
                                                </span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-800 fw-bold fs-7">{{ $ticket->user->nome }} {{ $ticket->user->cognome }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Sistema</span>
                                            @endif
                                        </td>
                                        <td>
                                    <span class="text-muted fw-semibold d-block fs-7">
                                        {{ $ticket->created_at->format('d/m/Y H:i') }}
                                    </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
