@php
    $html_tag_data = [];
    $title = __('admin_list');
    $breadcrumbs = ["/admin"=>"White Dent", ""=>__('menu_users')]
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
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-end w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#newAdmin">
                        <span>{{ __('new_employee') }}</span>
                        <i data-acorn-icon="plus"></i>
                    </button>
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
                            <!-- Hover Controls Start -->
                            <div class="row">
                                <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-1">
                                    <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                                        <input class="form-control form-control-sm datatable-search" placeholder="{{ __('search') }}" data-datatable="#datatableHover" />
                                        <span class="search-magnifier-icon">
                                          <i data-acorn-icon="search"></i>
                                        </span>
                                        <span class="search-delete-icon d-none">
                                          <i data-acorn-icon="close"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-7 col-lg-9 col-xxl-10 text-end mb-1">
                                    <div class="d-inline-block">
                                        <div class="d-inline-block datatable-export" data-datatable="#datatableHover">
                                            <button
                                                class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                                <i data-acorn-icon="download"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <button class="dropdown-item export-excel" type="button">Excel</button>
                                                <button class="dropdown-item export-cvs" type="button">Csv</button>
                                            </div>
                                        </div>
                                        <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#datatableHover">
                                            <button
                                                class="btn btn-outline-muted btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                                10 {{ __('results') }}
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <a class="dropdown-item" href="#">5 {{ __('results') }}</a>
                                                <a class="dropdown-item active" href="#">10 {{ __('results') }}</a>
                                                <a class="dropdown-item" href="#">20 {{ __('results') }}</a>
                                                <a class="dropdown-item" href="#">50 {{ __('results') }}</a>
                                                <a class="dropdown-item" href="#">100 {{ __('results') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Hover Controls End -->

                            <!-- Hover Table Start -->
                            <table class="data-table data-table-pagination data-table-standard responsive nowrap hover" id="datatableHover" data-order='[[ 0, "desc" ]]'>
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('fullname') }}</th>
                                    <th>{{ __('login') }}</th>
                                    <th>{{ __('menu_roles') }}</th>
                                    <th>{{ __('mobile') }}</th>
                                    <th>{{ __('active') }}</th>
                                    <th>Abunəlik bitir</th>
                                    <th>{{ __('operation') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $key => $admin)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$admin->fullname}}</td>
                                        <td class="text-alternate">{{$admin->login}}</td>
                                        <td class="text-alternate">{{$admin->getRoleNames()->first()}}</td>
                                        <td class="text-alternate">{{$admin->mobile}}</td>
                                        <td class="text-alternate">{{ $admin->is_active ? __('active_status') : __('inactive_status') }}</td>
                                        <td class="text-alternate">{{$admin->subscription_ends_at}}</td>
                                        <td class="text-alternate">
                                            <a href="{{route('admin.edit',$admin->id)}}" class="btn btn-primary btn-sm">{{ __('edit') }}</a>
                                            <button class="btn btn-sm btn-warning subscriptionBtn"
                                                    data-url="{{ route('admin.subscription', $admin->id) }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#subscriptionModal">
                                                Abunəlik
                                            </button>
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

        <div class="modal modal-right fade" id="newAdmin" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('new_employee') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('admin.add')}}">
                            @csrf
                            <label>{{ __('name') }}</label>
                            <input type="text" name="name" class="form-control" placeholder="{{ __('name') }}" value="{{old('name')}}" required>
                            <label>{{ __('surname') }}</label>
                            <input type="text" name="surname" class="form-control" placeholder="{{ __('surname') }}" value="{{old('surname')}}" required>
                            <label>{{ __('mobile') }}</label>
                            <input type="text" name="mobile" class="form-control" placeholder="{{ __('mobile') }}" value="{{old('mobile')}}" required>
                            <label>{{ __('login') }}</label>
                            <input type="text" name="login" class="form-control" placeholder="{{ __('login') }}" value="{{old('login')}}" required>
                            <label>{{ __('password') }}</label>
                            <input type="text" name="password" class="form-control" placeholder="{{ __('password') }}" required>
                            <label>{{ __('menu_roles') }}</label>
                            <select class="form-select" name="role_name" required>
                                <option value="">{{ __('select') }}</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->name}}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <label>{{ __('is_doctor') }}</label>
                            <select class="form-select" name="is_doctor" required>
                                <option value="1">{{ __('yes') }}</option>
                                <option value="0">{{ __('no_text') }}</option>
                            </select>
                            <hr>
                            <button type="submit" class="btn btn-primary">{{ __('add') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="subscriptionModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Abunəlik</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="subscriptionContent">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
