<div class="table-responsive">
    <table class="table table-row-bordered" id="tabella-elenco">
        <thead>
            <tr class="fw-bolder fs-6 text-gray-800">
                <th class="">Azienda Servizio</th>
                <th class="">Impianto</th>
                <th class="">Scala</th>
                <th class="">Piano</th>
                <th class="">Interno</th>
                <th class="">Nominativo Unita</th>
                <th class="">Tipologia</th>
                <th class="text-end">Millesimi Riscaldamento</th>
                <th class="text-end">Millesimi Acs</th>
                <th class="text-end">Metri Quadri</th>
                <th class="">Corpo Scaldante</th>
                <th class="">Contatore Acs Numero</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
<td class="">{{$record->azienda_servizio_id}}</td>
<td class="">{{$record->impianto_id}}</td>
<td class="">{{$record->scala}}</td>
<td class="">{{$record->piano}}</td>
<td class="">{{$record->interno}}</td>
<td class="">{{$record->nominativo_unita}}</td>
<td class="">{{$record->tipologia}}</td>
<td class="text-end">{{\App\importo($record->millesimi_riscaldamento)}}</td>
<td class="text-end">{{\App\importo($record->millesimi_acs)}}</td>
<td class="text-end">{{\App\importo($record->metri_quadri)}}</td>
<td class="">{{$record->corpo_scaldante}}</td>
<td class="">{{$record->contatore_acs_numero}}</td>

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
