@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-3 mb-6">
        <a href="{{ action([App\Http\Controllers\Aziendadiservizio\ImportazioneController::class, 'storico']) }}"
           class="btn btn-sm btn-secondary fw-bold">
            <i class="fas fa-history"></i> Storico Importazioni
        </a>
    </div>
@endsection

@section('content')
    @include('Metronic._components.alertMessage')
    <div class="row">
        {{-- Card CSV Letture Dispositivi --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-csv text-success me-2"></i>
                        Importa CSV Letture Dispositivi
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Carica file CSV contenenti letture dei dispositivi UDR dal concentratore.
                        Il file deve contenere dati di matricola, letture e metadati dell'impianto.
                    </p>

                    <form action="{{ action([App\Http\Controllers\Aziendadiservizio\ImportazioneController::class, 'caricaFile']) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="form-csv">
                        @csrf
                        <input type="hidden" name="tipo_file" value="csv_letture">

                        {{-- Selezione Impianto --}}
                        <div class="mb-4">
                            <label class="form-label required">Impianto</label>
                            <select name="impianto_id" class="form-select" required>
                                <option value="">Seleziona impianto...</option>
                                @foreach(\App\Models\Impianto::where('stato_impianto', 'attivo')->orderBy('nome_impianto')->get() as $impianto)
                                    <option value="{{ $impianto->id }}">{{ $impianto->nome_impianto }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Selezione Concentratore (opzionale) --}}
                        <div class="mb-4">
                            <label class="form-label">Concentratore</label>
                            <select name="concentratore_id" class="form-select">
                                <option value="">Seleziona concentratore...</option>
                                @foreach(\App\Models\Concentratore::orderBy('matricola')->get() as $concentratore)
                                    <option value="{{ $concentratore->id }}">{{ $concentratore->matricola }} - {{ $concentratore->marca }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Selezione Periodo (opzionale) --}}
                        <div class="mb-4">
                            <label class="form-label">Periodo Contabilizzazione</label>
                            <select name="periodo_id" class="form-select">
                                <option value="">Seleziona periodo...</option>
                                @foreach(\App\Models\PeriodoContabilizzazione::orderBy('data_inizio', 'desc')->get() as $periodo)
                                    <option value="{{ $periodo->id }}">
                                        {{ $periodo->data_inizio->format('d/m/Y') }} - {{ $periodo->data_fine->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Upload File --}}
                        <div class="mb-4">
                            <label class="form-label required">File CSV</label>
                            <input type="file"
                                   name="file"
                                   class="form-control"
                                   accept=".csv,.txt"
                                   required>
                            <div class="form-text">
                                Formati supportati: CSV, TXT. Dimensione massima: 10MB
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-upload"></i> Carica CSV Letture
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Card Excel Inventario --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-excel text-primary me-2"></i>
                        Importa Excel Inventario
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Carica file Excel contenenti dati di inventario, prodotti o altre informazioni tabellari.
                    </p>

                    <form action="{{ action([App\Http\Controllers\Aziendadiservizio\ImportazioneController::class, 'caricaFile']) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="form-excel">
                        @csrf
                        <input type="hidden" name="tipo_file" value="excel_inventario">

                        {{-- Upload File --}}
                        <div class="mb-4">
                            <label class="form-label required">File Excel</label>
                            <input type="file"
                                   name="file"
                                   class="form-control"
                                   accept=".xlsx,.xls"
                                   required>
                            <div class="form-text">
                                Formati supportati: XLSX, XLS. Dimensione massima: 10MB
                            </div>
                        </div>

                        {{-- Descrizione opzionale --}}
                        <div class="mb-4">
                            <label class="form-label">Note</label>
                            <textarea name="note"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Aggiungi note o descrizione per questo caricamento..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-upload"></i> Carica Excel Inventario
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Informazioni aggiuntive --}}
    <div class="row mt-6">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Informazioni sui Formati
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-success">CSV Letture Dispositivi</h5>
                            <ul class="text-muted">
                                <li>Separatore: punto e virgola (;)</li>
                                <li>Encoding: UTF-8</li>
                                <li>Header: Serial, Nome Impianto, Indirizzo, ecc.</li>
                                <li>Dati dispositivi: Matricola, Nome, Descrizioni, Data, Ora, Stato, UDR</li>
                                <li>Crea automaticamente dispositivi non esistenti</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-primary">Excel Inventario</h5>
                            <ul class="text-muted">
                                <li>Prima riga: intestazioni colonne</li>
                                <li>Dati tabellari strutturati</li>
                                <li>Supporta formule e formattazione</li>
                                <li>Elaborazione personalizzabile</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script>
        $(function() {
            // Gestione upload con progress
            $('.form-csv, .form-excel').on('submit', function() {
                var btn = $(this).find('button[type="submit"]');
                var originalText = btn.html();

                btn.prop('disabled', true);
                btn.html('<i class="fas fa-spinner fa-spin"></i> Elaborazione in corso...');

                setTimeout(function() {
                    if (btn.prop('disabled')) {
                        btn.html(originalText);
                        btn.prop('disabled', false);
                    }
                }, 30000); // Timeout dopo 30 secondi
            });

            // Validazione file CSV
            $('input[name="file"][accept*="csv"]').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var extension = file.name.split('.').pop().toLowerCase();
                    if (!['csv', 'txt'].includes(extension)) {
                        alert('Formato file non supportato. Utilizzare CSV o TXT.');
                        $(this).val('');
                    }
                }
            });

            // Validazione file Excel
            $('input[name="file"][accept*="xlsx"]').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var extension = file.name.split('.').pop().toLowerCase();
                    if (!['xlsx', 'xls'].includes(extension)) {
                        alert('Formato file non supportato. Utilizzare XLSX o XLS.');
                        $(this).val('');
                    }
                }
            });
        });
    </script>
@endpush
