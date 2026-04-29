@php
    $html_tag_data = [];
    $title = __('menu_partners');
    $breadcrumbs = ["/admin"=>"White Dent", ""=>__('menu_partners')];
@endphp
@extends('admin.layout',['html_tag_data'=>$html_tag_data, 'title'=>$title])

@section('css')
    <link rel="stylesheet" href="{{asset('backend/css/vendor/datatables.min.css')}}"/>
@endsection

@section('js_page')
    <script src="{{asset('backend/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('backend/js/cs/datatable.extend.js')}}"></script>
    <script src="{{asset('backend/js/plugins/datatable.boxedvariations.js?v=1.2.1')}}"></script>
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
                        <span>{{__('new_partner')}}</span>
                        <i data-acorn-icon="plus"></i>
                    </button>

                    <button type="button" class="btn btn-outline-danger btn-icon btn-icon-end w-100 w-sm-auto ms-2" data-bs-toggle="modal" data-bs-target="#newPurchase">
                        <span>{{__('new_purchase')}}</span>
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
                                        <input class="form-control form-control-sm datatable-search" placeholder="{{__('search_placeholder')}}" data-datatable="#datatableHover" />
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
                                                <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                            </div>
                                        </div>
                                        <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#datatableHover">
                                            <button
                                                class="btn btn-outline-muted btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                                10 {{ __('results') }}
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <a class="dropdown-item" href="#">5 {{ __('results') }}</a>
                                                <a class="dropdown-item" href="#">10 {{ __('results') }}</a>
                                                <a class="dropdown-item active" href="#">20 {{ __('results') }}</a>
                                                <a class="dropdown-item" href="#">50 {{ __('results') }}</a>
                                                <a class="dropdown-item" href="#">100 {{ __('results') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Hover Controls End -->

                            <!-- Hover Table Start -->
                            <table class="data-table data-table-pagination data-table-standard responsive nowrap hover" id="datatableHover" data-order='[[ 0, "asc" ]]'>
                                <thead>
                                <tr>
                                    <th class="text-muted text-small text-uppercase">#</th>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('type') }}</th>
                                    <th>{{ __('mobile') }}</th>
                                    <th>{{ __('address') }}</th>
                                    <th>{{ __('balance') }}</th>
                                    <th>{{ __('operation') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($partners as $key => $partner)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$partner->name}}</td>
                                        <td>{{ $partner->type == 'supplier' ? __('supplier') : __('technician') }}</td>
                                        <td class="text-alternate">{{$partner->mobile}}</td>
                                        <td class="text-alternate">{{$partner->address}}</td>
                                        <td class="text-alternate">{{($partner->balance) ?? 0}} ₼</td>
                                        <td class="text-alternate">
                                            <a href="{{route('admin.partners.delete',$partner->id)}}" class="btn btn-danger btn-sm">{{ __('delete') }}</a>
                                            <a href="{{route('admin.partners.edit',$partner->id)}}" class="btn btn-primary btn-sm">{{ __('edit') }}</a>
                                            @if($partner->balance)
                                                <a href="{{route('admin.partners.doctor.balance',$partner->id)}}" class="btn btn-primary btn-sm">{{ __('debt_details') }}</a>
                                            @endif
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
                        <h5 class="modal-title">{{ __('new_partner') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('admin.partners.add')}}">
                            @csrf
                            <label>{{ __('name') }}</label>
                            <input type="text" name="name" class="form-control" placeholder="{{ __('name') }}" value="{{old('name')}}" required>
                            <label>{{ __('address') }}</label>
                            <input type="text" name="address" class="form-control" placeholder="{{ __('address') }}" value="{{old('address')}}" required>
                            <label>{{ __('mobile') }}</label>
                            <input type="text"
                                   name="mobile"
                                   maxlength="10"
                                   pattern="[0-9]{10}"
                                   inputmode="numeric"
                                   class="form-control"
                                   placeholder="{{ __('mobile_number') }}"
                                   value="{{ old('mobile') }}"
                                   required>
                            <label>Növ</label>
                            <select class="form-select" name="type">
                                <option value="supplier">{{ __('supplier') }}</option>
                                <option value="technician">{{ __('technician') }}</option>
                            </select>
                            <hr>
                            <button type="submit" class="btn btn-primary">{{ __('add') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal modal-right fade" id="newPurchase" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('new_purchase') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('admin.partners.buyitem') }}">
                            @csrf
                            <label>{{ __('select_partner') }}</label>
                            <select class="form-control" name="partner_id" required>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                @endforeach
                            </select>
                            <label>{{ __('amount') }}</label>
                            <input type="text" name="amount" class="form-control" placeholder="{{ __('amount') }}" required>
                            <label>{{ __('description') }}</label>
                            <input type="text" name="note" class="form-control" placeholder="{{ __('description') }}">
                            <hr>
                            <button type="submit" class="btn btn-primary">{{ __('add') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
