@extends('Metronic._layout._main')
@section('toolbar')
    <div class="d-flex flex-wrap align-items-center gap-2">
        @includeWhen(isset($testoCerca),'Metronic._components.ricerca')
                @includeWhen(isset($ordinamenti),'Metronic._components.ordinamento')
        @isset($testoNuovo)
            <a class="btn btn-sm btn-primary fw-bold" data-targetZ="kt_modal" data-toggleZ="modal-ajax" href="{{action([$controller,'create'])}}"><span class="d-md-none">+</span><span class="d-none d-md-block">{{$testoNuovo}}</span></a>
        @endisset
    </div>
@endsection
@section('content')
   <div class="card">
    <div class="card-body">
        <div id="tabella">
    @include('Backend.UnitaImmobiliare.tabella')
</div>
    </div>
</div>
@endsection
@push('customScript')
    <script>
        var indexUrl = '{{action([$controller,'index'])}}';
        $(function () {
            searchHandler();
        });
    </script>
@endpush
