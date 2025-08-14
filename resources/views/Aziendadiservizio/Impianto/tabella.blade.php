<div class="table-responsive">
    <table class="table table-row-bordered" id="tabella-elenco">
        <thead>
        <tr class="fw-bolder fs-6 text-gray-800">

            <th class="">Nome Impianto</th>
            <th class="">Amministratore</th>
            <th class="">Indirizzo</th>
            <th class="">Stato</th>
            <th class="">Tipologia</th>
            <th class="">Servizio</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
            <tr>
                <td class="">
                    <div>{{$record->matricola_impianto}}</div>

                        <div class="text-muted">{{$record->nome_impianto}}</div>

                </td>
                <td class="">{{$record->amministratore?->ragione_sociale}}</td>
                <td class="">
                    @if($record->citta)
                        {{$record->comune?->comuneContarga()}}
                    @endif
                    @if($record->citta && $record->indirizzo)
                        <br>
                        @endif
                    @if($record->indirizzo)
                       <span class="small"> {{$record->indirizzo}}</span>
                    @endif
                </td>
                <td class="">{!! $record->badgeStato() !!}</td>
                <td class="">{{\App\Enums\TipologiaImpiantoEnum::tryFrom($record->tipologia)->testo()}}</td>
                <td class="">{{$record->servizio}}</td>
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
