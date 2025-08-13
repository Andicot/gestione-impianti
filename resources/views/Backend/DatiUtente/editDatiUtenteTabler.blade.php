@extends('Tabler._layout._main')
@section('content')
    @includeWhen(session()->has('message'),'Tabler._components.alertMessage')
    <div class="card">
        <div class="card-body">
            @include('Tabler._components.alertErrori')
            @include('Tabler._components.alertMessage')
            @php($activeTab = session('activeTab', 'dati-utente'))
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link {{$activeTab === 'dati-utente' ? 'active' : ''}}" data-bs-toggle="tab" href="#tab_dati">Dati utente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$activeTab === 'dati-password' ? 'active' : ''}}" data-bs-toggle="tab" href="#tab_password">Password</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$activeTab === 'dati-email' ? 'active' : ''}}" data-bs-toggle="tab" href="#tab_email">Email</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade {{$activeTab === 'dati-utente' ? 'active show' : ''}}" id="tab_dati" role="tabpanel">
                    <h3>Modifica i tuoi dati</h3>
                    <div class="pt-3"></div>
                    <form method="POST" action="{{ action([$controller,'update'],'dati-utente') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        @php($record=Auth::user())
                        <div class="row">
                            <div class="col-md-6">
                                @include('Tabler._inputs_v.inputText',['campo'=>'cognome','placeholder'=>'Il tuo cognome','required'=>true,'autocomplete'=>'family-name'])
                            </div>
                            <div class="col-md-6">
                                @include('Tabler._inputs_v.inputText',['campo'=>'nome','placeholder'=>'Il tuo nome','required'=>true,'autocomplete'=>'given-name'])
                            </div>
                            <div class="col-md-6">
                                @include('Tabler._inputs_v.inputText',['campo'=>'telefono','placeholder'=>'Il tuo numero di telefono','required'=>false,'altro'=>'maxlength="16"'])
                            </div>
                        </div>
                        <div class="w-100 text-center mt-3">
                            <button class="btn btn-primary" type="submit">Salva modifiche</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade {{$activeTab === 'dati-password' ? 'active show' : ''}}" id="tab_password" role="tabpanel">
                    <h3>Modifica la tua password</h3>
                    <div class="pt-3"></div>
                    <form method="POST" action="{{ action([$controller,'update'],'dati-password') }}">
                        @csrf
                        @method('PATCH')
                        @php($record=Auth::user())
                        @php($record->password_attuale=null)
                        @php($record->password_confirmation=null)
                        @php($record->password=null)
                        <div class="row">
                            <div class="col-md-6">
                                @include('Tabler._inputs_v.inputPassword',[
                                    'campo'=>'password_attuale',
                                    'label'=>'Password attuale',
                                    'placeholder'=>'Password attuale',
                                    'required'=>true,
                                    'autocomplete'=>'current-password'
                                ])
                            </div>
                            <div class="col-md-6">
                                @include('Tabler._inputs_v.inputPassword',[
                                    'campo'=>'password',
                                    'label'=>'Nuova password',
                                    'placeholder'=>'Scegli una password sicura',
                                    'required'=>true,
                                    'autocomplete'=>'new-password',
                                    'help'=>'La password deve essere lunga almeno 8 caratteri'
                                ])
                            </div>
                            <div class="col-md-6">
                                @include('Tabler._inputs_v.inputPassword',[
                                    'campo'=>'password_confirmation',
                                    'label'=>'Conferma la password',
                                    'placeholder'=>'Ripeti la tua password',
                                    'required'=>true,
                                    'autocomplete'=>'new-password'
                                ])
                            </div>
                        </div>
                        <div class="w-100 text-center mt-3">
                            <button class="btn btn-primary" type="submit">Modifica password</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade {{$activeTab === 'dati-email' ? 'active show' : ''}}" id="tab_email" role="tabpanel">
                    <h3>Modifica il tuo indirizzo email</h3>
                    <div class="pt-3"></div>
                    <form method="POST" action="{{ action([$controller,'update'],'dati-email') }}">
                        @csrf
                        @method('PATCH')
                        @php($record=Auth::user())
                        <div class="row">
                            <div class="col-md-6">
                                @include('Tabler._inputs_v.inputText',[
                                    'campo'=>'email',
                                    'placeholder'=>'Il tuo indirizzo email',
                                    'required'=>true,
                                    'autocomplete'=>'email'
                                ])
                            </div>
                        </div>
                        <div class="w-100 text-center mt-3">
                            <button class="btn btn-primary" type="submit">Modifica email</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('customScript')
    <script>
        $(function () {
            $('#password').maxlength({
                customMaxAttribute: 8,
                warningClass: "badge badge-danger",
                limitReachedClass: "badge badge-success",
                allowOverMax: true
            });
            $('#password_confirmation').maxlength({
                customMaxAttribute: 8,
                warningClass: "badge badge-danger",
                limitReachedClass: "badge badge-success",
                allowOverMax: true
            });

            // Attivazione automatica del tab corretto dopo il submit
            @if(session('activeTab'))
            var activeTab = '{{ session('activeTab') }}';
            // Rimuovi active da tutti i tab
            $('.nav-link').removeClass('active');
            $('.tab-pane').removeClass('active show');

            // Attiva il tab corretto
            $('a[href="#' + activeTab + '"]').addClass('active');
            $('#' + activeTab).addClass('active show');
            @endif
        });
    </script>
@endpush
