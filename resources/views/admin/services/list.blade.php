@php
    $html_tag_data = [];
    $title = __('service_list');

    $breadcrumbs = [
    "/admin"=>"White Dent",
    ""=>__('service_list')
    ];
@endphp
@extends('admin.layout',['html_tag_data'=>$html_tag_data, 'title'=>$title])

@section('js_page')
    <script src="{{asset('backend/js/plugins/lists.js')}}"></script>

    <script src="{{asset('backend/js/vendor/list.js')}}"></script>
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
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-end w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#newAdmin">
                        <span>{{ __('new_service_item') }}</span>
                        <i data-acorn-icon="plus"></i>
                    </button>
                    <!-- Tour Button End -->
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <div class="row">
            <div class="col">
                <section class="scroll-section" id="userButtons">
                    <h2 class="small-title">{{ __('menu_services') }}</h2>
                    <div class="row g-2" id="sortAndFilter">
                        <div class="col-12">
                            <div class="row gx-2">
                                <div class="col-12 col-sm mb-1 mb-sm-0">
                                    <div class="search-input-container shadow rounded-md bg-foreground mb-2">
                                        <input class="form-control search" type="text" autocomplete="off" placeholder="{{ __('search') }}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-0 h-100 align-content-center mb-2 custom-sort d-none d-sm-flex">
                                        <div class="col-3 col-sm-6 d-flex align-items-center">
                                            <div class="text-small">{{ __('service_name') }}</div>
                                        </div>
                                        <div class="col-3 col-sm-3 d-flex align-items-center">
                                            <div class="text-small">{{ __('activity') }}</div>
                                        </div>
                                        <div class="col-2 col-sm-1 d-flex align-items-center">
                                            <div class="text-small">{{ __('price') }}</div>
                                        </div>
                                        <div class="col-2 col-sm-1 d-flex align-items-center justify-content-end">
                                            <div class="text-small">{{ __('edit') }}</div>
                                        </div>
                                    </div>

                                    <div class="list scroll-out">
                                        <div class="scroll-by-count" data-count="20" data-childSelector=".scroll-child">
                                            @foreach($services as $service)
                                                <div class="h-auto sh-sm-5 mb-3 mb-sm-0 scroll-child">
                                                    <div class="row g-0 h-100 align-content-center">
                                                        <div class="col-3 col-sm-6 d-flex align-items-center category">{{$service->name}}</div>
                                                        <div class="col-3 col-sm-3 d-flex align-items-center text-muted category">{{ $service->active ? __('active_status') : __('inactive_status') }}</div>
                                                        <div class="col-2 col-sm-1 d-flex align-items-center text-muted category">{{ $service->price }}</div>
                                                        <div class="col-2 col-sm-1 d-flex align-items-center justify-content-sm-end text-muted sale">
                                                            <a href="{{route('admin.services.edit',$service->id)}}" class="btn btn-outline-secondary btn-sm ms-1" type="button">{{__('edit')}}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="modal modal-right fade" id="newAdmin" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('new_service_item') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('admin.services.add')}}">
                            @csrf
                            <label>{{ __('service_name') }}</label>
                            <input type="text" name="name" class="form-control" placeholder="{{ __('service_name') }}" value="{{old('name')}}" required>

                            <label>{{ __('price') }}</label>
                            <input type="text" name="price" class="form-control" placeholder="{{ __('price') }}" value="{{old('price')}}" required>
                            <hr>
                            <button type="submit" class="btn btn-primary">{{ __('add') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
