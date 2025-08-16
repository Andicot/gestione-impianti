<div class="table-responsive">
    <table class="table table-row-bordered" id="tabella-elenco">
        <thead>
            <tr class="fw-bolder fs-6 text-gray-800">
                <th class="">Unita Immobiliare</th>
                <th class="">Periodo</th>
                <th class="">Dispositivo</th>
                <th class="">Tipo Consumo</th>
                <th class="">Categoria</th>
                <th class="text-end">Udr Attuale</th>
                <th class="text-end">Udr Precedente</th>
                <th class="text-end">Differenza Consumi</th>
                <th class="">Unita Misura</th>
                <th class="text-end">Costo Unitario</th>
                <th class="text-end">Costo Totale</th>
                <th class="text-center">Errore</th>
                <th class="">Descrizione Errore</th>
                <th class="text-center">Anomalia</th>
                <th class="">Importazione Csv</th>
                <th class="text-center">Data Lettura</th>
                <th class="">Ora Lettura</th>
                <th class="text-end">Comfort Termico Attuale</th>
                <th class="text-end">Temp Massima Sonde</th>
                <th class="text-center">Data Registrazione Temp Max</th>
                <th class="text-end">Temp Tecnica Tt16</th>
                <th class="text-end">Comfort Termico Periodo Prec</th>
                <th class="text-end">Temp Media Calorifero Prec</th>
                <th class="text-end">Udr Storico 1</th>
                <th class="text-end">Udr Totali</th>
                <th class="">Data Ora Dispositivo</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
<td class="">{{$record->unita_immobiliare_id}}</td>
<td class="">{{$record->periodo_id}}</td>
<td class="">{{$record->dispositivo->matricola}}</td>
<td class="">{{ucfirst($record->tipo_consumo)}}</td>
<td class="">{{\App\Enums\CategoriaConsumoEnum::tryFrom($record->categoria)->testo()}}</td>
<td class="text-end">{{\App\importo($record->udr_attuale)}}</td>
<td class="text-end">{{\App\importo($record->udr_precedente)}}</td>
<td class="text-end">{{\App\importo($record->differenza_consumi)}}</td>
<td class="">{{$record->unita_misura}}</td>
<td class="text-end">{{\App\importo($record->costo_unitario)}}</td>
<td class="text-end">{{\App\importo($record->costo_totale)}}</td>
<td class="text-center">@if($record->errore)
    <i class="fas fa-check fs-3" style="color: #26C281;"></i>
@endif</td>
<td class="">{{$record->descrizione_errore}}</td>
<td class="text-center">@if($record->anomalia)
    <i class="fas fa-check fs-3" style="color: #26C281;"></i>
@endif</td>
<td class="">{{$record->importazione_csv_id}}</td>
<td class="text-center">{{$record->data_lettura?->format('d/m/Y')}}</td>
<td class="">{{$record->ora_lettura}}</td>
<td class="text-end">{{\App\importo($record->comfort_termico_attuale)}}</td>
<td class="text-end">{{\App\importo($record->temp_massima_sonde)}}</td>
<td class="text-center">{{$record->data_registrazione_temp_max?->format('d/m/Y')}}</td>
<td class="text-end">{{\App\importo($record->temp_tecnica_tt16)}}</td>
<td class="text-end">{{\App\importo($record->comfort_termico_periodo_prec)}}</td>
<td class="text-end">{{\App\importo($record->temp_media_calorifero_prec)}}</td>
<td class="text-end">{{\App\importo($record->udr_storico_1)}}</td>
<td class="text-end">{{\App\importo($record->udr_totali)}}</td>
<td class="">{{$record->data_ora_dispositivo}}</td>

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
