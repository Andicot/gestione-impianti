<div class="card mb-5 mb-xl-8">
    <div class="card-body pt-15">
        <div class="d-flex flex-center flex-column mb-5">

            <div class="fs-3 text-gray-800 fw-bolder mb-1">{{$record->ragione_sociale}}</div>
            <div class="fs-5 fw-bold text-muted mb-6">
                @if($record->attivo)
                    <span class="badge badge-light-success">Attivo</span>
                @else
                    <span class="badge badge-light-danger">Non Attivo</span>
                @endif
            </div>
        </div>

        {{-- Sezione Azioni rapide --}}
        @if($record->attivo && false)
            {{-- TODO: Implementare quando saranno pronti i controller Impianto e Amministratore --}}
            <div class="d-flex flex-stack gap-3 mb-6">
                <a href="{{action([\App\Http\Controllers\Backend\ImpiantoController::class,'create'],'azienda_id='.$record->id)}}"
                   class="btn btn-light-primary btn-sm w-100">
                    <i class="fas fa-plus"></i> Nuovo Impianto
                </a>
                <a href="{{action([\App\Http\Controllers\Backend\AmministratoreController::class,'create'],'azienda_id='.$record->id)}}"
                   class="btn btn-light-info btn-sm w-100">
                    <i class="fas fa-user-plus"></i> Nuovo Admin
                </a>
            </div>
        @endif

        {{-- Dettagli Azienda --}}
        <div class="fw-bolder mb-3">
            <h3>Dettagli Azienda</h3>
        </div>

        <div id="kt_customer_view_details" class="collapse show">
            <div class="fs-6">
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

                {{-- Sede --}}
                <div class="fw-bolder mt-5">Indirizzo</div>
                <div class="text-gray-600">
                    @if($record->indirizzo || $record->citta)
                        @if($record->indirizzo)
                            {{$record->indirizzo}}
                            @if($record->cap || $record->citta)
                                <br>
                            @endif
                        @endif
                        @if($record->cap || $record->citta)
                            {{$record->cap}} {{$record->comune?->comuneContarga()}}
                        @endif
                    @else
                        Non specificato
                    @endif
                </div>

                {{-- Contatti aziendali --}}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="fw-bolder mt-5">Email Aziendale</div>
                        <div class="text-gray-600">
                            @if($record->email_aziendale)
                                <a href="mailto:{{$record->email_aziendale}}" class="text-gray-600 text-hover-primary">
                                    {{$record->email_aziendale}}
                                </a>
                            @else
                                Non specificata
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="fw-bolder mt-5">Telefono</div>
                        <div class="text-gray-600">
                            @if($record->telefono)
                                <a href="tel:{{$record->telefono}}" class="text-gray-600 text-hover-primary">
                                    {{$record->telefono}}
                                </a>
                            @else
                                Non specificato
                            @endif
                        </div>
                    </div>
                </div>

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
                            {{$record->impianti_count}}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fw-bolder mt-5">Amministratori</div>
                        <div class="text-gray-600 fs-2 fw-bold text-info">
                            {{$record->amministratori_count}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="fw-bolder mt-5">Impianti Attivi</div>
                        <div class="text-gray-600 fs-2 fw-bold text-success">
                            {{$record->impianti()->where('stato', 'attivo')->count()}}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fw-bolder mt-5">Admin Attivi</div>
                        <div class="text-gray-600 fs-2 fw-bold text-success">
                            {{$record->amministratori()->where('attivo', true)->count()}}
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
