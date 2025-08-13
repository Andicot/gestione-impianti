@extends('Metronic._layout._main')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card card-dashed flex-center min-w-150px my-4 p-6 mt-0">
                                <span class="fs-4 fw-bold  pb-1 px-2">
                                    Counter 1
                                </span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                                    <span data-kt-countup="true" data-kt-countup-value="34" class="counted"
                                          data-kt-initialized="1">{{'0'}}</span>
                                </span>
            </div>
        </div>
        <div class="col">
            <div class="card card-dashed flex-center min-w-150px my-3 p-6 mt-0">
                                <span class="fs-4 fw-bold  pb-1 px-2">
                                    Counter 2
                                </span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                                    <span data-kt-countup="true" data-kt-countup-value="34" class="counted"
                                          data-kt-initialized="1">{{'0'}}</span>
                                </span>
            </div>
        </div>
    </div>
@endsection
@push('customCss')
@endpush
@push('customScript')
@endpush
