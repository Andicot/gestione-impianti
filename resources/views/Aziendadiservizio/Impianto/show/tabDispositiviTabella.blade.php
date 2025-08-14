<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
        <thead>
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            <th class="">Dispositivo</th>
            <th class="">Tipo</th>
            <th class="">Ubicazione</th>
            <th class="">Ultima Lettura</th>
            <th class="">Concentratore</th>
            <th class="">Stato</th>
            <th class="text-end min-w-70px">Azioni</th>
        </tr>
        </thead>
        <tbody class="fw-semibold text-gray-600">
        @forelse($records as $dispositivo)
            <tr>
                <td>
                    <div class="d-flex flex-column">
                        <a href="{{action([\App\Http\Controllers\Aziendadiservizio\DispositivoMisuraController::class,'show'],$dispositivo->id)}}"
                           class="text-gray-800 fw-bold text-hover-primary mb-1">
                            {{$dispositivo->nome_dispositivo ?: 'Dispositivo '.$dispositivo->id}}
                        </a>
                        <span class="text-muted fs-7">{{$dispositivo->matricola}}</span>
                        @if($dispositivo->marca || $dispositivo->modello)
                            <span class="text-muted fs-8">{{$dispositivo->marca}} {{$dispositivo->modello}}</span>
                        @endif
                    </div>
                </td>
                <td>
                    @php
                        $tipoBadges = [
                            'udr' => ['bg' => 'success', 'icon' => 'thermometer-half'],
                            'contatore_acs' => ['bg' => 'info', 'icon' => 'tint'],
                            'contatore_gas' => ['bg' => 'warning', 'icon' => 'fire'],
                            'contatore_kwh' => ['bg' => 'primary', 'icon' => 'bolt'],
                            'diretto' => ['bg' => 'secondary', 'icon' => 'plug']
                        ];
                        $badge = $tipoBadges[$dispositivo->tipo] ?? ['bg' => 'light', 'icon' => 'microchip'];
                    @endphp
                    <span class="badge badge-light-{{$badge['bg']}}">
                            <i class="fas fa-{{$badge['icon']}} me-1"></i>
                            {{strtoupper($dispositivo->tipo)}}
                        </span>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        @if($dispositivo->unitaImmobiliare)
                            <span class="text-gray-800">
                                    @if($dispositivo->unitaImmobiliare->scala)
                                    Scala {{$dispositivo->unitaImmobiliare->scala}},
                                @endif
                                    Piano {{$dispositivo->unitaImmobiliare->piano}},
                                    Interno {{$dispositivo->unitaImmobiliare->interno}}
                                </span>
                            @if($dispositivo->unitaImmobiliare->nominativo_unita)
                                <span class="text-muted fs-7">{{$dispositivo->unitaImmobiliare->nominativo_unita}}</span>
                            @endif
                        @else
                            <span class="text-muted">Non assegnato</span>
                        @endif
                        @if($dispositivo->ubicazione)
                            <span class="text-muted fs-8">{{$dispositivo->ubicazione}}</span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        @if($dispositivo->ultimo_valore_rilevato)
                            <span class="text-gray-800 fw-bold">{{$dispositivo->ultimo_valore_rilevato}}</span>
                        @else
                            <span class="text-muted">Nessuna lettura</span>
                        @endif
                        @if($dispositivo->data_ultima_lettura)
                            <span class="text-muted fs-7">{{$dispositivo->data_ultima_lettura->format('d/m/Y H:i')}}</span>
                        @endif
                    </div>
                </td>
                <td>
                    @if($dispositivo->concentratore)
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fs-7">{{$dispositivo->concentratore->marca}} {{$dispositivo->concentratore->modello}}</span>
                            <span class="text-muted fs-8">{{$dispositivo->concentratore->matricola}}</span>
                            @php
                                $statoConc = \App\Enums\StatoGenericoEnum::from($dispositivo->concentratore->stato);
                            @endphp
                            <span class="badge badge-light-{{$statoConc->colore()}} fs-8 mt-1">{{$statoConc->testo()}}</span>
                        </div>
                    @else
                        <span class="text-muted">Non collegato</span>
                    @endif
                </td>
                <td>
                    {!! $dispositivo->badgeStat() !!}
                    @if($dispositivo->data_installazione)
                        <div class="text-muted fs-8 mt-1">
                            Inst: {{$dispositivo->data_installazione->format('d/m/Y')}}
                        </div>
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
                            <a href="{{action([\App\Http\Controllers\Aziendadiservizio\DispositivoMisuraController::class,'show'],$dispositivo->id)}}"
                               class="menu-link px-3">
                                <i class="fas fa-eye me-2"></i>Visualizza
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a href="{{action([\App\Http\Controllers\Aziendadiservizio\DispositivoMisuraController::class,'edit'],$dispositivo->id)}}"
                               class="menu-link px-3">
                                <i class="fas fa-edit me-2"></i>Modifica
                            </a>
                        </div>
                        <div class="separator my-2"></div>
                        @if(false)
                            <div class="menu-item px-3">
                                <a href="{{action([\App\Http\Controllers\Aziendadiservizio\LetturaConsumoController::class,'index'],'dispositivo_id='.$dispositivo->id)}}"
                                   class="menu-link px-3">
                                    <i class="fas fa-chart-line me-2"></i>Letture
                                </a>
                            </div>
                            @if($dispositivo->stato === 'attivo')
                                <div class="menu-item px-3">
                                    <a href="{{action([\App\Http\Controllers\Aziendadiservizio\AnomaliaRilevatumController::class,'index'],'dispositivo_id='.$dispositivo->id)}}"
                                       class="menu-link px-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Anomalie
                                    </a>
                                </div>
                            @endif

                            @if($dispositivo->stato === \App\Enums\StatoDispositivoEnum::attivo->value)
                                <div class="separator my-2"></div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-warning"
                                       onclick="cambiaStatoDispositivo({{$dispositivo->id}}, 'sostituito')">
                                        <i class="fas fa-exchange-alt me-2"></i>Sostituisci
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-danger"
                                       onclick="cambiaStatoDispositivo({{$dispositivo->id}}, 'guasto')">
                                        <i class="fas fa-times-circle me-2"></i>Segna Guasto
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-10">
                    <div class="d-flex flex-column align-items-center">
                        <h3 class="text-muted">Nessun dispositivo trovato</h3>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="w-100 text-center">
    {{$records->links()}}
</div>
