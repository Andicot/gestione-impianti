{{-- Tabella Documenti --}}
<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
        <thead>
        <tr class="fw-bolder fs-6 text-gray-800">
            <th class="">Documento</th>
            <th class="">Tipo</th>
            <th class="">Impianto</th>
            <th class="">Visibilità</th>
            <th class="">Scadenza</th>
            <th class="">Visualizzazioni</th>
            <th class="text-end min-w-70px">Azioni</th>
        </tr>
        </thead>
        <tbody class="fw-semibold text-gray-600">
        @forelse($records as $record)
            <tr>
                {{-- Documento --}}
                <td>
                    <div class="d-flex flex-column">
                        <span class="text-gray-800 fw-bold mb-1">{{Str::limit($record->nome_file_originale, 35)}}</span>
                        @if($record->descrizione)
                            <span class="text-muted fs-7">{{Str::limit($record->descrizione, 50)}}</span>
                        @endif
                        <span class="text-muted fs-8">
                            {{$record->created_at->format('d/m/Y H:i')}} • {{$record->dimensione_leggibile()}}
                        </span>
                    </div>
                </td>

                {{-- Tipo --}}
                <td>
                    <span class="badge badge-light-{{\App\Enums\TipoDocumentoEnum::from($record->tipo_documento)->colore()}}">
                        {{\App\Enums\TipoDocumentoEnum::from($record->tipo_documento)->testo()}}
                    </span>
                </td>

                {{-- Impianto --}}
                <td>
                    @if($record->impianto)
                        <div class="d-flex flex-column">
                            <span class="text-gray-800">{{$record->impianto->nome_impianto}}</span>
                            @if($record->unitaImmobiliare)
                                <span class="text-muted fs-7">
                                    {{$record->unitaImmobiliare->scala ? 'Scala ' . $record->unitaImmobiliare->scala . ', ' : ''}}Piano {{$record->unitaImmobiliare->piano}}, Int. {{$record->unitaImmobiliare->interno}}
                                </span>
                            @endif
                        </div>
                    @else
                        <span class="text-muted">Non specificato</span>
                    @endif
                </td>

                {{-- Visibilità --}}
                <td>
                    @if($record->pubblico)
                        <span class="badge badge-light-success">Pubblico</span>
                    @elseif($record->riservato_amministratori)
                        <span class="badge badge-light-warning">Riservato</span>
                    @else
                        <span class="badge badge-light-secondary">Privato</span>
                    @endif
                </td>

                {{-- Scadenza --}}
                <td>
                    @if($record->data_scadenza)
                        @if($record->scaduto())
                            <span class="badge badge-light-danger">Scaduto</span>
                            <div class="text-muted fs-8">{{$record->data_scadenza->format('d/m/Y')}}</div>
                        @elseif($record->in_scadenza())
                            <span class="badge badge-light-warning">In scadenza</span>
                            <div class="text-muted fs-8">{{$record->data_scadenza->format('d/m/Y')}}</div>
                        @else
                            <div class="text-gray-800">{{$record->data_scadenza->format('d/m/Y')}}</div>
                        @endif
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>

                {{-- Visualizzazioni --}}
                <td>
                    <span class="text-gray-800">{{$record->numero_visualizzazioni}}</span>
                    @if($record->ultima_visualizzazione)
                        <div class="text-muted fs-8">{{$record->ultima_visualizzazione->format('d/m/Y')}}</div>
                    @endif
                </td>

                {{-- Azioni --}}
                <td class="text-end">
                    <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                        <i class="bi bi-three-dots fs-3"></i>
                    </button>
                    <div
                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                        data-kt-menu="true">

                        {{-- Visualizza --}}
                        <div class="menu-item px-3">
                            <a href="{{action([\App\Http\Controllers\Backend\DocumentoController::class, 'show'], $record->id)}}"
                               class="menu-link px-3">
                                Visualizza
                            </a>
                        </div>

                        {{-- Download --}}
                        <div class="menu-item px-3">
                            <a href="{{route('documento.download', $record->id)}}"
                               class="menu-link px-3"
                               target="_blank">
                                Scarica
                            </a>
                        </div>

                        {{-- Modifica (solo se caricato dall'utente corrente) --}}
                        @if($record->caricato_da_id === Auth::id())
                            <div class="menu-item px-3">
                                <a href="{{action([\App\Http\Controllers\Backend\DocumentoController::class, 'edit'], $record->id)}}"
                                   class="menu-link px-3">
                                    Modifica
                                </a>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <div class="text-muted">Nessun documento trovato</div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
