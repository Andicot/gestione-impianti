{{-- Informazioni principali --}}
<div class="row mb-8">
    <div class="col-12">
        <div class="card bg-light-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px symbol-circle me-5">
                        <div class="symbol-label bg-primary">
                            <i class="fas fa-user-tie text-white fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="text-gray-800 fw-bold mb-1">
                            {{$record->ragione_sociale ?: ($record->user->nome . ' ' . $record->user->cognome)}}
                        </h3>
                        <div class="text-muted fw-semibold">
                            Amministratore di Condominio

                        </div>
                    </div>
                    <div class="text-end">
                        @if($record->attivo)
                            <span class="badge badge-light-success fs-7">Attivo</span>
                        @else
                            <span class="badge badge-light-danger fs-7">Non Attivo</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Dettagli dell'amministratore --}}
<div class="row">
    {{-- Colonna sinistra --}}
    <div class="col-md-6">
        {{-- Dati anagrafici --}}
        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-id-card me-2"></i>Dati Anagrafici
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'ragione_sociale','label'=>'Ragione Sociale'])
                    </div>
                    <div class="col-6 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'codice_fiscale','label'=>'Codice Fiscale'])
                    </div>
                    <div class="col-6 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'partita_iva','label'=>'Partita IVA'])
                    </div>
                </div>
            </div>
        </div>

        {{-- Dati utente --}}
        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user me-2"></i>Dati Utente
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'nome','valore'=>$record->user->nome,'label'=>'Nome'])
                    </div>
                    <div class="col-6 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'cognome','valore'=>$record->user->cognome,'label'=>'Cognome'])
                    </div>
                    <div class="col-12 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'email','valore'=>$record->user->email,'label'=>'Email'])
                    </div>
                    <div class="col-6 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'telefono','valore'=>$record->user->telefono,'label'=>'Telefono Personale'])
                    </div>
                    <div class="col-6 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'ruolo','valore'=>\App\Enums\RuoliOperatoreEnum::from($record->user->ruolo ?? 'amministratore_condominio')->testo(),'label'=>'Ruolo'])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Colonna destra --}}
    <div class="col-md-6">
        {{-- Sede e contatti --}}
        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-map-marker-alt me-2"></i>Sede e Contatti
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'indirizzo_ufficio','label'=>'Indirizzo Ufficio'])
                    </div>
                    <div class="col-6 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'cap_ufficio','label'=>'CAP'])
                    </div>
                    <div class="col-6 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'citta_ufficio','label'=>'Città'])
                    </div>
                    <div class="col-12 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'telefono_ufficio','label'=>'Telefono Ufficio'])
                    </div>
                </div>
            </div>
        </div>

        {{-- Referente --}}
        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-tie me-2"></i>Referente
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'nome_referente','label'=>'Nome Referente'])
                    </div>
                    <div class="col-6 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'cognome_referente','label'=>'Cognome Referente'])
                    </div>
                    <div class="col-12 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'email_referente','label'=>'Email Referente'])
                    </div>
                    <div class="col-12 mb-4">
                        @include('Metronic._inputs_v.showInput',['campo'=>'telefono_referente','label'=>'Telefono Referente'])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Statistiche rapide --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie me-2"></i>Statistiche Rapide
                </h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border border-gray-300 border-dashed rounded py-3">
                            <div class="fs-1 fw-bold text-primary">{{$record->impianti()->count()}}</div>
                            <div class="fs-7 fw-semibold text-muted">Impianti Gestiti</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border border-gray-300 border-dashed rounded py-3">
                            <div class="fs-1 fw-bold text-success">{{$record->impianti()->where('stato', 'attivo')->count()}}</div>
                            <div class="fs-7 fw-semibold text-muted">Impianti Attivi</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border border-gray-300 border-dashed rounded py-3">
                            <div class="fs-1 fw-bold text-info">
                                {{$record->impianti()->withCount('unitaImmobiliari')->get()->sum('unita_immobiliari_count')}}
                            </div>
                            <div class="fs-7 fw-semibold text-muted">Unità Immobiliari</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border border-gray-300 border-dashed rounded py-3">
                            <div class="fs-1 fw-bold text-warning">--</div>
                            <div class="fs-7 fw-semibold text-muted">Condomini Totali</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Note e info aggiuntive --}}
@if($record->note || $record->user->ultimo_accesso)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-sticky-note me-2"></i>Informazioni Aggiuntive
                    </h3>
                </div>
                <div class="card-body">
                    @if($record->note)
                        <div class="mb-5">
                            <h5 class="fw-bold text-gray-800">Note:</h5>
                            <div class="text-gray-600">
                                {!! nl2br(e($record->note)) !!}
                            </div>
                        </div>
                    @endif

                    @if($record->user->ultimo_accesso)
                        <div class="mb-3">
                            <h5 class="fw-bold text-gray-800">Ultimo Accesso:</h5>
                            <div class="text-gray-600">
                                {{$record->user->ultimo_accesso->format('d/m/Y H:i')}}
                                <span class="text-muted">
                                    ({{$record->user->ultimo_accesso->diffForHumans()}})
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-6">
                            <h6 class="fw-bold text-gray-800">Data Registrazione:</h6>
                            <div class="text-gray-600">{{$record->created_at->format('d/m/Y H:i')}}</div>
                        </div>
                        @if($record->updated_at != $record->created_at)
                            <div class="col-6">
                                <h6 class="fw-bold text-gray-800">Ultima Modifica:</h6>
                                <div class="text-gray-600">{{$record->updated_at->format('d/m/Y H:i')}}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
