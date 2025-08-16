<div class="card mb-5 mb-xl-8">
    <div class="card-body pt-15">
        <div class="d-flex flex-center flex-column mb-5">
            <div class="fs-3 text-gray-800 fw-bolder mb-1">
                {{$record->ragione_sociale ?: ($record->user->nome . ' ' . $record->user->cognome)}}
            </div>
            <div class="fs-5 fw-bold text-muted mb-6">
                @if($record->attivo)
                    <span class="badge badge-light-success">Attivo</span>
                @else
                    <span class="badge badge-light-danger">Non Attivo</span>
                @endif
            </div>
        </div>


        {{-- Dettagli Amministratore --}}
        <div class="fw-bolder mb-3">
            <h3>Dettagli Amministratore</h3>
        </div>

        <div id="kt_customer_view_details" class="collapse show">
            <div class="fs-6">
                {{-- Dati utente collegato --}}
                <div class="fw-bolder mt-5">Utente di Sistema</div>
                <div class="text-gray-600">
                    <div class="d-flex align-items-center">
                        <span class="me-3">{{$record->user->nome}} {{$record->user->cognome}}</span>
                        <span class="badge badge-light-{{$record->user->attivo ? 'success' : 'danger'}} fs-8">
                            {{$record->user->attivo ? 'Attivo' : 'Non Attivo'}}
                        </span>
                    </div>
                    <div class="text-muted fs-7 mt-1">
                        <a href="mailto:{{$record->user->email}}" class="text-muted text-hover-primary">
                            {{$record->user->email}}
                        </a>
                    </div>
                </div>
@if(false)
                {{-- Azienda di Servizio --}}
                @if($record->azienda_servizio_id)
                    <div class="fw-bolder mt-5">Azienda di Servizio</div>
                    <div class="text-gray-600">
                        <a href="{{action([\App\Http\Controllers\Aziendadiservizio\AziendaServizioController::class,'show'],$record->azienda_servizio_id)}}"
                           class="text-gray-600 text-hover-primary">
                            {{$record->aziendaServizio->ragione_sociale}}
                        </a>
                    </div>
                @endif
@endif
                {{-- Dati fiscali --}}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="fw-bolder mt-5">Codice Fiscale</div>
                        <div class="text-gray-600">{{$record->codice_fiscale ?: 'Non specificato'}}</div>
                    </div>
                    <div class="col-lg-6">
                        <div class="fw-bolder mt-5">Partita IVA</div>
                        <div class="text-gray-600">{{$record->partita_iva ?: 'Non specificata'}}</div>
                    </div>
                </div>

                {{-- Sede ufficio --}}
                <div class="fw-bolder mt-5">Indirizzo Ufficio</div>
                <div class="text-gray-600">
                    @if($record->indirizzo_ufficio || $record->citta_ufficio)
                        @if($record->indirizzo_ufficio)
                            {{$record->indirizzo_ufficio}}
                            @if($record->cap_ufficio || $record->citta_ufficio)
                                <br>
                            @endif
                        @endif
                        @if($record->cap_ufficio || $record->citta_ufficio)
                            {{$record->cap_ufficio}} {{$record->citta_ufficio}}
                        @endif
                    @else
                        Non specificato
                    @endif
                </div>

                {{-- Contatti --}}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="fw-bolder mt-5">Email</div>
                        <div class="text-gray-600">
                            <a href="mailto:{{$record->user->email}}" class="text-gray-600 text-hover-primary">
                                {{$record->user->email}}
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="fw-bolder mt-5">Telefono Ufficio</div>
                        <div class="text-gray-600">
                            @if($record->telefono_ufficio)
                                <a href="tel:{{$record->telefono_ufficio}}" class="text-gray-600 text-hover-primary">
                                    {{$record->telefono_ufficio}}
                                </a>
                            @else
                                Non specificato
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Telefono personale se disponibile --}}
                @if($record->user->telefono)
                    <div class="fw-bolder mt-5">Telefono Personale</div>
                    <div class="text-gray-600">
                        <a href="tel:{{$record->user->telefono}}" class="text-gray-600 text-hover-primary">
                            {{$record->user->telefono}}
                        </a>
                    </div>
                @endif

                <div class="separator separator-dashed my-3"></div>

                {{-- Referente --}}
                <div class="fw-bolder mt-6">
                    <h3>Referente</h3>
                </div>

                @if($record->nome_referente || $record->cognome_referente)
                    <div class="fw-bolder mt-5">Nominativo</div>
                    <div class="text-gray-600">{{trim($record->nome_referente.' '.$record->cognome_referente)}}</div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="fw-bolder mt-5">Email Referente</div>
                            <div class="text-gray-600">
                                @if($record->email_referente)
                                    <a href="mailto:{{$record->email_referente}}" class="text-gray-600 text-hover-primary">
                                        {{$record->email_referente}}
                                    </a>
                                @else
                                    Non specificata
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="fw-bolder mt-5">Telefono Referente</div>
                            <div class="text-gray-600">
                                @if($record->telefono_referente)
                                    <a href="tel:{{$record->telefono_referente}}" class="text-gray-600 text-hover-primary">
                                        {{$record->telefono_referente}}
                                    </a>
                                @else
                                    Non specificato
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-gray-500 fst-italic">Nessun referente specificato</div>
                @endif

                <div class="separator separator-dashed my-3"></div>

                {{-- Statistiche veloci --}}
                <div class="fw-bolder mt-6">
                    <h3>Statistiche</h3>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="fw-bolder mt-5">Impianti Gestiti</div>
                        <div class="text-gray-600 fs-2 fw-bold text-primary">
                            {{$record->impianti_count ?: $record->impianti()->count()}}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fw-bolder mt-5">Impianti Attivi</div>
                        <div class="text-gray-600 fs-2 fw-bold text-success">
                            {{$record->impianti()->where('stato', 'attivo')->count()}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="fw-bolder mt-5">Unità Immobiliari</div>
                        <div class="text-gray-600 fs-2 fw-bold text-info">
                            da implementare
                            @if(false)
                            {{$record->impianti()->withCount('unitaImmobiliari')->get()->sum('unita_immobiliari_count')}}
                                @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fw-bolder mt-5">Condomini</div>
                        <div class="text-gray-600 fs-2 fw-bold text-warning">
                            --
                        </div>
                    </div>
                </div>

                {{-- Ruolo --}}
                <div class="separator separator-dashed my-3"></div>
                <div class="fw-bolder mt-5">Ruolo</div>
                <div class="text-gray-600">
                    @php
                        $ruolo = \App\Enums\RuoliOperatoreEnum::from($record->user->ruolo ?? 'amministratore_condominio');
                    @endphp
                    <span class="badge {{$ruolo->badge()}}">
                        <i class="fas fa-{{$ruolo->icona()}} me-1"></i>
                        {{$ruolo->testo()}}
                    </span>
                </div>

                {{-- Ultimo accesso --}}
                @if($record->user->ultimo_accesso)
                    <div class="fw-bolder mt-5">Ultimo Accesso</div>
                    <div class="text-gray-600">{{$record->user->ultimo_accesso->format('d/m/Y H:i')}}</div>
                @endif

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
