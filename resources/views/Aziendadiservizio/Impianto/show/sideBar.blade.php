<div class="card mb-5 mb-xl-8">
    <div class="card-body pt-15">
        <div class="d-flex flex-center flex-column mb-5">
            <div class="fs-3 text-gray-800 fw-bolder mb-1">
                {{$record->matricola_impianto}}
            </div>
            <div class="fs-4 text-gray-800 fw-bolder mb-1">
                {{$record->nome_impianto}}
            </div>
            <div class="fs-5 fw-bold text-muted mb-6">
                {!! $record->badgeStato() !!}

            </div>
        </div>

        {{-- Dettagli Impianto --}}
        <div class="fw-bolder mb-3">
            <h3>Dettagli Impianto</h3>
        </div>

        <div id="kt_customer_view_details" class="collapse show">
            <div class="fs-6">
                {{-- Tipologia --}}
                <div class="fw-bolder mt-5">Tipologia</div>
                <div class="text-gray-600">
                    @php
                        $tipologiaEnum = \App\Enums\TipologiaImpiantoEnum::from($record->tipologia);
                    @endphp
                    <span class="badge badge-light-info">{{$tipologiaEnum->testo()}}</span>
                </div>
                @role(\App\Enums\RuoliOperatoreEnum::admin->value)
                {{-- Azienda di Servizio --}}
                @if($record->azienda_servizio_id)
                    <div class="fw-bolder mt-5">Azienda di Servizio</div>
                    <div class="text-gray-600">
                        <span
                            class="text-gray-600 text-hover-primary">
                            {{$record->aziendaServizio->ragione_sociale}}
                        </span>
                    </div>
                @endif
                @endrole
                {{-- Amministratore --}}
                @if($record->amministratore_id)
                    <div class="fw-bolder mt-5">Amministratore</div>
                    <div class="text-gray-600">
                        <a href="{{action([\App\Http\Controllers\Backend\AmministratoreController::class,'show'],$record->amministratore_id)}}"
                           class="text-gray-600 text-hover-primary">
                            {{$record->amministratore->ragione_sociale ?: ($record->amministratore->user->nome . ' ' . $record->amministratore->user->cognome)}}
                        </a>
                    </div>
                @else
                    <div class="fw-bolder mt-5">Amministratore</div>
                    <div class="text-gray-600">Non assegnato</div>
                @endif

                {{-- Indirizzo --}}
                <div class="fw-bolder mt-5">Indirizzo</div>
                <div class="text-gray-600">
                    {{$record->indirizzo}}
                    @if($record->cap || $record->citta)
                        <br>{{$record->cap}} {{$record->citta}}
                    @endif
                </div>

                {{-- Criteri di ripartizione --}}
                <div class="separator separator-dashed my-3"></div>
                <div class="fw-bolder mt-6">
                    <h3>Ripartizione Spese</h3>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="fw-bolder mt-5">Criterio Ripartizione</div>
                        <div class="text-gray-600">{{$record->criterio_ripartizione_numerica}}%</div>
                    </div>
                    <div class="col-lg-6">
                        <div class="fw-bolder mt-5">Quota Fissa</div>
                        <div class="text-gray-600">{{$record->percentuale_quota_fissa}}%</div>
                    </div>
                </div>

                @if($record->servizio)
                    <div class="fw-bolder mt-5">Servizio</div>
                    <div class="text-gray-600">{{$record->servizio}}</div>
                @endif

                <div class="separator separator-dashed my-3"></div>

                {{-- Statistiche veloci --}}
                <div class="fw-bolder mt-6">
                    <h3>Statistiche</h3>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="fw-bolder mt-5">Unità Immobiliari</div>
                        <div class="text-gray-600 fs-2 fw-bold text-primary">
                            {{$record->unita_immobiliari_count}}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fw-bolder mt-5">Dispositivi</div>
                        <div class="text-gray-600 fs-2 fw-bold text-success">
                            {{$record->dispositivi_count}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="fw-bolder mt-5">Concentratori</div>
                        <div class="text-gray-600 fs-2 fw-bold text-info">
                            da implementare
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fw-bolder mt-5">Periodi</div>
                        <div class="text-gray-600 fs-2 fw-bold text-warning">
                            da implementare
                        </div>
                    </div>
                </div>

                {{-- Note --}}
                @if($record->note)
                    <div class="separator separator-dashed my-3"></div>
                    <div class="fw-bolder mt-5">Note</div>
                    <div class="text-gray-600">
                        {!! nl2br(e($record->note)) !!}
                    </div>
                @endif

                {{-- Data registrazione --}}
                <div class="separator separator-dashed my-3"></div>
                <div class="fw-bolder mt-5">Data Registrazione</div>
                <div class="text-gray-600">{{$record->created_at->format('d/m/Y H:i')}}</div>

                @if($record->updated_at != $record->created_at)
                    <div class="fw-bolder mt-3">Ultima Modifica</div>
                    <div class="text-gray-600">{{$record->updated_at->format('d/m/Y H:i')}}</div>
                @endif
            </div>
        </div>
    </div>
</div>
