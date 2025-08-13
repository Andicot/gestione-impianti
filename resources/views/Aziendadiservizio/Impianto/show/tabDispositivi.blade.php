<div class="d-flex justify-content-between gap-5">
    {{-- Contatore Totale Dispositivi --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-primary">
                <i class="fas fa-microchip text-primary fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Totale Dispositivi</div>
            <div class="fs-4 fw-bold text-gray-800">{{ $statistiche['totale_dispositivi'] }}</div>
        </div>
    </div>

    {{-- Contatore UDR --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-success">
                <i class="fas fa-thermometer-half text-success fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">UDR</div>
            <div class="fs-4 fw-bold text-success">{{ $statistiche['totale_udr'] }}</div>
        </div>
    </div>

    {{-- Contatore Contatori ACS --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-info">
                <i class="fas fa-tint text-info fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Contatori ACS</div>
            <div class="fs-4 fw-bold text-info">{{ $statistiche['totale_contatori_acs'] }}</div>
        </div>
    </div>

    {{-- Contatore Dispositivi Attivi --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-warning">
                <i class="fas fa-check-circle text-warning fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Attivi</div>
            <div class="fs-4 fw-bold text-warning">{{ $statistiche['totale_attivi'] }}</div>
        </div>
    </div>
</div>

{{-- Header tabella con filtri --}}
<div class="border-0 pt-6">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            @includeWhen(isset($testoCerca),'Metronic._components.ricerca')
            <!-- Pulsante Filtri -->
            <button class="btn btn-sm btn-flex @if($conFiltro) btn-light-success @else btn-secondary @endif "
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#filtri-dispositivi-card"
                    aria-expanded="false"
                    aria-controls="filtri-dispositivi-card"
                    id="toggle-filtri-dispositivi">
                <i class="bi bi-funnel fs-3"></i>
                <span class="d-none d-md-block">Filtri</span>
            </button>
        </div>

        <div class="d-flex justify-content-end gap-3" >
            {{-- Pulsante Nuovo Dispositivo --}}
            @if($record->stato === 'attivo')
                <a href="{{action([\App\Http\Controllers\Aziendadiservizio\DispositivoMisuraController::class,'create'],'impianto_id='.$record->id)}}"
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fs-4"></i>
                    Nuovo Dispositivo
                </a>
            @endif
        </div>
    </div>

    <!-- Filtri Collassabili -->
    <div class="collapse" id="filtri-dispositivi-card">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Filtri di Ricerca</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <!-- Campo di ricerca generale -->
                    <div class="col-md-4">
                        <label class="form-label">Cerca</label>
                        <input type="text" name="cerca_no_ajax" class="form-control form-control-solid form-control-sm"
                               placeholder="Matricola, nome, marca, modello..."
                               value="">
                    </div>

                    <!-- Filtro Tipo -->
                    <div class="col-md-2">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutti i tipi</option>
                            @foreach(\App\Enums\TipoDispositivoEnum::cases() as $tipo)
                                <option value="{{$tipo->value}}">{{$tipo->testo()}}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Stato -->
                    <div class="col-md-2">
                        <label class="form-label">Stato</label>
                        <select name="stato" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutti gli stati</option>
                            @foreach(\App\Enums\StatoDispositivoEnum::cases() as $tipo)
                                <option value="{{$tipo->value}}">{{$tipo->testo()}}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Concentratore -->
                    <div class="col-md-2">
                        <label class="form-label">Concentratore</label>
                        <select name="concentratore_id" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutti</option>
                            <option value="con_concentratore">Con Concentratore</option>
                            <option value="senza_concentratore">Senza Concentratore</option>
                        </select>
                    </div>

                    <!-- Filtro Unità -->
                    <div class="col-md-2">
                        <label class="form-label">Unità</label>
                        <select name="unita_immobiliare_id" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutte</option>
                            <option value="con_unita">Con Unità</option>
                            <option value="senza_unita">Senza Unità</option>
                        </select>
                    </div>

                    <!-- Pulsanti -->
                    <div class="col-md-12 d-flex align-items-end">
                        <button type="submit" class="btn btn-sm btn-primary me-2">
                            <i class="fas fa-search"></i> Filtra
                        </button>
                        <a href="#" class="btn btn-sm btn-secondary me-2" onclick="resetFiltri()">
                            <i class="fas fa-eraser"></i> Reset
                        </a>
                        <button type="button" class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#filtri-dispositivi-card">
                            <i class="fas fa-times"></i> Chiudi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Tabella Dispositivi --}}
<div class="pt-0"  id="tabella">
    @include('Aziendadiservizio.Impianto.show.tabDispositiviTabella')
</div>

{{-- Script per filtri e ricerca --}}
@push('customScript')
    <script>
        $(document).ready(function () {
            // Ricerca
            let searchTimeout;
            $('[data-kt-customer-table-filter="search"]').on('keyup', function () {
                clearTimeout(searchTimeout);
                const searchValue = this.value.toLowerCase();

                searchTimeout = setTimeout(function () {
                    filterTable(searchValue);
                }, 300);
            });

            function filterTable(searchValue) {
                $('#kt_customers_table tbody tr').each(function () {
                    const row = $(this);
                    const text = row.text().toLowerCase();

                    if (text.includes(searchValue) || searchValue === '') {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            }

            // Gestione filtri rapidi automatici
            document.querySelectorAll('input[name="filtro_rapido"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    if (this.checked) {
                        switch (this.value) {
                            case 'solo_attivi':
                                document.querySelector('select[name="stato"]').value = 'attivo';
                                break;
                            case 'con_anomalie':
                                // Logica per dispositivi con anomalie
                                break;
                            case 'senza_letture':
                                // Logica per dispositivi senza letture
                                break;
                        }
                    }
                });
            });
        });

        function resetFiltri() {
            // Reset del form
            document.querySelector('#filtri-dispositivi-card form').reset();

            // Mostra tutte le righe
            $('#kt_customers_table tbody tr').show();

            // Reset dei filtri rapidi
            document.querySelectorAll('input[name="filtro_rapido"]').forEach(function (radio) {
                radio.checked = false;
            });
        }

        // Funzione per cambiare stato dispositivo
        function cambiaStatoDispositivo(dispositivoId, nuovoStato) {
            if (confirm('Sei sicuro di voler cambiare lo stato di questo dispositivo?')) {
                // Qui implementare la chiamata AJAX per cambiare lo stato
                $.ajax({
                    url: '/dispositivi/' + dispositivoId + '/cambio-stato',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        stato: nuovoStato
                    },
                    success: function (response) {
                        location.reload();
                    },
                    error: function (xhr) {
                        alert('Errore nel cambio stato: ' + xhr.responseText);
                    }
                });
            }
        }
    </script>
@endpush
