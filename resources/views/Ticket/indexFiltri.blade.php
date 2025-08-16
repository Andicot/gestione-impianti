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
                           placeholder="Titolo, descrizione..."
                           value="{{ request('cerca') }}">
                </div>

                <!-- Filtro Stato -->
                <div class="col-md-2">
                    <label class="form-label">Stato</label>
                    <select name="stato" class="form-select form-select-sm form-select-solid" data-control="select2" data-hide-search="true">
                        <option value="">Tutti gli stati</option>
                        @foreach(\App\Enums\StatoTicketEnum::cases() as $stato)
                            <option value="{{ $stato->value }}"
                                {{ request('stato') == $stato->value ? 'selected' : '' }}>
                                {{ $stato->testo() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Priorità -->
                <div class="col-md-2">
                    <label class="form-label">Priorità</label>
                    <select name="priorita" class="form-select form-select-sm form-select-solid" data-control="select2" data-hide-search="true">
                        <option value="">Tutte le priorità</option>
                        @foreach(\App\Enums\PrioritaTicketEnum::cases() as $priorita)
                            <option value="{{ $priorita->value }}"
                                {{ request('priorita') == $priorita->value ? 'selected' : '' }}>
                                {{ $priorita->testo() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Categoria -->
                <div class="col-md-3">
                    <label class="form-label">Categoria</label>
                    <select name="categoria" class="form-select form-select-sm form-select-solid" data-control="select2" data-hide-search="true">
                        <option value="">Tutte le categorie</option>
                        @foreach(\App\Enums\CategoriaTicketEnum::cases() as $categoria)
                            <option value="{{ $categoria->value }}"
                                {{ request('categoria') == $categoria->value ? 'selected' : '' }}>
                                {{ $categoria->testo() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Origine -->
                <div class="col-md-2">
                    <label class="form-label">Origine</label>
                    <select name="origine" class="form-select form-select-sm form-select-solid" data-control="select2" data-hide-search="true">
                        <option value="">Tutte le origini</option>
                        @foreach(\App\Enums\OrigineTicketEnum::cases() as $origine)
                            <option value="{{ $origine->value }}"
                                {{ request('origine') == $origine->value ? 'selected' : '' }}>
                                {{ $origine->testo() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($mostraAssegnato ?? true)
                    <!-- Filtro Assegnato A -->
                    <div class="col-md-3">
                        <label class="form-label">Assegnato A</label>
                        <select id="assegnato_a_id" name="assegnato_a_id" class="form-select form-select-sm form-select-solid">
                            <option value="">Tutti gli utenti</option>
                            <option value="non_assegnato" {{ request('assegnato_a_id') == 'non_assegnato' ? 'selected' : '' }}>
                                Non Assegnato
                            </option>
                            @if(isset($responsabili))
                                @foreach($responsabili as $responsabile)
                                    <option value="{{ $responsabile->id }}"
                                        {{ request('assegnato_a_id') == $responsabile->id ? 'selected' : '' }}>
                                        {{ $responsabile->nome }} {{ $responsabile->cognome }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @endif

                <!-- Filtro Impianto -->
                <div class="col-md-3">
                    <label class="form-label">Impianto</label>
                    <select id="impianto_id" name="impianto_id" class="form-select form-select-sm form-select-solid">
                        <option value="">Tutti gli impianti</option>
                        @if(isset($impianti))
                            @foreach($impianti as $impianto)
                                <option value="{{ $impianto->id }}"
                                    {{ request('impianto_id') == $impianto->id ? 'selected' : '' }}>
                                    {{ $impianto->nome_impianto }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Filtro Data Creazione -->
                <div class="col-md-3">
                    <label class="form-label">Periodo Creazione</label>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" name="data_da" id="data_da" class="form-control form-control-solid form-control-sm"
                                   placeholder="Da"
                                   value="{{ request('data_da') }}"
                                   data-inputmask="'mask': '99/99/9999'"
                            >
                        </div>
                        <div class="col-6">
                            <input type="text" name="data_a" id="data_a" class="form-control form-control-solid form-control-sm"
                                   placeholder="A"
                                   value="{{ request('data_a') }}"
                                   data-inputmask="'mask': '99/99/9999'"
                            >
                        </div>
                    </div>
                </div>

                <!-- Filtri rapidi -->
                <div class="col-md-3">
                    <label class="form-label">Filtri Rapidi</label>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="radio" name="filtro_rapido" value="miei_ticket"
                                       {{ request('filtro_rapido') == 'miei_ticket' ? 'checked' : '' }} id="miei_ticket">
                                <label class="form-check-label fs-7" for="miei_ticket">
                                    I Miei Ticket
                                </label>
                            </div>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="radio" name="filtro_rapido" value="non_assegnati"
                                       {{ request('filtro_rapido') == 'non_assegnati' ? 'checked' : '' }} id="non_assegnati">
                                <label class="form-check-label fs-7" for="non_assegnati">
                                    Non Assegnati
                                </label>
                            </div>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="radio" name="filtro_rapido" value="urgenti"
                                       {{ request('filtro_rapido') == 'urgenti' ? 'checked' : '' }} id="urgenti">
                                <label class="form-check-label fs-7" for="urgenti">
                                    Urgenti
                                </label>
                            </div>
                        </div>
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
