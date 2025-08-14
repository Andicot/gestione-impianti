<!-- Filtri Collassabili -->
<div class="collapse @if($conFiltro) show @endif" id="filtri-card">
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                Filtri di Ricerca
                @if($conFiltro)
                    <span class="badge badge-primary ms-2">Filtri Attivi</span>
                @endif
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <!-- Campo di ricerca generale -->
                <div class="col-md-4">
                    <label class="form-label">Cerca</label>
                    <input type="text" name="cerca" class="form-control form-control-solid form-control-sm"
                           placeholder="Nome impianto, indirizzo, codice..."
                           value="{{ request('cerca') }}">
                </div>

                <!-- Filtro Stato -->
                <div class="col-md-2">
                    <label class="form-label">Stato</label>
                    <select name="stato" class="form-select form-select-sm form-select-solid">
                        <option value="">Tutti gli stati</option>
                        @foreach(\App\Enums\StatoImpiantoEnum::cases() as $stato)
                            <option value="{{ $stato->value }}"
                                {{ request('stato') == $stato->value ? 'selected' : '' }}>
                                {{ $stato->testo() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Tipologia -->
                <div class="col-md-2">
                    <label class="form-label">Tipologia</label>
                    <select name="tipologia" class="form-select form-select-sm form-select-solid">
                        <option value="">Tutte le tipologie</option>
                        @foreach(\App\Enums\TipologiaImpiantoEnum::cases() as $tipologia)
                            <option value="{{ $tipologia->value }}"
                                {{ request('tipologia') == $tipologia->value ? 'selected' : '' }}>
                                {{ $tipologia->testo() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($mostraAmministratore)
                    <!-- Filtro Amministratore -->
                    <div class="col-md-2">
                        <label class="form-label">Amministratore</label>
                        <select id="amministratore_id" name="amministratore_id" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutti gli amministratori</option>
                            @if(isset($amministratori))
                                @foreach($amministratori as $amministratore)
                                    <option value="{{ $amministratore->id }}"
                                        {{ request('amministratore_id') == $amministratore->id ? 'selected' : '' }}>
                                        {{ $amministratore->user->nome }} {{ $amministratore->user->cognome }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @endif
                <!-- Filtro Città -->
                <div class="col-md-2">
                    <label class="form-label">Città</label>
                    <select id="citta" name="citta" class="form-select form-select-sm form-select-solid">
                        <option value="">Tutte le città</option>
                        @if(isset($citta_disponibili))
                            @foreach($citta_disponibili as $citta)
                                <option value="{{ $citta }}"
                                    {{ request('citta') == $citta ? 'selected' : '' }}>
                                    {{ $citta }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Filtro Concentratore -->
                <div class="col-md-2">
                    <label class="form-label">Concentratore</label>
                    <select name="concentratore_id" class="form-select form-select-sm form-select-solid">
                        <option value="">Tutti</option>
                        <option value="con_concentratore" {{ request('concentratore_id') == 'con_concentratore' ? 'selected' : '' }}>
                            Con Concentratore
                        </option>
                        <option value="senza_concentratore" {{ request('concentratore_id') == 'senza_concentratore' ? 'selected' : '' }}>
                            Senza Concentratore
                        </option>
                        @if(isset($concentratori))
                            @foreach($concentratori as $concentratore)
                                <option value="{{ $concentratore->id }}"
                                    {{ request('concentratore_id') == $concentratore->id ? 'selected' : '' }}>
                                    {{ $concentratore->marca }} {{ $concentratore->modello }} ({{ $concentratore->matricola }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Filtri Servizi -->
                <div class="col-md-3">
                    <label class="form-label">Servizi Attivi</label>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="checkbox" name="servizio_riscaldamento" value="1"
                                       {{ request('servizio_riscaldamento') ? 'checked' : '' }} id="riscaldamento">
                                <label class="form-check-label fs-7" for="riscaldamento">
                                    Riscaldamento
                                </label>
                            </div>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="checkbox" name="servizio_acs" value="1"
                                       {{ request('servizio_acs') ? 'checked' : '' }} id="acs">
                                <label class="form-check-label fs-7" for="acs">
                                    ACS
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="checkbox" name="servizio_gas" value="1"
                                       {{ request('servizio_gas') ? 'checked' : '' }} id="gas">
                                <label class="form-check-label fs-7" for="gas">
                                    Gas
                                </label>
                            </div>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="checkbox" name="servizio_luce" value="1"
                                       {{ request('servizio_luce') ? 'checked' : '' }} id="luce">
                                <label class="form-check-label fs-7" for="luce">
                                    Luce
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtri Rapidi -->
                <div class="col-md-6">
                    <label class="form-label">Filtri Rapidi</label>
                    <div class="btn-group btn-group-sm w-100" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                        <label class="btn btn-sm btn-outline btn-color-muted btn-active-primary {{ request('filtro_rapido') == 'attivi' ? 'active' : '' }}"
                               data-kt-button="true">
                            <input class="btn-check" type="radio" name="filtro_rapido" value="attivi"
                                {{ request('filtro_rapido') == 'attivi' ? 'checked' : '' }}/>
                            Solo Attivi
                        </label>

                        <label
                            class="btn btn-sm btn-outline btn-color-muted btn-active-success {{ request('filtro_rapido') == 'con_concentratore' ? 'active' : '' }}"
                            data-kt-button="true">
                            <input class="btn-check" type="radio" name="filtro_rapido" value="con_concentratore"
                                {{ request('filtro_rapido') == 'con_concentratore' ? 'checked' : '' }}/>
                            Con Concentratore
                        </label>

                        <label
                            class="btn btn-sm btn-outline btn-color-muted btn-active-warning {{ request('filtro_rapido') == 'senza_concentratore' ? 'active' : '' }}"
                            data-kt-button="true">
                            <input class="btn-check" type="radio" name="filtro_rapido" value="senza_concentratore"
                                {{ request('filtro_rapido') == 'senza_concentratore' ? 'checked' : '' }}/>
                            Senza Concentratore
                        </label>
                    </div>
                </div>

                <!-- Pulsanti -->
                <div class="col-md-12 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-primary me-2">
                        <i class="fas fa-search"></i> Filtra
                    </button>
                    <a href="{{ action([$controller,'index']) }}" class="btn btn-sm btn-secondary me-2">
                        <i class="fas fa-eraser"></i> Reset
                    </a>
                    <button type="button" class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#filtri-card">
                        <i class="fas fa-times"></i> Chiudi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
