{{-- Tabella tickets --}}
<div class="table-responsive">
    <table class="table table-row-bordered" id="tabella-elenco">
        <thead>
        <tr class="fw-bolder fs-6 text-gray-800">
            <th class="">Ticket</th>
            <th class="">Titolo</th>
            <th class="">Stato</th>
            <th class="">Priorità</th>
            <th class="">Categoria</th>
            <th class="">Creato Da</th>
            <th class="">Assegnato A</th>
            <th class="">Data Creazione</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
            <tr>
                {{-- Numero Ticket --}}
                <td class="">
                    <div>#{{ $record->id }}</div>
                    @if($record->impianto)
                        <div class="text-muted">{{ $record->impianto->nome_impianto }}</div>
                    @endif
                </td>

                {{-- Titolo --}}
                <td class="">
                    <div>{{ Str::limit($record->titolo, 40) }}</div>
                    @if($record->unitaImmobiliare)
                        <div class="text-muted small">
                            Unità: {{ $record->unitaImmobiliare->scala }} - {{ $record->unitaImmobiliare->interno }}
                        </div>
                    @endif
                </td>

                {{-- Stato --}}
                <td class="">
                    @php
                        $statoEnum = \App\Enums\StatoTicketEnum::from($record->stato);
                    @endphp
                    <span class="badge badge-light-{{ $statoEnum->colore() }}">{{ $statoEnum->testo() }}</span>
                </td>

                {{-- Priorità --}}
                <td class="">
                    @php
                        $prioritaEnum = \App\Enums\PrioritaTicketEnum::from($record->priorita);
                    @endphp
                    <span class="badge badge-light-{{ $prioritaEnum->colore() }}">{{ $prioritaEnum->testo() }}</span>
                </td>

                {{-- Categoria --}}
                <td class="">
                    @php
                        $categoriaEnum = \App\Enums\CategoriaTicketEnum::from($record->categoria);
                    @endphp
                    <span class="badge badge-light-{{ $categoriaEnum->colore() }}">{{ $categoriaEnum->testo() }}</span>
                </td>

                {{-- Creato Da --}}
                <td class="">
                    <div>{{ $record->creadoDa->nome }} {{ $record->creadoDa->cognome }}</div>
                    @php
                        $origineEnum = \App\Enums\OrigineTicketEnum::from($record->origine);
                    @endphp
                    <div class="text-muted small">{{ $origineEnum->testo() }}</div>
                </td>

                {{-- Assegnato A --}}
                <td class="">
                    @if($record->assegnatoA)
                        <div>{{ $record->assegnatoA->nome }} {{ $record->assegnatoA->cognome }}</div>
                        @if($record->assegnatoA->ruolo)
                            @php
                                $ruoloEnum = \App\Enums\RuoliOperatoreEnum::from($record->assegnatoA->ruolo);
                            @endphp
                            <div class="text-muted small">{{ $ruoloEnum->testo() }}</div>
                        @endif
                    @else
                        <span class="badge badge-light-warning">Non Assegnato</span>
                    @endif
                </td>

                {{-- Data Creazione --}}
                <td class="">
                    <div>{{ $record->created_at->format('d/m/Y') }}</div>
                    <div class="text-muted small">{{ $record->created_at->format('H:i') }}</div>
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
