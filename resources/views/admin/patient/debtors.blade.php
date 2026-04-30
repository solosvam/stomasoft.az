@php
    $html_tag_data = [];
    $title = __('debtors_list');

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
                                    <th>No</th>
                                    <th>{{ __('fullname') }}</th>
                                    <th>{{ __('gender') }}</th>
                                    <th>{{ __('age') }}</th>
                                    <th>{{ __('mobile') }}</th>
                                    <th>{{ __('debt') }}</th>
                                    <th>{{ __('operation') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($debtors as $debtor)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$debtor->patient->fullname}}</td>
                                        <td class="text-alternate">{{ $debtor->patient->sex ? __('male') : __('female') }}</td>
                                        <td class="text-alternate">{{ \Carbon\Carbon::parse($debtor->patient->bday)->age }}</td>
                                        <td class="text-alternate">{{$debtor->patient->mobile}}</td>
                                        <td class="text-alternate">{{$debtor->balance}} AZN</td>
                                        <td class="text-alternate">
                                            <a href="{{route('admin.crm.info',$debtor->patient->id)}}" class="btn btn-primary btn-sm">{{ __('menu_crm') }}</a>
                                            <a href="{{route('admin.patient.edit',$debtor->patient->id)}}" class="btn btn-primary btn-sm">{{ __('edit') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>{{ __('total_debt') }}</strong></td>
                                    <td colspan="2"><strong>{{ number_format($debtors->sum('balance'),2) }} AZN</strong></td>
                                </tr>
                                </tfoot>
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
                        <h5 class="modal-title">{{ __('new_patient') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('admin.patient.add')}}">
                            @csrf
                            <label>{{ __('name') }}</label>
                            <input type="text" name="name" class="form-control" placeholder="{{ __('name') }}" value="{{old('name')}}" required>
                            <label>{{ __('surname') }}</label>
                            <input type="text" name="surname" class="form-control" placeholder="{{ __('surname') }}" value="{{old('surname')}}" required>
                            <label>{{ __('gender') }}</label>
                            <select class="form-control form-select" name="sex">
                                <option value="0">{{ __('female') }}</option>
                                <option value="1">{{ __('male') }}</option>
                            </select>
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
                            <label>{{ __('birth_date') }}</label>
                            <input type="date" class="form-control" name="bday" value="{{old('bday')}}">
                            <label>{{ __('note') }}</label>
                            <textarea name="comment" class="form-control" placeholder="{{ __('note') }}">{{old('comment')}}</textarea>
                            <hr>
                            <button type="submit" class="btn btn-primary">{{ __('add') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
