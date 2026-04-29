@php
    $html_tag_data = [];
    $title = __('user_edit');
    $breadcrumbs = ["/admin"=>"White Dent", ""=>$title]
@endphp
@extends('admin.layout',['html_tag_data'=>$html_tag_data, 'title'=>$title])

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
                    <h2 class="small-title">{{ __('user_edit') }}</h2>
                    <div class="card h-100-card">
                        <div class="card-body">
                            <form action="{{route('admin.update',$user->id)}}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('name') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="text" class="form-control" name="name" value="{{$user->name}}" />
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('surname') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="text" class="form-control" name="surname" value="{{$user->surname}}" />
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('login') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="text" class="form-control" name="login" value="{{$user->login}}" />
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('mobile') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="text" class="form-control" name="mobile" value="{{$user->mobile}}" />
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('password_optional') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="text" class="form-control" name="password"  />
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('active') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <select class="form-select" name="is_active">
                                            <option value="1" {{ $user->is_active ? 'selected' : '' }}>{{ __('active_status') }}</option>
                                            <option value="0" {{ !$user->is_active ? 'selected' : '' }}>{{ __('inactive_status') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('menu_roles') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <select class="form-control" name="role_name" required>
                                            <option value="">{{ __('select') }}</option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->name}}" @if($user->hasRole($role->id)) selected @endif>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('is_doctor') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <select class="form-control" name="is_doctor" required>
                                            <option value="1" @if($user->is_doctor) selected @endif>{{ __('yes') }}</option>
                                            <option value="0" @if(!$user->is_doctor) selected @endif>{{ __('no_text') }}</option>
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
