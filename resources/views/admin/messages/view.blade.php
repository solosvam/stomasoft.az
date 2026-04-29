@php
    $html_tag_data = [];
    $title = 'Saytdan göndərilən mesajlar';
    $breadcrumbs = ["/admin"=>"White Dent", ""=>"Mesajlar"]
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
                            <table class="table">
                                <tr>
                                    <td>{{ __('date') }}</td>
                                    <td>{{$message->created_at}}</td>
                                    <td>{{ __('email') }}</td>
                                    <td>{{$message->email}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('fullname') }}</td>
                                    <td>{{$message->fullname}}</td>
                                    <td>{{ __('mobile') }}</td>
                                    <td>{{$message->phone}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('subject') }}</td>
                                    <td>{{$message->subject}}</td>
                                    <td>{{ __('message') }}</td>
                                    <td>{{$message->message}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>


    </div>
@endsection
