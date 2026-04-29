@php
    $html_tag_data = [];
    $title = __('menu_cashier');
    $breadcrumbs = ["/admin"=> "White Dent", route('admin.cashier.index') => __('menu_cashier')]
@endphp
@extends('admin.layout',[ 'title'=>$title])
@section('css')
    <link rel="stylesheet" href="{{asset('backend/css/vendor/datatables.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('backend/css/vendor/daterangepicker.css')}}"/>
    <style>
        .hide-money{
            -webkit-filter: blur(4px);
            -ms-filter: blur(4px);
            filter: blur(4px);
        }
    </style>
@endsection

@section('js_page')
    <script src="{{asset('backend/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('backend/js/cs/datatable.extend.js')}}"></script>
    <script src="{{asset('backend/js/plugins/datatable.boxedvariations.js?v=1.2.1')}}"></script>
    <script src="{{asset('backend/js/vendor/datepicker/moment.min.js')}}"></script>
    <script src="{{asset('backend/js/vendor/datepicker/daterangepicker.js')}}"></script>
    <script>
        $(document).ready(function() {
            initDatePicker();
        })
    </script>
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
                    <button type="button" class="btn btn-outline-info btn-icon btn-icon-end w-100 w-sm-auto cashier-net hide-money">
                        <span>{{ __('cash_net') }} : {{ $doctorCash?->balance ?? 0 }}</span>
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-end w-100 w-sm-auto ms-4" data-bs-toggle="offcanvas" data-bs-target="#filterCanvas" aria-controls="filterCanvas">
                        <i data-acorn-icon="filter"></i>
                        <span>{{ __('filter') }}</span>
                    </button>
                    <button type="button" class="btn btn-outline-success btn-icon btn-icon-end w-100 w-sm-auto ms-2" data-bs-toggle="modal" data-bs-target="#income">
                        <i data-acorn-icon="plus"></i>
                        <span>{{ __('income') }}</span>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-icon btn-icon-end w-100 w-sm-auto ms-2" data-bs-toggle="modal" data-bs-target="#expence">
                        <i data-acorn-icon="minus"></i>
                        <span>{{ __('expense') }}</span>
                    </button>
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <div class="row gx-4 gy-5">
            <div class="col-12">
                <div id="accordionCards">
                    <div class="card d-flex mb-2">
                        <div class="d-flex flex-grow-1" role="button" data-bs-toggle="collapse" data-bs-target="#incomeTable" aria-expanded="true" aria-controls="incomeTable">
                            <div class="card-body py-4">
                                <div class="btn btn-link list-item-heading p-0">{{ __('income_table') }}</div>
                            </div>
                        </div>
                        <div id="incomeTable" class="collapse show" data-bs-parent="#accordionCards">
                            <div class="card-body accordion-content pt-0">
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
                                <table class="data-table data-table-pagination data-table-standard responsive nowrap hover" id="datatableHover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('date') }}</th>
                                        <th>{{ __('patient') }}</th>
                                        <th>{{ __('amount') }}</th>
                                        <th>{{ __('payment_type') }}</th>
                                        <th>{{ __('note') }}</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($incomeLogs as $key => $log)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d.m.Y H:i') }}</td>
                                            <td>
                                                @if($log->patient)
                                                    <a href="{{ route('admin.crm.info', $log->patient->id) }}" target="_blank">
                                                        {{ $log->patient->fullname }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-success">
                                                <i data-acorn-icon="arrow-bottom" data-acorn-size="16" class="me-1"></i>
                                                <span class="fw-bold">{{ number_format($log->amount,2) }} ₼</span>
                                            </td>
                                            <td>
                                                @switch($log->method)
                                                    @case('cash')
                                                        <span class="badge bg-success">{{ __('cash') }}</span>
                                                        @break
                                                    @case('pos')
                                                        <span class="badge bg-primary">{{ __('pos') }}</span>
                                                        @break
                                                    @case('c2c')
                                                        <span class="badge bg-warning text-dark">{{ __('c2c') }}</span>
                                                        @break
                                                    @default
                                                        -
                                                @endswitch
                                            </td>
                                            <td>{{ $log->note ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <strong>{{ __('cash') }}:</strong> {{ number_format($incomeSummary->total_cash, 2) }} ₼
                                    &nbsp; | &nbsp;
                                    <strong>{{ __('c2c') }}:</strong> {{ number_format($incomeSummary->total_c2c, 2) }} ₼
                                    &nbsp; | &nbsp;
                                    <strong>{{ __('pos') }}:</strong> {{ number_format($incomeSummary->total_posterminal, 2) }} ₼
                                    &nbsp; | &nbsp;
                                    <strong>{{ __('total') }}:</strong> {{ number_format($incomeSummary->total_all, 2) }} ₼
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card d-flex mb-2">
                        <div class="d-flex flex-grow-1" role="button" data-bs-toggle="collapse" data-bs-target="#expenseTable" aria-expanded="true" aria-controls="expenseTable">
                            <div class="card-body py-4">
                                <div class="btn btn-link list-item-heading p-0">{{ __('expense_table') }}</div>
                            </div>
                        </div>
                        <div id="expenseTable" class="collapse" data-bs-parent="#accordionCards">
                            <div class="card-body accordion-content pt-0">
                                <div class="row">
                                    <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-1">
                                        <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                                            <input class="form-control form-control-sm datatable-search" placeholder="{{ __('search') }}" data-datatable="#expenseDatatable" />
                                            <span class="search-magnifier-icon"><i data-acorn-icon="search"></i></span>
                                            <span class="search-delete-icon d-none"><i data-acorn-icon="close"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-7 col-lg-9 col-xxl-10 text-end mb-1">
                                        <div class="d-inline-block">
                                            <div class="d-inline-block datatable-export" data-datatable="#expenseDatatable">
                                                <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                                    <i data-acorn-icon="download"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <button class="dropdown-item export-excel" type="button">Excel</button>
                                                    <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                                </div>
                                                <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#expenseDatatable">
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
                                </div>
                                <table class="data-table data-table-pagination data-table-standard responsive nowrap hover" id="expenseDatatable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('date') }}</th>
                                        <th>{{ __('partner') }}</th>
                                        <th>{{ __('amount') }}</th>
                                        <th>{{ __('payment_type') }}</th>
                                        <th>{{ __('note') }}</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @php $total = 0 @endphp
                                    @foreach($expenseLogs as $key => $log)
                                        @php $total += $log->amount; @endphp
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d.m.Y H:i') }}</td>
                                            <td>{{ $log->partner?->name ?? '-' }}</td>
                                            <td class="text-danger">
                                                <i data-acorn-icon="arrow-top" data-acorn-size="16" class="me-1"></i>
                                                <span class="fw-bold">{{ number_format($log->amount,2) }} ₼</span>
                                            </td>
                                            <td>
                                                @switch($log->method)
                                                    @case('cash')
                                                        <span class="badge bg-success">{{ __('cash') }}</span>
                                                        @break
                                                    @case('pos')
                                                        <span class="badge bg-primary">{{ __('pos') }}</span>
                                                        @break
                                                    @case('c2c')
                                                        <span class="badge bg-warning text-dark">{{ __('c2c') }}</span>
                                                        @break
                                                    @default
                                                        -
                                                @endswitch
                                            </td>
                                            <td>{{ $log->note ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <strong>{{ __('total') }}:</strong> {{ number_format($expenseSummary->total_all, 2) }} ₼
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="offcanvas offcanvas-bottom" style="height: 60vh;" tabindex="-1" id="filterCanvas" aria-labelledby="offcanvasBottomLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasBottomLabel">{{__('filter')}}</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body ">
                <form method="GET" action="{{route('admin.cashier.index')}}">
                    <label for="finance-date-filter">{{ __('start_end_dates') }}</label>
                    <input type="text" name="dates" class="form-control dateLimit" id="finance-date-filter" placeholder="{{ __('select') }}">
                    <button type="submit" class="btn btn-primary mt-2">{{ __('add') }}</button>
                </form>
            </div>
        </div>

        <div class="modal modal-right fade" id="expence" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('add_expense') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('admin.cashier.expence')}}">
                            @csrf
                            <label>{{ __('amount') }}</label>
                            <input name="amount" type="number" step="0.01" max="10000" min="0.1" class="form-control" placeholder="{{ __('enter_amount') }}" value="{{old('amount')}}" required>
                            <label>{{ __('expense_description') }}</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="{{ __('write_exoense_detail') }}" required>{{old('description')}}</textarea>
                            <button type="submit" class="btn btn-primary mt-2">{{ __('filter') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal modal-right fade" id="income" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('add_income') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('admin.cashier.income')}}">
                            @csrf
                            <label>{{ __('amount') }}</label>
                            <input name="amount" type="number" step="0.01" max="10000" min="0.1" class="form-control" placeholder="{{ __('enter_amount') }}" value="{{old('amount')}}" required>
                            <label>{{ __('income_description') }}</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="{{ __('write_income_detail') }}" required>{{old('description')}}</textarea>
                            <button type="submit" class="btn btn-primary mt-2">{{ __('add') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
