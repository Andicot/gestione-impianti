<div class="table-responsive">
    <table class="table table-row-bordered" id="tabella-elenco">
        <thead>
        <tr class="fw-bolder fs-6 text-gray-800">
            <th class="">Marca</th>
            <th class="">Modello</th>
            <th class="">Matricola</th>
            <th class="">Frequenza Scansione</th>
            <th class="">Stato</th>

            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
            <tr>
                <td class="">{{$record->marca}}</td>
                <td class="">{{$record->modello}}</td>
                <td class="">{{$record->matricola}}</td>
                <td class="">{{$record->frequenza_scansione}}</td>
                <td class="">{{$record->stato}}</td>
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
