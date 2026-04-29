@php
    $html_tag_data = [];
    $title = __('partner_edit');
    $breadcrumbs = ["/admin"=>"White Dent", ""=>__('partner_edit')];
@endphp
@extends('admin.layout',['html_tag_data'=>$html_tag_data, 'title'=>$title])

@section('css')

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
                <section class="scroll-section" id="userButtons">
                    <h2 class="small-title">{{ __('partner_edit') }}</h2>
                    <div class="card h-100-card">
                        <div class="card-body">
                            <form action="{{route('admin.partners.update',$partner->id)}}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('name') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="text" class="form-control" name="name" value="{{$partner->name}}" />
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('address') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="text" class="form-control" name="address" value="{{$partner->address}}" />
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('mobile') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="text"
                                               name="mobile"
                                               maxlength="10"
                                               pattern="[0-9]{10}"
                                               inputmode="numeric"
                                               class="form-control"
                                               placeholder="{{ __('mobile_number') }}"
                                               value="{{ $partner->mobile }}"
                                               required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('type') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <select class="form-select" name="type">
                                            <option value="supplier" @if($partner->type =='supplier') selected @endif>{{ __('supplier') }}</option>
                                            <option value="technician" @if($partner->type =='technician') selected @endif>{{ __('technician') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row mt-5">
                                    <div class="col-sm-8 col-md-9 col-lg-10 ms-auto">
                                        <button type="submit" class="btn btn-primary">{{ __('update') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
