<div class="table-responsive">
    <table class="table table-row-bordered align-middle" >
        <thead>
        <tr class="fw-bolder fs-6 text-gray-800">
            <th class="">Unità</th>
            <th class="">Nominativo</th>
            <th class="">Tipologia</th>
            <th class="">Condomino</th>
            <th class="text-end min-w-70px">Azioni</th>
        </tr>
        </thead>
        <tbody class="">
        @forelse($records as $unita)
            <tr>
                <td>
                    <div class="d-flex flex-column ">
                        {{$unita->getDescrizioneCompleta()}}
                        <span class="text-muted fs-7">#: {{$unita->id}}</span>
                    </div>
                </td>
                <td>
                    <div>
                        {{$unita->nominativo_unita ?: 'Non specificato'}}
                    </div>
                </td>
                <td>
                    {{\App\Enums\TipoUnitaImmobiliareEnum::from($unita->tipologia)?->testo()}}
                </td>
                <td>
                    @if($unita->user_id && $unita->user)
                        <div class="d-flex flex-column">
                            <span>{{$unita->user->nome}} {{$unita->user->cognome}}</span>
                            <span class="text-muted fs-7">{{$unita->user->email}}</span>
                        </div>
                    @else
                        <span class="text-muted">Non assegnato</span>
                    @endif
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                        <i class="bi bi-three-dots fs-3"></i>
                    </button>
                    <div
                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                        data-kt-menu="true">
                        <div class="menu-item px-3">
                            <a href="{{action([\App\Http\Controllers\Aziendadiservizio\UnitaImmobiliareController::class,'show'],$unita->id)}}"
                               class="menu-link px-3">
                                Visualizza
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a href="{{action([\App\Http\Controllers\Aziendadiservizio\UnitaImmobiliareController::class,'edit'],$unita->id)}}"
                               class="menu-link px-3">
                               Modifica
                            </a>
                        </div>
                        @if(false)
                            <div class="separator my-2"></div>
                            <div class="menu-item px-3">
                                <a href="{{action([\App\Http\Controllers\Aziendadiservizio\LetturaConsumoController::class,'index'],'unita_id='.$unita->id)}}"
                                   class="menu-link px-3">
                                    <i class="fas fa-chart-line me-2"></i>Letture
                                </a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="{{action([\App\Http\Controllers\Aziendadiservizio\BollettinoController::class,'index'],'unita_id='.$unita->id)}}"
                                   class="menu-link px-3">
                                    <i class="fas fa-file-invoice me-2"></i>Bollettini
                                </a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="{{action([\App\Http\Controllers\Aziendadiservizio\DispositivoMisuraController::class,'index'],'unita_id='.$unita->id)}}"
                                   class="menu-link px-3">
                                    <i class="fas fa-microchip me-2"></i>Dispositivi
                                </a>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-10">
                    <div class="d-flex flex-column align-items-center">
                        <h3 class="text-muted">Nessuna unità immobiliare trovata</h3>


                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="w-100 text-center">
    {{$records->links()}}
</div>
