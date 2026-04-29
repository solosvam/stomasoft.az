@php
    $html_tag_data = [];
    $title = __('patient_list');

    $breadcrumbs = [
    "/admin"=>"White Dent",
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
    <script src="{{asset('backend/js/plugins/datatable.ajax.js?v=' . time()) }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
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
                            <!-- Add New Button Start -->
                            <button type="button" class="btn btn-outline-primary btn-icon btn-icon-end w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#newAdmin">
                                <span>{{ __('new_patient') }}</span>
                                <i data-acorn-icon="plus"></i>
                            </button>
                            <!-- Add New Button End -->
                        </div>
                        <!-- Top Buttons End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->

                <!-- Content Start -->
                <div class="data-table-rows slim">
                    <!-- Controls Start -->
                    <div class="row">
                        <!-- Search Start -->
                        <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">
                            <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                <input class="form-control datatable-search" placeholder="{{ __('search') }}" data-datatable="#datatableRowsAjax" />
                                <span class="search-magnifier-icon">
                                    <i data-acorn-icon="search"></i>
                                </span>
                                <span class="search-delete-icon d-none">
                                    <i data-acorn-icon="close"></i>
                                </span>
                            </div>
                        </div>
                        <!-- Search End -->

                        <div class="col-sm-12 col-md-7 col-lg-9 col-xxl-10 text-end mb-1">
                            <div class="d-inline-block">
                                <!-- Print Button Start -->
                                <button
                                    class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print"
                                    data-bs-delay="0"
                                    data-datatable="#datatableRowsAjax"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="{{ __('print') }}"
                                    type="button"
                                >
                                    <i data-acorn-icon="print"></i>
                                </button>
                                <!-- Print Button End -->

                                <!-- Export Dropdown Start -->
                                <div class="d-inline-block datatable-export" data-datatable="#datatableRowsAjax">
                                    <button class="btn p-0" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                      <span
                                          class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown"
                                          data-bs-delay="0"
                                          data-bs-placement="top"
                                          data-bs-toggle="tooltip"
                                          title="{{ __('export') }}"
                                      >
                                        <i data-acorn-icon="download"></i>
                                      </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <button class="dropdown-item export-copy" type="button">Copy</button>
                                        <button class="dropdown-item export-excel" type="button">Excel</button>
                                        <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                    </div>
                                </div>
                                <!-- Export Dropdown End -->

                                <!-- Length Start -->
                                <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#datatableRowsAjax" data-childSelector="span">
                                    <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                      <span
                                          class="btn btn-foreground-alternate dropdown-toggle"
                                          data-bs-toggle="tooltip"
                                          data-bs-placement="top"
                                          data-bs-delay="0"
                                          title="{{ __('item_count') }}"
                                      >
                                        10 Items
                                      </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <a class="dropdown-item" href="#">5 {{ __('items') }}</a>
                                        <a class="dropdown-item active" href="#">10 {{ __('items') }}</a>
                                        <a class="dropdown-item" href="#">20 {{ __('items') }}</a>
                                    </div>
                                </div>
                                <!-- Length End -->
                            </div>
                        </div>
                    </div>
                    <!-- Controls End -->

                    <!-- Table Start -->
                    <div class="data-table-responsive-wrapper">
                        <table id="datatableRowsAjax" class="data-table nowrap w-100">
                            <thead>
                            <tr>
                                <th class="text-muted text-small text-uppercase">#</th>
                                <th class="text-muted text-small text-uppercase">{{ __('fullname') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('gender') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('age') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('mobile') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('balance') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('note') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('operation') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- Table End -->
                </div>
                <!-- Content End -->

                <div class="modal modal-right fade" id="newAdmin" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Yeni Pasient</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{route('admin.patient.add')}}">
                                    @csrf
                                    <label>Ad</label>
                                    <input type="text" name="name" class="form-control" placeholder="Ad" value="{{old('name')}}" required>
                                    <label>Soyad</label>
                                    <input type="text" name="surname" class="form-control" placeholder="Soyad" value="{{old('surname')}}" required>
                                    <label>Cinsiyyət</label>
                                    <select class="form-control form-select" name="sex">
                                        <option value="0">Qadın</option>
                                        <option value="1">Kişi</option>
                                    </select>
                                    <label>Mobil</label>
                                    <input type="text"
                                           name="mobile"
                                           maxlength="10"
                                           pattern="[0-9]{10}"
                                           inputmode="numeric"
                                           class="form-control"
                                           placeholder="Mobil nömrə"
                                           value="{{ old('mobile') }}"
                                           required>
                                    <label>Doğum tarixi</label>
                                    <input type="text" class="form-control" name="bday" id="datePickerBasic" value="{{old('bday')}}">
                                    <label>Qeyd</label>
                                    <textarea name="comment" class="form-control" placeholder="Qeyd">{{old('comment')}}</textarea>
                                    <label>Ünvan</label>
                                    <input type="text" name="address" class="form-control" value="{{old('address')}}" placeholder="Ünvan">
                                    <label>FIN</label>
                                    <input type="text" name="fin" class="form-control" value="{{old('fin')}}" placeholder="FİN">
                                    <hr>
                                    <button type="submit" class="btn btn-primary">Əlavə et</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
