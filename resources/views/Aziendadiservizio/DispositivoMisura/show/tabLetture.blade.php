<div class="table-responsive">
    <table class="table table-row-bordered" id="tabella-letture">
        <thead>
        <tr class="fw-bolder fs-6 text-gray-800">
            <th class="">Data Lettura</th>
            <th class="">Categoria</th>
            <th class="">Tipo Consumo</th>
            <th class="">UDR Attuale</th>
            <th class="">UDR Precedente</th>
            <th class="">Differenza</th>
            <th class="">Costo Totale</th>
            <th class="">Periodo</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($records as $lettura)
            <tr>
                <td class="">
                    <div>{{ $lettura->created_at->format('d/m/Y') }}</div>
                    <div class="text-muted small">{{ $lettura->created_at->format('H:i:s') }}</div>
                </td>
                <td class="">
                    {!! $lettura->badgeCategoria() !!}
                </td>
                <td class="">
                    @if($lettura->tipo_consumo == 'volontario')
                        <span class="badge badge-light-success">Volontario</span>
                    @else
                        <span class="badge badge-light-warning">Involontario</span>
                    @endif
                </td>
                <td class="">
                    <span class="fw-bold text-primary">{{ \App\importo($lettura->udr_attuale) }}</span>
                </td>
                <td class="">{{ \App\importo($lettura->udr_precedente) }}</td>
                <td class="">
                    @php
                        $differenza = $lettura->udr_attuale - $lettura->udr_precedente;
                        $colorClass = $differenza > 0 ? 'text-success' : ($differenza < 0 ? 'text-danger' : 'text-muted');
                    @endphp
                    <span class="fw-bold {{ $colorClass }}">{{ \App\importo($differenza) }}</span>
                </td>
                <td class="">
                    @if($lettura->costo_totale)
                        <span class="fw-bold">€ {{ \App\importo($lettura->costo_totale) }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="">
                    @if($lettura->periodo)
                        <div>{{ $lettura->periodo->codice }}</div>
                        <div class="text-muted small">
                            {{ $lettura->periodo->data_inizio->format('d/m/Y') }} -
                            {{ $lettura->periodo->data_fine->format('d/m/Y') }}
                        </div>
                    @else
                        <span class="text-muted">Non assegnato</span>
                    @endif
                </td>
                <td class="text-end text-nowrap">
                    @if($lettura->errore)
                        <span class="badge badge-light-danger" title="{{ $lettura->descrizione_errore }}">
                                       <i class="fas fa-exclamation-triangle"></i> Errore
                                   </span>
                    @endif
                    @if($lettura->importazione_csv_id)
                        <span class="badge badge-light-info" title="Importato da CSV">
                                       <i class="fas fa-file-csv"></i> CSV
                                   </span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center py-10">
                    <div class="d-flex flex-column align-items-center">
                        <h3 class="text-muted">Nessuna lettura trovata</h3>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@if($records instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="w-100 text-center">
        {{ $records->links() }}
    </div>
@endif
