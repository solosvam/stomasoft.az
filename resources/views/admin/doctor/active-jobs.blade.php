@php
    $html_tag_data = [];
    $title = __('active_services_patients');

    $breadcrumbs = [
    "/admin"=>"StomaSoft",
    ""=>__('menu_patients')
    ];
@endphp
@extends('admin.layout',['html_tag_data'=>$html_tag_data, 'title'=>$title])

@section('css')
    <link rel="stylesheet" href="{{asset('backend/css/vendor/datatables.min.css')}}"/>
@endsection

@section('js_page')
    <script src="{{asset('backend/js/vendor/datatables.min.js')}}"></script>

    <script src="{{asset('backend/js/cs/datatable.extend.js')}}"></script>
    <script src="{{asset('backend/js/plugins/datatable.boxedvariations.js')}}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-sm-6">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{$title}}</h1>
                    @include('admin._layout.breadcrumb',['breadcrumbs'=>$breadcrumbs])
                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-sm-6 d-flex align-items-start justify-content-end">
                    <!-- Tour Button Start -->
                    <!-- Tour Button End -->
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <div class="row">
            <div class="col">
                <section class="scroll-section" id="hover">
                    <div class="card mb-5">
                        <div class="card-body">
                            <!-- Hover Table Start -->
                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('fullname') }}</th>
                                    <th>{{ __('mobile') }}</th>
                                    <th>{{ __('debt') }}</th>
                                    <th>{{ __('operation') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($doctors as $doctor)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$doctor->fullname}}</td>
                                        <td class="text-alternate">{{$doctor->mobile}}</td>
                                        <td class="text-alternate">{{$doctor->balance->balance}} AZN</td>
                                        <td class="text-alternate">
                                            <a href="{{route('admin.tcrm.info',$doctor->id)}}" class="btn btn-primary btn-sm">{{ __('menu_crm') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
