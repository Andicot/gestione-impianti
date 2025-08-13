
    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
            <thead>
            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                <th class="">Ragione Sociale</th>
                <th class="">Contatti</th>
                <th class="text-end">Impianti</th>
                <th class="">Stato</th>
                <th class="text-end">Azioni</th>
            </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
            @foreach($records as $amministratore)
                <tr>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold mb-1">{{$amministratore->ragione_sociale}}</span>
                            <span class="text-muted fs-7">#: {{$amministratore->id }}</span>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800">{{$amministratore->email_referente}}</span>
                            @if($amministratore->telefono_ufficio)
                                <span class="text-muted fs-7">{{$amministratore->telefono_ufficio}}</span>
                            @endif
                        </div>
                    </td>

                    <td class="text-end">

                        {{\App\intero($record->impianti_count)}}

                    </td>
                    <td>
                        @if($amministratore->attivo)
                            <span class="badge badge-light-success">Attivo</span>
                        @else
                            <span class="badge badge-light-danger">Non Attivo</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary " data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="bi bi-three-dots fs-3"></i>
                        </button>
                        <div
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                            data-kt-menu="true">
                            <div class="menu-item px-3">
                                <a href="{{action([\App\Http\Controllers\Aziendadiservizio\AmministratoreController::class,'show'],$amministratore->id)}}"
                                   class="menu-link px-3">
                                    Visualizza
                                </a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="{{action([\App\Http\Controllers\Aziendadiservizio\AmministratoreController::class,'edit'],$amministratore->id)}}"
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
