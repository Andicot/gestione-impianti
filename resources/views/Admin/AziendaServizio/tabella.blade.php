<div class="table-responsive">
    <table class="table table-row-bordered" id="tabella-elenco">
        <thead>
        <tr class="fw-bolder fs-6 text-gray-800">
            <th class="">Ragione Sociale</th>
            <th class="">Telefono</th>
            <th class="">Email Aziendale</th>
            <th class="">Citta</th>
            <th class="">Referente</th>
            <th class="text-end">Impianti</th>
            <th class="text-end">Amministratori</th>
            <th class="text-center">Attiva</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
            <tr>
                <td class="">{{$record->ragione_sociale}}</td>
                <td class="">{{$record->telefono}}</td>
                <td class="">{{$record->email_aziendale}}</td>
                <td class="">{{$record->comune?->comuneConTarga()}}</td>
                <td class="">{{$record->cognome_referente.' '.$record->nome_referente}}</td>
                <td class="text-end">{{$record->impianti_count}}</td>
                <td class="text-end">{{$record->amministratori_count}}</td>
                <td class="text-center">
                    @if($record->attivo)
                        <i class="fas fa-check fs-3" style="color: #26C281;"></i>
                    @endif</td>

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
