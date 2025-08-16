@extends('Metronic._layout._main')

@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-3 mb-6">
        @isset($eliminabile)
            @if($eliminabile === true)
                <a href="{{action([$controller,'destroy'],$record->id)}}" class="btn btn-danger mt-3 btn-elimina" data-method="DELETE">
                    <i class="fas fa-trash"></i> Elimina
                </a>
            @endif
        @endisset
        <a href="{{action([$controller,'edit'],$record->id)}}" class="btn btn-sm btn-primary fw-bold">
            <i class="fas fa-edit"></i> Modifica
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4">
            @include('Backend.Impianto.show.sideBar')
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    {{-- Tabs di navigazione --}}
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                        @foreach($tabs as $item)
                            <li class="nav-item">
                                <a class="nav-link @if($tab==$item) active @endif"
                                   href="{{action([$controller,'tab'],[$record->id,$item])}}">
                                    <span class="nav-text fw-semibold fs-6">
                                        {{\Illuminate\Support\Str::of($item)->remove('tab_')->title()->replace('_',' ')}}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Contenuto delle tabs --}}
                    <div class="tab-content" id="tabContent">
                        <div class="tab-pane fade show active" role="tabpanel">

                            @include('Backend.Impianto.show.tab'.Str::of($tab)->remove('tab_')->title()->remove('_'))

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script>
        var indexUrl = '{{url()->current()}}';


        function resetFiltri() {
            location.href = indexUrl;
        }

        $(function () {
            eliminaHandler('Questo impianto verrà eliminato definitivamente');
            searchHandler(reinitializeMenus);

            // Handler per azioni generiche
            function azioneHandler() {
                $('.azione').click(function (e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    ajaxAzione(url);
                });
            }

            azioneHandler();
        });
    </script>
@endpush
