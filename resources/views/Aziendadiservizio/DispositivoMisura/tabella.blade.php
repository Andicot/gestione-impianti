<div class="table-responsive">
    <table class="table table-row-bordered" id="tabella-elenco">
        <thead>
            <tr class="fw-bolder fs-6 text-gray-800">
                <th class="">Matricola</th>
                <th class="">Nome Dispositivo</th>
                <th class="">Descrizione 1</th>
                <th class="">Descrizione 2</th>
                <th class="">Marca</th>
                <th class="">Modello</th>
                <th class="">Tipo</th>
                <th class="text-end">Offset</th>
                <th class="text-center">Data Installazione</th>
                <th class="">Stato</th>
                <th class="">Ubicazione</th>
                <th class="">Unita Immobiliare</th>
                <th class="">Impianto</th>
                <th class="">Concentratore</th>
                <th class="text-end">Ultimo Valore Rilevato</th>
                <th class="">Data Ultima Lettura</th>
                <th class="text-center">Creato Automaticamente</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
<td class="">{{$record->matricola}}</td>
<td class="">{{$record->nome_dispositivo}}</td>
<td class="">{{$record->descrizione_1}}</td>
<td class="">{{$record->descrizione_2}}</td>
<td class="">{{$record->marca}}</td>
<td class="">{{$record->modello}}</td>
<td class="">{{$record->tipo}}</td>
<td class="text-end">{{\App\importo($record->offset)}}</td>
<td class="text-center">{{$record->data_installazione?->format('d/m/Y')}}</td>
<td class="">{{$record->stato}}</td>
<td class="">{{$record->ubicazione}}</td>
<td class="">{{$record->unita_immobiliare_id}}</td>
<td class="">{{$record->impianto_id}}</td>
<td class="">{{$record->concentratore_id}}</td>
<td class="text-end">{{\App\importo($record->ultimo_valore_rilevato)}}</td>
<td class="">{{$record->data_ultima_lettura}}</td>
<td class="text-center">@if($record->creato_automaticamente)
    <i class="fas fa-check fs-3" style="color: #26C281;"></i>
@endif</td>

                    <td class="text-end text-nowrap">
                        <a data-targetZ="kt_modal" data-toggleZ="modal-ajax"
                           class="btn btn-sm btn-light btn-active-light-primary"
                           href="{{action([$controller,'edit'],$record->id)}}">Modifica</a>
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
