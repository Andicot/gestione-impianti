@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-3 mb-6">
        <a href="{{ action([App\Http\Controllers\Aziendadiservizio\ImportazioneController::class, 'storico']) }}"
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
                                            <span class="badge badge-warning fs-6">Completato con Errori</span>
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

        {{-- Statistiche --}}
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

                    @if($importazione->righe_errore > 0)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Righe con Errori</span>
                                <span class="text-danger fw-bolder">{{ number_format($importazione->righe_errore) }}</span>
                            </div>
                            <div class="progress mb-2" style="height: 6px;">
                                @php($percentualeErrori = $importazione->righe_totali > 0 ? ($importazione->righe_errore / $importazione->righe_totali) * 100 : 0)
                                <div class="progress-bar bg-danger" style="width: {{ $percentualeErrori }}%"></div>
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
                                <i class="fas fa-info-circle me-2"></i>
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

    {{-- Log Errori --}}
    @if($importazione->log_errori && count($importazione->log_errori) > 0)
        <div class="row mt-6">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Log Errori ({{ count($importazione->log_errori) }})
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-row-gray-100">
                                <thead>
                                <tr class="fw-bolder text-muted">
                                    <th>Riga</th>
                                    <th>Errore</th>
                                    <th>Dati</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($importazione->log_errori as $errore)
                                    <tr>
                                        <td>
                                            @if(isset($errore['riga']))
                                                <span class="badge badge-light-danger">{{ $errore['riga'] }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-danger">{{ $errore['errore'] ?? $errore['errore_generale'] ?? 'Errore non specificato' }}</div>
                                        </td>
                                        <td>
                                            @if(isset($errore['dati']) && is_array($errore['dati']))
                                                <code class="text-muted">{{ implode(' | ', array_slice($errore['dati'], 0, 5)) }}{{ count($errore['dati']) > 5 ? '...' : '' }}</code>
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
                </div>
            </div>
        </div>
    @endif
@endsection
