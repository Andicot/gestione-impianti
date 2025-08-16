<div class="table-responsive">
    <table class="table table-row-bordered align-middle">
        <thead>
        <tr class="fw-bolder fs-6 text-gray-800">
            <th class="">Unità</th>
            <th class="">Periodo</th>
            <th class="text-end">Importo</th>
            <th class="">Stato Pagamento</th>
            <th class="">Scadenza</th>
            <th class="">Visualizzato</th>
            <th class="text-end min-w-70px">Azioni</th>
        </tr>
        </thead>
        <tbody class="">
        @forelse($records as $bollettino)
            <tr>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{$bollettino->unitaImmobiliare->getDescrizioneCompleta()}}</span>
                        @if($bollettino->unitaImmobiliare->nominativo_unita)
                            <span class="text-muted fs-7">{{$bollettino->unitaImmobiliare->nominativo_unita}}</span>
                        @endif
                        <span class="text-muted fs-7">#: {{$bollettino->id}}</span>
                    </div>
                </td>
                <td>
                    @if($bollettino->periodo)
                        <div class="d-flex flex-column">
                            <span>{{$bollettino->periodo->codice}}</span>
                            <span class="text-muted fs-7">
                                {{$bollettino->periodo->data_inizio->format('d/m/Y')}} - {{$bollettino->periodo->data_fine->format('d/m/Y')}}
                            </span>
                        </div>
                    @else
                        <span class="text-muted">Non specificato</span>
                    @endif
                </td>
                <td class="text-end">
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{\App\importo($bollettino->importo)}}</span>
                        @if($bollettino->importo_pagato > 0)
                            <span class="text-success fs-7">Pagato: € {{number_format($bollettino->importo_pagato, 2, ',', '.')}}</span>
                        @endif
                    </div>
                </td>
                <td>
                    {!! $bollettino->badgeStatoPagamento() !!}
                </td>
                <td>
                    @if($bollettino->data_scadenza)
                        <div class="d-flex flex-column">
                            <span class="{{$bollettino->data_scadenza->isPast() && $bollettino->stato_pagamento != 'pagato' ? 'text-danger fw-bold' : ''}}">
                                {{$bollettino->data_scadenza->format('d/m/Y')}}
                            </span>
                            @if($bollettino->data_scadenza->isPast() && $bollettino->stato_pagamento != 'pagato')
                                <span class="text-danger fs-7">Scaduto</span>
                            @elseif($bollettino->data_scadenza->diffInDays(now()) <= 7 && $bollettino->stato_pagamento != 'pagato')
                                <span class="text-warning fs-7">In scadenza</span>
                            @endif
                        </div>
                    @else
                        <span class="text-muted">Non specificata</span>
                    @endif
                </td>
                <td>
                    @if($bollettino->data_visualizzazione)
                        <div class="d-flex flex-column">
                            <span class="badge badge-light-success fw-bold">
                                <i class="fas fa-eye me-1 text-success"></i>Sì
                            </span>
                            @if($bollettino->data_visualizzazione)
                                <span class="text-muted fs-7">{{$bollettino->data_visualizzazione->format('d/m/Y H:i')}}</span>
                            @endif
                        </div>
                    @else
                        <span class="badge badge-light-danger fw-bold">
                            <i class="fas fa-eye-slash me-1 text-danger"></i>No
                        </span>
                    @endif
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                        <i class="bi bi-three-dots fs-3"></i>
                    </button>
                    <div
                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                        data-kt-menu="true">
                        <div class="menu-item px-3">
                            <a href="{{action([\App\Http\Controllers\Backend\BollettinoController::class,'show'],$bollettino->id)}}"
                               class="menu-link px-3">
                              Visualizza
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a href="{{action([\App\Http\Controllers\Backend\BollettinoController::class,'edit'],$bollettino->id)}}"
                               class="menu-link px-3">
                               Modifica
                            </a>
                        </div>
                        @if($bollettino->path_file)
                            <div class="menu-item px-3">
                                <a href="{{route('bollettino.download',$bollettino->id)}}"
                                   class="menu-link px-3">
                                    Scarica PDF
                                </a>
                            </div>
                        @endif
                        <div class="separator my-2"></div>
                        @if(false)
                        @if($bollettino->stato_pagamento != 'pagato')
                            <div class="menu-item px-3">
                                <a href="{{action([\App\Http\Controllers\Backend\PagamentoController::class,'create'],'bollettino_id='.$bollettino->id)}}"
                                   class="menu-link px-3">
                                   Registra Pagamento
                                </a>
                            </div>
                        @endif
                        <div class="menu-item px-3">
                            <a href="{{action([\App\Http\Controllers\Backend\PagamentoController::class,'index'],'bollettino_id='.$bollettino->id)}}"
                               class="menu-link px-3">
                              Storico Pagamenti
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
                        <h3 class="text-muted">Nessun bollettino trovato</h3>
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
