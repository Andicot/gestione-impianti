<div class="table-responsive">
    <table class="table table-row-bordered" id="tabella-elenco">
        <thead>
        <tr class="fw-bolder fs-6 text-gray-800">
            <th class="">Dispositivo</th>
            <th class="">Descrizione</th>
            <th class="">Tipo</th>
            <th class="">Ubicazione</th>
            <th class="text-end">Ultimo Valore</th>
            <th class="text-center">Data Installazione</th>
            <th class="">Stato</th>
            <th class="text-center">Creato Automaticamente</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
            <tr>
                {{-- Dispositivo (Matricola + Nome + Marca/Modello) --}}
                <td class="">
                    <div>{{$record->matricola}}</div>
                    @if($record->nome_dispositivo)
                        <div class="text-muted fs-7">{{$record->nome_dispositivo}}</div>
                    @endif
                    @if($record->marca || $record->modello)
                        <div class="text-muted fs-8">{{ trim($record->marca . ' ' . $record->modello) }}</div>
                    @endif
                </td>

                {{-- Descrizione (Descrizione 1 + 2) --}}
                <td class="">
                    @if($record->descrizione_1)
                        <div>{{$record->descrizione_1}}</div>
                    @endif
                    @if($record->descrizione_2)
                        <div class="text-muted fs-7">{{$record->descrizione_2}}</div>
                    @endif
                </td>

                {{-- Tipo + Offset --}}
                <td class="">
                    <div>{{$record->tipo}}</div>
                    @if($record->offset != 0)
                        <div class="text-muted fs-7">Offset: {{\App\importo($record->offset)}}</div>
                    @endif
                </td>

                {{-- Ubicazione (Ubicazione + IDs) --}}
                <td class="">
                    @if($record->ubicazione)
                        <div>{{$record->ubicazione}}</div>
                    @endif
                    @if($record->unita_immobiliare_id)
                        <div class="text-muted fs-7">UI: {{$record->unita_immobiliare_id}}</div>
                    @endif
                    @if($record->impianto_id)
                        <div class="text-muted fs-7">Impianto: {{$record->impianto_id}}</div>
                    @endif
                    @if($record->concentratore_id)
                        <div class="text-muted fs-7">Concentratore: {{$record->concentratore_id}}</div>
                    @endif
                </td>

                {{-- Ultimo Valore + Data Lettura --}}
                <td class="text-end">
                    @if($record->ultimo_valore_rilevato !== null)
                        <div>{{\App\importo($record->ultimo_valore_rilevato)}}</div>
                        @if($record->data_ultima_lettura)
                            <div class="text-muted fs-7">
                                {{\Carbon\Carbon::parse($record->data_ultima_lettura)->format('d/m/Y H:i')}}
                            </div>
                        @endif
                    @else
                        <div class="text-muted">-</div>
                    @endif
                </td>

                {{-- Data Installazione --}}
                <td class="text-center">{{$record->data_installazione?->format('d/m/Y')}}</td>

                {{-- Stato --}}
                <td class="">{!! $record->badgeStato() !!}</td>

                {{-- Creato Automaticamente --}}
                <td class="text-center">
                    @if($record->creato_automaticamente)
                        <i class="fas fa-check fs-3" style="color: #26C281;"></i>
                    @endif
                </td>

                {{-- Azioni --}}
                <td class="text-end text-nowrap">
                    <a data-targetZ="kt_modal" data-toggleZ="modal-ajax"
                       class="btn btn-sm btn-light btn-active-light-success"
                       href="{{action([$controller,'show'],$record->id)}}">Vedi</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($records instanceof \Illuminate\Pagination\LengthAwarePaginator )
    <div class="w-100 text-center">
        {{$records->links()}}
    </div>
@endif
