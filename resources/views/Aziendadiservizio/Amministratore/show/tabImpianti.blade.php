<div class="d-flex justify-content-between gap-5">
    {{-- Contatore Totale Impianti --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-primary">
                <i class="fas fa-building text-primary fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Totale Impianti</div>
            <div class="fs-4 fw-bold text-gray-800">{{ $records->total() }}</div>
        </div>
    </div>

    {{-- Contatore Impianti Attivi --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-success">
                <i class="fas fa-check-circle text-success fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Impianti Attivi</div>
            <div class="fs-4 fw-bold text-success">{{ $records->where('stato', 'attivo')->count() }}</div>
        </div>
    </div>

    {{-- Contatore Impianti Dismessi --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-warning">
                <i class="fas fa-exclamation-triangle text-warning fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Dismessi</div>
            <div class="fs-4 fw-bold text-warning">{{ $records->where('stato', 'dismesso')->count() }}</div>
        </div>
    </div>

    {{-- Contatore Unità Immobiliari Totali --}}
    <div class="d-flex align-items-center">
        <div class="symbol symbol-40px symbol-circle me-3">
            <div class="symbol-label bg-light-info">
                <i class="fas fa-home text-info fs-4"></i>
            </div>
        </div>
        <div>
            <div class="fs-7 text-muted fw-bold">Unità Immobiliari</div>
            <div class="fs-4 fw-bold text-info">--</div>
        </div>
    </div>
</div>


{{-- Header tabella con filtri --}}
<div class="border-0 pt-6">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center position-relative my-1">
            <i class="fas fa-search fs-3 position-absolute ms-5"></i>
            <input type="text" data-kt-customer-table-filter="search"
                   class="form-control form-control-solid form-control-sm w-250px ps-13"
                   placeholder="Cerca impianti..."/>
        </div>
        <div class="d-flex justify-content-end" >
            {{-- Pulsante Nuovo Impianto a destra --}}
            @if($record->attivo)
                <a href="{{action([\App\Http\Controllers\Aziendadiservizio\ImpiantoController::class,'create'],'azienda_id='.$record->id)}}"
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fs-4"></i>
                    Nuovo Impianto
                </a>
            @endif
        </div>
    </div>
</div>

{{-- Tabella Impianti --}}
<div class="pt-0">
    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
            <thead>
            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                <th class="">Nome Impianto</th>
                <th class="">Indirizzo</th>
                <th class="">Tipologia</th>
                <th class="">Amministratore</th>
                <th class="">Unità</th>
                <th class="">Stato</th>
            </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
            @foreach($records as $impianto)
                <tr>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold mb-1">{{$impianto->nome_impianto}}</span>
                            <span class="text-muted fs-7">Cod: {{$impianto->id }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800">{{$impianto->indirizzo}}</span>
                            <span class="text-muted fs-7">{{$impianto->cap}} {{$impianto->citta}}</span>
                        </div>
                    </td>
                    <td>
                            <span class="badge badge-light-info">
                                {{\App\Enums\TipologiaImpiantoEnum::from($impianto->tipologia)->testo()}}
                            </span>
                    </td>
                    <td>
                        @if($impianto->amministratore)
                            <div class="d-flex flex-column">
                                <span class="text-muted fs-7">{{$impianto->amministratore->ragione_sociale}}</span>
                            </div>
                        @else
                            <span class="text-muted">Non assegnato</span>
                        @endif
                    </td>
                    <td>
                            <span class="badge badge-light-primary fs-7">
                                -- unità
                            </span>
                    </td>
                    <td>
                        @php
                            $statoEnum = \App\Enums\StatoGenericoEnum::from($impianto->stato);
                        @endphp
                        <span class="badge badge-light-{{$statoEnum->colore()}}">{{$statoEnum->testo()}}</span>
                    </td>
                    <td class="text-end">

                        <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary " data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                            <i class="bi bi-three-dots fs-3"></i>
                        </button>
                        <div
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                            data-kt-menu="true">
                            <div class="menu-item px-3">
                                <a href="{{action([\App\Http\Controllers\Aziendadiservizio\ImpiantoController::class,'show'],$impianto->id)}}"
                                   class="menu-link px-3">
                                    Visualizza
                                </a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="{{action([\App\Http\Controllers\Aziendadiservizio\ImpiantoController::class,'edit'],$impianto->id)}}"
                                   class="menu-link px-3">
                                    Modifica
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="w-100 text-center">
        {{$records->links()}}
    </div>
</div>

{{-- Script per filtri e ricerca --}}
@push('customScript')
    <script>
        $(document).ready(function () {
            // TODO: Implementare filtri quando saranno pronti i dati

            // Ricerca
            $('[data-kt-customer-table-filter="search"]').on('keyup', function () {
                console.log('Ricerca:', this.value);
                // Implementare logica di ricerca
            });

            // Filtro stato
            $('select[data-control="select2"]').on('change', function () {
                console.log('Filtro cambiato:', this.value);
                // Implementare logica di filtro
            });
        });
    </script>
@endpush
