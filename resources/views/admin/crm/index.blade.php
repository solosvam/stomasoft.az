@php
    $html_tag_data = [];
    $title = __('menu_crm');
    $breadcrumbs = ["/admin"=> "White Dent", route('admin.crm.index') => "CRM", "#"=> __('menu_crm')]
@endphp
@extends('admin.layout',[ 'title'=>$title])
@section('js_page')
    <script src="{{asset('backend/js/jquery.inputmask.js')}}"></script>
@endsection
@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                    @include('admin._layout.breadcrumb',['breadcrumbs'=>$breadcrumbs])
                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">

                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <div class="row gx-4 gy-5">
            <div class="col-12">
                <!-- Biography Start -->
                <div class="card mb-5">
                    <div class="card-body">
                        <label class="top-label">
                            <input type="text" class="form-control form-control-lg multi-source-search-input" data-source="crm" placeholder="{{__('search_patient')}}" autofocus>
                            <span>{{__('search_patient')}}</span>
                            <div class="invalid-feedback result" style="max-height: 250px;overflow-y: auto"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
