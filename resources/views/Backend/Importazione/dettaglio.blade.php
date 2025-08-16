@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-3 mb-6">
        <a href="{{ action([\App\Http\Controllers\Backend\ImportazioneController::class, 'storico']) }}"
           class="btn btn-sm btn-secondary fw-bold">
            <i class="fas fa-arrow-left"></i> Torna allo Storico
        </a>
        @if($importazione->path_file && Storage::disk('public')->exists($importazione->path_file))
            <a href="{{ Storage::disk('public')->url($importazione->path_file) }}"
               class="btn btn-sm btn-primary fw-bold"
               download>
                <i class="fas fa-download"></i> Scarica File
            </a>
        @endif
    </div>
@endsection

@section('content')
    <div class="row">
        {{-- Informazioni generali --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Dettagli Importazione #{{ $importazione->id }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Nome File</label>
                                <div class="text-dark fw-bolder">
                                    @if(str_contains($importazione->nome_file, '.csv'))
                                        <i class="fas fa-file-csv text-success me-2"></i>
                                    @else
                                        <i class="fas fa-file-excel text-primary me-2"></i>
                                    @endif
                                    {{ $importazione->nome_file }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Stato</label>
                                <div>
                                    @switch($importazione->stato)
                                        @case('completato')
                                            <span class="badge badge-success fs-6">Completato</span>
                                            @break
                                        @case('con_errori')
                                            <span class="badge badge-danger fs-6">Completato con Errori</span>
                                            @break
                                        @case('con_avvisi')
                                            <span class="badge badge-warning fs-6">Completato con Avvisi</span>
                                            @break
                                        @case('in_elaborazione')
                                            <span class="badge badge-primary fs-6">In Elaborazione</span>
                                            @break
                                        @case('errore')
                                            <span class="badge badge-danger fs-6">Errore</span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Impianto</label>
                                <div class="text-dark">
                                    @if($importazione->impianto)
                                        {{ $importazione->impianto->nome_impianto }}
                                        <div class="text-muted fs-7">{{ $importazione->impianto->indirizzo }}</div>
                                    @else
                                        <span class="text-muted">Non specificato</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Concentratore</label>
                                <div class="text-dark">
                                    @if($importazione->concentratore)
                                        {{ $importazione->concentratore->matricola }}
                                        <div class="text-muted fs-7">{{ $importazione->concentratore->marca }} {{ $importazione->concentratore->modello }}</div>
                                    @else
                                        <span class="text-muted">Non specificato</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Tipo Caricamento</label>
                                <div>
                                    @if($importazione->tipo_caricamento === 'automatico_ip')
                                        <span class="badge badge-light-warning">Automatico IP</span>
                                    @else
                                        <span class="badge badge-light-info">Manuale</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">IP Mittente</label>
                                <div class="text-dark">
                                    {{ $importazione->ip_mittente ?? 'Non disponibile' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Data Caricamento</label>
                                <div class="text-dark">
                                    {{ $importazione->created_at->format('d/m/Y H:i:s') }}
                                    <div class="text-muted fs-7">{{ $importazione->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label text-muted">Caricato da</label>
                                <div class="text-dark">
                                    @if($importazione->caricatoDa)
                                        {{ $importazione->caricatoDa->nome }} {{ $importazione->caricatoDa->cognome }}
                                        <div class="text-muted fs-7">{{ $importazione->caricatoDa->email }}</div>
                                    @else
                                        <span class="text-muted">Utente non disponibile</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistiche AGGIORNATE --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Statistiche Elaborazione
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Righe Totali</span>
                            <span class="text-dark fw-bolder">{{ number_format($importazione->righe_totali) }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Righe Elaborate</span>
                            <span class="text-success fw-bolder">{{ number_format($importazione->righe_elaborate) }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            @php($percentualeElaborate = $importazione->righe_totali > 0 ? ($importazione->righe_elaborate / $importazione->righe_totali) * 100 : 0)
                            <div class="progress-bar bg-success" style="width: {{ $percentualeElaborate }}%"></div>
                        </div>
                    </div>

                    {{-- ERRORI REALI --}}
                    @if($importazione->righe_errore > 0)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Errori Reali</span>
                                <span class="text-danger fw-bolder">{{ number_format($importazione->righe_errore) }}</span>
                            </div>
                            <div class="progress mb-2" style="height: 6px;">
                                @php($percentualeErrori = $importazione->righe_totali > 0 ? ($importazione->righe_errore / $importazione->righe_totali) * 100 : 0)
                                <div class="progress-bar bg-danger" style="width: {{ $percentualeErrori }}%"></div>
                            </div>
                        </div>
                    @endif

                    {{-- AVVISI --}}
                    @if(($importazione->righe_warning ?? 0) > 0)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Avvisi</span>
                                <span class="text-warning fw-bolder">{{ number_format($importazione->righe_warning) }}</span>
                            </div>
                            <div class="progress mb-2" style="height: 6px;">
                                @php($percentualeWarning = $importazione->righe_totali > 0 ? ($importazione->righe_warning / $importazione->righe_totali) * 100 : 0)
                                <div class="progress-bar bg-warning" style="width: {{ $percentualeWarning }}%"></div>
                            </div>
                        </div>
                    @endif

                    {{-- MESSAGGI INFORMATIVI --}}
                    @if(($importazione->righe_info ?? 0) > 0)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Messaggi Informativi</span>
                                <span class="text-info fw-bolder">{{ number_format($importazione->righe_info) }}</span>
                            </div>
                            <div class="alert alert-light-info py-2 px-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Messaggi normali del processo di importazione
                            </div>
                        </div>
                    @endif

                    @if($importazione->dispositivi_nuovi > 0)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Dispositivi Nuovi</span>
                                <span class="text-primary fw-bolder">{{ number_format($importazione->dispositivi_nuovi) }}</span>
                            </div>
                            <div class="alert alert-light-primary py-2 px-3">
                                <i class="fas fa-plus-circle me-2"></i>
                                Dispositivi creati automaticamente dal CSV
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Metadata CSV --}}
    @if($importazione->metadata_csv)
        <div class="row mt-6">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Metadata File CSV
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($importazione->metadata_csv as $chiave => $valore)
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-muted">{{ ucfirst(str_replace('_', ' ', $chiave)) }}</label>
                                    <div class="text-dark">{{ $valore ?: 'Non specificato' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- LOG STRUTTURATO --}}
    @if($importazione->log_errori)
        @php($logData = is_string($importazione->log_errori)? json_decode($importazione->log_errori, true): $importazione->log_errori)


        <div class="row mt-6">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Log Elaborazione</h3>
                        <div class="card-toolbar">
                            {{-- Badge riassuntivo --}}
                            <div class="d-flex gap-2">
                                @if(isset($logData['errori']) && count($logData['errori']) > 0)
                                    <span class="badge badge-danger fs-7">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ count($logData['errori']) }} Errore/i
                                    </span>
                                @endif

                                @if(isset($logData['warning']) && count($logData['warning']) > 0)
                                    <span class="badge badge-warning fs-7">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ count($logData['warning']) }} Avviso/i
                                    </span>
                                @endif

                                @if(isset($logData['info']) && count($logData['info']) > 0)
                                    <span class="badge badge-info fs-7">
                                        <i class="fas fa-info-circle"></i>
                                        {{ count($logData['info']) }} Info
                                    </span>
                                @endif

                                @if((!isset($logData['errori']) || count($logData['errori']) == 0) && (!isset($logData['warning']) || count($logData['warning']) == 0))
                                    <span class="badge badge-success fs-7">
                                        <i class="fas fa-check"></i>
                                        Elaborazione OK
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Tabs per i diversi tipi di log --}}
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-bold mb-5" role="tablist">
                            {{-- Tab Errori --}}
                            @if(isset($logData['errori']) && count($logData['errori']) > 0)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-danger active" data-bs-toggle="tab" href="#tab_errori" role="tab">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Errori ({{ count($logData['errori']) }})
                                    </a>
                                </li>
                            @endif

                            {{-- Tab Warning --}}
                            @if(isset($logData['warning']) && count($logData['warning']) > 0)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-warning {{ !isset($logData['errori']) || count($logData['errori']) == 0 ? 'active' : '' }}"
                                       data-bs-toggle="tab" href="#tab_warning" role="tab">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        Avvisi ({{ count($logData['warning']) }})
                                    </a>
                                </li>
                            @endif

                            {{-- Tab Info --}}
                            @if(isset($logData['info']) && count($logData['info']) > 0)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-info {{ (!isset($logData['errori']) || count($logData['errori']) == 0) && (!isset($logData['warning']) || count($logData['warning']) == 0) ? 'active' : '' }}"
                                       data-bs-toggle="tab" href="#tab_info" role="tab">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Info ({{ count($logData['info']) }})
                                    </a>
                                </li>
                            @endif

                            {{-- Tab per log legacy (vecchio formato) --}}
                            @if(!isset($logData['errori']) && !isset($logData['warning']) && !isset($logData['info']) && is_array($logData))
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-dark active" data-bs-toggle="tab" href="#tab_legacy" role="tab">
                                        <i class="fas fa-list me-2"></i>
                                        Log Completo ({{ count($logData) }})
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <div class="tab-content">
                            {{-- Contenuto Tab Errori --}}
                            @if(isset($logData['errori']) && count($logData['errori']) > 0)
                                <div class="tab-pane fade show active" id="tab_errori" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered table-row-gray-100">
                                            <thead>
                                            <tr class="fw-bolder text-muted bg-danger bg-opacity-10">
                                                <th class="min-w-50px">Riga</th>
                                                <th class="min-w-200px">Errore</th>
                                                <th class="min-w-150px">Dati</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($logData['errori'] as $errore)
                                                <tr>
                                                    <td>
                                                        <span class="badge badge-light-danger">{{ $errore['riga'] ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-danger fw-bold">{{ $errore['messaggio'] ?? 'Messaggio non disponibile' }}</span>
                                                    </td>
                                                    <td>
                                                        @if(isset($errore['dati']) && is_array($errore['dati']) && count($errore['dati']) > 0)
                                                            <code
                                                                class="text-muted">{{ implode(' | ', array_slice($errore['dati'], 0, 3)) }}{{ count($errore['dati']) > 3 ? '...' : '' }}</code>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- Contenuto Tab Warning --}}
                            @if(isset($logData['warning']) && count($logData['warning']) > 0)
                                <div class="tab-pane fade {{ !isset($logData['errori']) || count($logData['errori']) == 0 ? 'show active' : '' }}"
                                     id="tab_warning" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered table-row-gray-100">
                                            <thead>
                                            <tr class="fw-bolder text-muted bg-warning bg-opacity-10">
                                                <th class="min-w-50px">Riga</th>
                                                <th class="min-w-200px">Avviso</th>
                                                <th class="min-w-150px">Dati</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($logData['warning'] as $warning)
                                                <tr>
                                                    <td>
                                                        <span class="badge badge-light-warning">{{ $warning['riga'] ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-warning fw-bold">{{ $warning['messaggio'] ?? 'Messaggio non disponibile' }}</span>
                                                    </td>
                                                    <td>
                                                        @if(isset($warning['dati']) && is_array($warning['dati']) && count($warning['dati']) > 0)
                                                            <code
                                                                class="text-muted">{{ implode(' | ', array_slice($warning['dati'], 0, 3)) }}{{ count($warning['dati']) > 3 ? '...' : '' }}</code>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- Contenuto Tab Info --}}
                            @if(isset($logData['info']) && count($logData['info']) > 0)
                                <div
                                    class="tab-pane fade {{ (!isset($logData['errori']) || count($logData['errori']) == 0) && (!isset($logData['warning']) || count($logData['warning']) == 0) ? 'show active' : '' }}"
                                    id="tab_info" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered table-row-gray-100">
                                            <thead>
                                            <tr class="fw-bolder text-muted bg-info bg-opacity-10">
                                                <th class="min-w-50px">Riga</th>
                                                <th class="min-w-200px">Messaggio Informativo</th>
                                                <th class="min-w-150px">Dati</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($logData['info'] as $info)
                                                <tr>
                                                    <td>
                                                        <span class="badge badge-light-info">{{ $info['riga'] ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-info">{{ $info['messaggio'] ?? 'Messaggio non disponibile' }}</span>
                                                    </td>
                                                    <td>
                                                        @if(isset($info['dati']) && is_array($info['dati']) && count($info['dati']) > 0)
                                                            <code
                                                                class="text-muted">{{ implode(' | ', array_slice($info['dati'], 0, 3)) }}{{ count($info['dati']) > 3 ? '...' : '' }}</code>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- Contenuto Tab Legacy --}}
                            @if(!isset($logData['errori']) && !isset($logData['warning']) && !isset($logData['info']) && is_array($logData))
                                <div class="tab-pane fade show active" id="tab_legacy" role="tabpanel">
                                    <div class="alert alert-warning d-flex align-items-center p-5 mb-6">
                                        <i class="fas fa-exclamation-triangle fs-2hx text-warning me-4"></i>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-warning">Log in formato precedente</h4>
                                            <span>Questi log usano il formato precedente. Le prossime importazioni avranno il formato migliorato.</span>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-row-bordered table-row-gray-100">
                                            <thead>
                                            <tr class="fw-bolder text-muted">
                                                <th class="min-w-50px">Riga</th>
                                                <th class="min-w-200px">Messaggio</th>
                                                <th class="min-w-150px">Dati</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($logData as $log)
                                                @if(is_array($log))
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-light">{{ $log['riga'] ?? 'N/A' }}</span>
                                                        </td>
                                                        <td>
                                                            {{-- QUESTA È LA CORREZIONE PRINCIPALE! --}}
                                                            <span>{{ $log['messaggio'] ?? $log['errore'] ?? $log['errore_generale'] ?? 'Messaggio non disponibile' }}</span>
                                                        </td>
                                                        <td>
                                                            @if(isset($log['dati']) && is_array($log['dati']) && count($log['dati']) > 0)
                                                                <code
                                                                    class="text-muted">{{ implode(' | ', array_slice($log['dati'], 0, 3)) }}{{ count($log['dati']) > 3 ? '...' : '' }}</code>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
