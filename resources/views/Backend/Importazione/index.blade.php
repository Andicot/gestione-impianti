@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-3 mb-6">
        <a href="{{ action([\App\Http\Controllers\Backend\ImportazioneController::class, 'storico']) }}"
           class="btn btn-sm btn-secondary fw-bold">
            <i class="fas fa-history"></i> Storico Importazioni
        </a>
    </div>
@endsection

@section('content')
    @include('Metronic._components.alertMessage')

    {{-- Form Unificato Importazione Letture --}}
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-import text-primary me-2"></i>
                        Importa Letture Dispositivi
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Carica file contenenti letture dei dispositivi UDR.
                        Il sistema rileva automaticamente il formato (CSV o Excel) e processa i dati.
                    </p>

                    <form action="{{ action([\App\Http\Controllers\Backend\ImportazioneController::class, 'caricaFile']) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="form-importazione">
                        @csrf

                        {{-- Selezione Impianto --}}
                        <div class="mb-4">
                            <label class="form-label required">Impianto</label>
                            <select name="impianto_id" class="form-select" required>
                                <option value="">Seleziona impianto...</option>
                                @foreach(\App\Models\Impianto::where('stato_impianto', 'attivo')->orderBy('nome_impianto')->get() as $impianto)
                                    <option value="{{ $impianto->id }}" {{ old('impianto_id') == $impianto->id ? 'selected' : '' }}>
                                        {{ $impianto->nome_impianto }}
                                    </option>
                                @endforeach
                            </select>
                            @error('impianto_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Selezione Concentratore (opzionale) --}}
                        <div class="mb-4">
                            <label class="form-label">Concentratore</label>
                            <select name="concentratore_id" class="form-select">
                                <option value="">Seleziona concentratore...</option>
                                @foreach(\App\Models\Concentratore::orderBy('matricola')->get() as $concentratore)
                                    <option value="{{ $concentratore->id }}" {{ old('concentratore_id') == $concentratore->id ? 'selected' : '' }}>
                                        {{ $concentratore->matricola }} - {{ $concentratore->marca }}
                                    </option>
                                @endforeach
                            </select>
                            @error('concentratore_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Selezione Periodo (opzionale) --}}
                        <div class="mb-4">
                            <label class="form-label">Periodo Contabilizzazione</label>
                            <select name="periodo_id" class="form-select">
                                <option value="">Seleziona periodo...</option>
                                @foreach(\App\Models\PeriodoContabilizzazione::orderBy('data_inizio', 'desc')->get() as $periodo)
                                    <option value="{{ $periodo->id }}" {{ old('periodo_id') == $periodo->id ? 'selected' : '' }}>
                                        {{ $periodo->codice }} - {{ $periodo->data_inizio->format('d/m/Y') }} / {{ $periodo->data_fine->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('periodo_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Upload File --}}
                        <div class="mb-4">
                            <label class="form-label required">File Letture</label>
                            <input type="file"
                                   name="file"
                                   class="form-control"
                                   accept=".csv,.txt,.xlsx,.xls"
                                   required
                                   id="file-input">
                            <div class="form-text">
                                <strong>Formati supportati:</strong> CSV, TXT, Excel (XLSX, XLS).
                                <strong>Dimensione massima:</strong> 10MB
                            </div>
                            @error('file')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror

                            {{-- Anteprima tipo file --}}
                            <div id="file-preview" class="mt-2" style="display: none;">
                                <div class="alert alert-info d-flex align-items-center">
                                    <i id="file-icon" class="fas fa-file me-2"></i>
                                    <div>
                                        <strong>File selezionato:</strong> <span id="file-name"></span><br>
                                        <small><strong>Tipo rilevato:</strong> <span id="file-type"></span></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="fas fa-upload"></i> Importa Letture Dispositivi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Informazioni sui Formati --}}
    <div class="row mt-6">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Formati Supportati
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- CSV --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-file-csv text-success fs-2 me-3"></i>
                                <h5 class="text-success mb-0">File CSV/TXT</h5>
                            </div>
                            <ul class="text-muted">
                                <li><strong>Separatore:</strong> punto e virgola (;)</li>
                                <li><strong>Encoding:</strong> UTF-8</li>
                                <li><strong>Header:</strong> Serial, Nome Impianto, Indirizzo</li>
                                <li><strong>Dati:</strong> Matricola, Nome, Data, Ora, UDR</li>
                                <li><strong>Vantaggi:</strong> Formato standard concentratori</li>
                            </ul>
                        </div>

                        {{-- Excel --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-file-excel text-primary fs-2 me-3"></i>
                                <h5 class="text-primary mb-0">File Excel</h5>
                            </div>
                            <ul class="text-muted">
                                <li><strong>Formati:</strong> .xlsx, .xls</li>
                                <li><strong>Struttura:</strong> Tabella con header</li>
                                <li><strong>Dati:</strong> Matricola, Ambiente, UDR, Temperature</li>
                                <li><strong>Vantaggi:</strong> Dati storici dettagliati</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-light d-flex">
                                <i class="fas fa-lightbulb text-warning me-3 fs-4"></i>
                                <div>
                                    <strong>Nota:</strong> Il sistema crea automaticamente i dispositivi non esistenti
                                    e tenta di associarli alle unità immobiliari basandosi sui nomi.
                                    I dispositivi non associati verranno marcati come "anomalia" per revisione manuale.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script>
        $(function () {
            // Gestione preview file
            $('#file-input').on('change', function () {
                const file = this.files[0];
                const preview = $('#file-preview');

                if (file) {
                    const fileName = file.name;
                    const fileExtension = fileName.split('.').pop().toLowerCase();
                    let fileType = '';
                    let iconClass = '';

                    // Determina tipo e icona
                    if (['csv', 'txt'].includes(fileExtension)) {
                        fileType = 'CSV - Letture da concentratore';
                        iconClass = 'fas fa-file-csv text-success';
                    } else if (['xlsx', 'xls'].includes(fileExtension)) {
                        fileType = 'Excel - Dati ripartitori di calore';
                        iconClass = 'fas fa-file-excel text-primary';
                    } else {
                        fileType = 'Formato non riconosciuto';
                        iconClass = 'fas fa-file text-muted';
                    }

                    // Aggiorna preview
                    $('#file-name').text(fileName);
                    $('#file-type').text(fileType);
                    $('#file-icon').removeClass().addClass(iconClass);
                    preview.show();
                } else {
                    preview.hide();
                }
            });

            // Validazione client-side
            $('.form-importazione').on('submit', function (e) {
                const file = $('#file-input')[0].files[0];
                const impiantoId = $('select[name="impianto_id"]').val();

                if (!file) {
                    e.preventDefault();
                    alert('Seleziona un file da importare');
                    return false;
                }

                if (!impiantoId) {
                    e.preventDefault();
                    alert('Seleziona un impianto');
                    return false;
                }

                // Conferma invio
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                if (!confirm(`Procedere con l'importazione del file "${file.name}" (${fileSize} MB)?`)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endpush
