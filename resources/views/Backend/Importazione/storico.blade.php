@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('importazione.index') }}"
           class="btn btn-sm btn-primary fw-bold">
            Nuova Importazione
        </a>
    </div>
@endsection

@section('content')
    {{-- Statistiche rapide --}}
    @if($importazioni->count() > 0)
        <div class="row mb-6">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fs-2x text-success me-4"></i>
                            <div>
                                <div class="fs-2 fw-bold text-success">{{ $importazioni->where('stato', 'completato')->count() }}</div>
                                <div class="fs-6 text-gray-700">Completate</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fs-2x text-warning me-4"></i>
                            <div>
                                <div class="fs-2 fw-bold text-warning">{{ $importazioni->where('stato', 'con_errori')->count() }}</div>
                                <div class="fs-6 text-gray-700">Con Errori</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock fs-2x text-primary me-4"></i>
                            <div>
                                <div class="fs-2 fw-bold text-primary">{{ $importazioni->where('stato', 'in_elaborazione')->count() }}</div>
                                <div class="fs-6 text-gray-700">In Elaborazione</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-times-circle fs-2x text-danger me-4"></i>
                            <div>
                                <div class="fs-2 fw-bold text-danger">{{ $importazioni->where('stato', 'errore')->count() }}</div>
                                <div class="fs-6 text-gray-700">Errori</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            @if($importazioni->count() > 0)
                <div class="table-responsive">
                    <table class="table table-row-bordered" id="tabella-elenco">
                        <thead>
                        <tr class="fw-bolder fs-6 text-gray-800">
                            <th class="">File</th>
                            <th class="">Impianto</th>
                            <th class="">Tipo</th>
                            <th class="">Stato</th>
                            <th class="">Righe</th>
                            <th class="">Caricato da</th>
                            <th class="">Data</th>
                            <th class="text-end"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($importazioni as $importazione)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(str_contains($importazione->nome_file, '.csv'))
                                            <span class="text-success me-2">CSV</span>
                                        @else
                                            <span class="text-primary me-2">EXCEL</span>
                                        @endif
                                        <span class="text-dark fw-bolder">{{ Str::limit($importazione->nome_file, 30) }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($importazione->impianto)
                                        <span class="text-muted">{{ Str::limit($importazione->impianto->nome_impianto, 25) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($importazione->tipo_caricamento === 'automatico_ip')
                                        <span class="badge badge-light-warning">Automatico IP</span>
                                    @else
                                        <span class="badge badge-light-info">Manuale</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($importazione->stato)
                                        @case('completato')
                                            <span class="badge badge-light-success">Completato</span>
                                            @break
                                        @case('con_errori')
                                            <span class="badge badge-light-warning">Con Errori</span>
                                            @break
                                        @case('in_elaborazione')
                                            <span class="badge badge-light-primary">In Elaborazione</span>
                                            @break
                                        @case('errore')
                                            <span class="badge badge-light-danger">Errore</span>
                                            @break
                                        @default
                                            <span class="badge badge-light-secondary">{{ ucfirst($importazione->stato) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="text-dark fw-bolder">{{ number_format($importazione->righe_elaborate) }}</div>
                                    @if($importazione->righe_errore > 0)
                                        <div class="text-danger fs-7">{{ number_format($importazione->righe_errore) }} errori</div>
                                    @endif
                                    @if($importazione->dispositivi_nuovi > 0)
                                        <div class="text-success fs-7">{{ number_format($importazione->dispositivi_nuovi) }} nuovi</div>
                                    @endif
                                </td>
                                <td>
                                    @if($importazione->caricatoDa)
                                        <div class="text-dark">{{ $importazione->caricatoDa->nome }} {{ $importazione->caricatoDa->cognome }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-dark">{{ $importazione->created_at->format('d/m/Y') }}</div>
                                    <div class="text-muted fs-7">{{ $importazione->created_at->format('H:i') }}</div>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('importazione.dettaglio', $importazione->id) }}"
                                       class="btn btn-bg-light btn-active-color-primary btn-sm me-1">
                                        Dettaglio
                                    </a>
                                    @if($importazione->path_file && Storage::disk('public')->exists($importazione->path_file))
                                        <a href="{{ Storage::disk('public')->url($importazione->path_file) }}"
                                           class="btn btn-bg-light btn-active-color-primary btn-sm"
                                           download>
                                            Scarica
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginazione --}}
                @if($importazioni instanceof \Illuminate\Pagination\LengthAwarePaginator )
                    <div class="w-100 text-center">
                        {{$importazioni->links()}}
                    </div>
                @endif
            @else
                <div class="text-center py-10">
                    <h4 class="text-muted mt-3">Nessuna importazione trovata</h4>
                    <p class="text-muted">Non sono ancora state effettuate importazioni di file.</p>
                    <a href="{{ route('importazione.index') }}"
                       class="btn btn-primary">
                        Inizia Prima Importazione
                    </a>
                </div>
            @endif
        </div>
    </div>


@endsection
