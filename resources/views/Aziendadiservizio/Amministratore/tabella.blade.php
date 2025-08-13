<div class="table-responsive">
    <table class="table table-row-bordered" id="tabella-elenco">
        <thead>
        <tr class="fw-bolder fs-6 text-gray-800">
            <th class="">Ragione Sociale</th>
            <th class="">Telefono Ufficio</th>
            <th class="">Citta Ufficio</th>
            <th class="">Nominativo Referente</th>
            <th class="">Telefono Referente</th>
            <th class="text-end">Impianti</th>
            <th class="text-center">Attivo</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
            <tr>
                <td class="">{{$record->ragione_sociale}}</td>
                <td class="">{{$record->telefono_ufficio}}</td>
                <td class="">{{$record->citta_ufficio}}</td>
                <td class="">{{$record->cognome_referente.' '.$record->nome_referente}}</td>
                <td class="">{{$record->telefono_referente}}</td>
                <td class="text-end">{{$record->impianti_count}}</td>
                <td class="text-center">
                    @if($record->attivo)
                        <i class="fas fa-check fs-3" style="color: #26C281;"></i>
                    @endif
                </td>

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
