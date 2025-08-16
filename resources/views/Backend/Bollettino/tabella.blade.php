<div class="table-responsive">
    <table class="table table-row-bordered" id="tabella-elenco">
        <thead>
            <tr class="fw-bolder fs-6 text-gray-800">
                <th class="">Unita Immobiliare</th>
                <th class="">Periodo</th>
                <th class="text-end">Importo</th>
                <th class="">Metodo Pagamento</th>
                <th class="">Pdf Allegato</th>
                <th class="">Nome File Originale</th>
                <th class="">Mime Type</th>
                <th class="">Dimensione File</th>
                <th class="">Caricato Da</th>
                <th class="">Data Caricamento</th>
                <th class="text-center">Visualizzato</th>
                <th class="">Data Visualizzazione</th>
                <th class="">Stato Pagamento</th>
                <th class="text-end">Importo Pagato</th>
                <th class="text-center">Data Scadenza</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
<td class="">{{$record->unita_immobiliare_id}}</td>
<td class="">{{$record->periodo_id}}</td>
<td class="text-end">{{\App\importo($record->importo)}}</td>
<td class="">{{$record->metodo_pagamento}}</td>
<td class="">{{$record->pdf_allegato}}</td>
<td class="">{{$record->nome_file_originale}}</td>
<td class="">{{$record->mime_type}}</td>
<td class="">{{$record->dimensione_file}}</td>
<td class="">{{$record->caricato_da_id}}</td>
<td class="">{{$record->data_caricamento}}</td>
<td class="text-center">@if($record->visualizzato)
    <i class="fas fa-check fs-3" style="color: #26C281;"></i>
@endif</td>
<td class="">{{$record->data_visualizzazione}}</td>
<td class="">{{$record->stato_pagamento}}</td>
<td class="text-end">{{\App\importo($record->importo_pagato)}}</td>
<td class="text-center">{{$record->data_scadenza?->format('d/m/Y')}}</td>

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
