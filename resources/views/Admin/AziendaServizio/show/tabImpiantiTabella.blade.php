
    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
            <thead>
            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                <th class="">Nome Impianto</th>
                <th class="">Indirizzo</th>
                <th class="">Tipologia</th>
                <th class="">Amministratore</th>
                <th class="text-end">Unità</th>
                <th class="">Stato</th>
            </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
            @foreach($records as $impianto)
                <tr>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold mb-1">{{$impianto->nome_impianto}}</span>
                            <span class="text-muted fs-7">#: {{$impianto->id }}</span>
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
                    <td class="text-end">
               {{$impianto->unita_immobiliari_count}}
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
