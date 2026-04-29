@php
     $html_tag_data = [];
     $title = __('patient_profile');
     $breadcrumbs = [
         "/admin"=> "White Dent",
         route('admin.crm.index') => __('menu_crm'),
         "#"=> $patient->fullname
     ];
@endphp
@extends('admin.layout',[ 'title'=>$title])
@section('css')
    <link rel="stylesheet" href="{{asset('backend/css/vendor/select2.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('backend/css/vendor/select2-bootstrap4.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('backend/css/crm.packages.table.css')}}"/>
    <link rel="stylesheet" href="{{asset('backend/css/vendor/dropzone.min.css')}}"/>
@endsection
@section('js_page')
    <script src="{{ asset('backend/js/vendor/dropzone.min.js') }}"></script>
    <script src="{{asset('backend/js/cs/dropzone.templates.js')}}"></script>
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

                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <div class="row gx-4 gy-5">
            <!-- Left Side Start -->
            <div class="col-12 col-xl-3 col-xxl-3">
                <!-- Biography Start -->
                <div class="card">
                    <div class="card-body mb-n5">
                        <button class="btn btn-sm btn-icon btn-icon-only btn-outline-quaternary mb-1 position-absolute" type="button" data-bs-toggle="modal" data-bs-target="#profitModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="acorn-icons acorn-icons-power undefined"><path d="M16 5C17.2447 6.37932 18 8.19116 18 10.1755 18 14.4969 14.4183 18 10 18 5.58172 18 2 14.4969 2 10.1755 2 8.19116 2.75527 6.37932 4 5M10 2 10 8"></path></svg>
                        </button>
                        <a href="{{ route('admin.print.service',$patient->id) }}" class="btn btn-sm btn-icon btn-icon-only btn-outline-danger mb-1 position-absolute" style="right: 20px">
                            <i data-acorn-icon="print" data-acorn-size="16" class="me-1"></i>
                        </a>
                        <div class="d-flex align-items-center flex-column mb-5">
                            <div class="d-flex align-items-center flex-column mb-4">
                                <div class="sw-13 position-relative mb-3">
                                    <img src="{{ asset('backend/img/profile/' . ($patient->sex == 0 ? 'female.png' : 'male.png')) }}" class="img-fluid rounded-xl" alt="thumb"/>
                                </div>
                                <div class="h5 mb-1">{{$patient->fullname}}</div>
                                <div class="text-muted">
                                    <i data-acorn-icon="mobile" data-acorn-size="16" class="me-1"></i>
                                    <span class="align-middle">{{$patient->mobile}}</span>
                                </div>
                                <div class="text-muted">
                                    <i data-acorn-icon="calendar" data-acorn-size="16" class="me-1"></i>
                                    <span class="align-middle">{{ \Carbon\Carbon::parse($patient->bday)->age }} {{__('age')}} | {{__('fin')}} {{$patient->fin}}</span>
                                </div>

                                <div class="text-muted">
                                    <i data-acorn-icon="pin" data-acorn-size="16" class="me-1"></i>
                                    <span class="align-middle">{{$patient->address}}</span>
                                </div>

                                <div class="text-muted">
                                    <i data-acorn-icon="note" data-acorn-size="16" class="me-1"></i>
                                    <span class="align-middle">{{$patient->comment}}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-row justify-content-between w-100 w-sm-50 w-xl-100">
                                <a href="{{route('admin.patient.edit',$patient->id)}}" class="btn btn-outline-primary w-100 me-2">
                                    <i data-acorn-icon="edit" data-acorn-size="16" class="me-1"></i>
                                    {{ __('edit') }}
                                </a>
                                <button type="button" class="btn btn-outline-danger w-100 me-2" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                                    <i data-acorn-icon="tooth" data-acorn-size="16" class="me-1"></i>
                                    {{ __('service') }}
                                </button>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="row g-0 align-items-center mb-2">
                                <button type="button" class="btn btn-outline-info w-100 me-2" data-bs-toggle="modal" data-bs-target="#showBalanceLogs">
                                    <i data-acorn-icon="dollar" data-acorn-size="16" class="me-1"></i>
                                    {{ __('balance') }} {{$patient->total_balance}} ₼
                                </button>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="row g-0 align-items-center mb-2">
                                <button type="button" class="btn btn-outline-dark w-100 me-2" data-bs-toggle="modal" data-bs-target="#partnerAction">
                                    <i data-acorn-icon="pharmacy" data-acorn-size="16" class="me-1"></i>
                                    {{ __('partner') }} {{$patient->partnerBalances()->sum('balance')}} ₼
                                </button>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="row g-0 align-items-center mb-2">
                                <button type="button" class="btn btn-outline-danger w-100 me-2" data-bs-toggle="modal" data-bs-target="#reservation">
                                    <i data-acorn-icon="clock" data-acorn-size="16" class="me-1"></i>
                                    {{ __('reservation') }}
                                </button>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="row g-0 align-items-center mb-2">
                                <button type="button" class="btn btn-outline-secondary w-100 me-2" data-bs-toggle="modal" data-bs-target="#patientFiles">
                                    <i data-acorn-icon="file-image" data-acorn-size="16" class="me-1"></i>
                                    {{ __('files') }} <span id="patientFilesCount">{{ $patient->files->count() }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="row g-0 align-items-center mb-2">
                                <button type="button" class="btn btn-outline-dark w-100 me-2" data-bs-toggle="modal" data-bs-target="#prescription">
                                    <i data-acorn-icon="note" data-acorn-size="16" class="me-1"></i>
                                    {{ __('prescription') }} {{$patient->prescriptions->count()}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Biography End -->
            </div>
            <!-- Left Side End -->

            <!-- Right Side Start -->
            <div class="col-12 col-xl-9 col-xxl-9">
                @foreach($patient->sessions as $session)
                    <div class="card mb-2">
                        <div class="card-body p-3">
                            <table class="table table-sm mb-0">
                                <tbody>
                                <tr class="table-header-1">
                                    <td colspan="2"><strong>{{ $session->doctor->fullname }}</strong></td>
                                    <td colspan="1"><strong>{{ __('date') }} : {{$session->date}}</strong></td>
                                    <td colspan="2"><strong>{{ __('total_price') }} : {{$session->total_cost}} AZN</strong></td>
                                    <td colspan="1"><strong>{{ __('general_note') }} : {{($session->note) ?? '---'}}</strong></td>
                                    <td>
                                        @if($session->status == 1)
                                            <a href="{{ route('admin.crm.session.edit', $session->id) }}"
                                               class="badge bg-warning text-decoration-none">
                                                {{ __('edit') }}
                                            </a>
                                            <a href="{{ route('admin.crm.finishSession', $session->id) }}"
                                               class="badge bg-dark text-decoration-none">
                                                {{ __('finish_service') }}
                                            </a>
                                            <a href="{{ route('admin.crm.session.delete', $session->id) }}"
                                               class="badge bg-danger text-decoration-none">
                                                {{ __('delete') }}
                                            </a>
                                        @else
                                            <span class="badge bg-success">{{ __('service_finished') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="table-header-2">
                                    <th>{{ __('no') }}</th>
                                    <th>{{ __('tooth') }}</th>
                                    <th>{{ __('service') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('discount') }}</th>
                                    <th>{{ __('total_price') }}</th>
                                    <th>{{ __('note') }}</th>
                                </tr>
                                @foreach($session->items as $item)
                                    <tr class="table-body">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->tooth_id }}</td>
                                        <td>{{ $item->service->name }}</td>
                                        <td>{{ $item->price }} AZN</td>
                                        <td>{{ $item->percent }} %</td>
                                        <td>{{ $item->price_net }} AZN</td>
                                        <td>{{ ($item->note) ?? '---' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Right Side End -->
        </div>

        <div class="modal fade modal-close-out" id="addServiceModal" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1" role="dialog" aria-hidden="true" data-worked-url="{{ route('admin.patient.workedTeeth', $patient->id) }}">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title">{{ __('new_service') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <div id="serviceTemplate" class="d-none">
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-price="{{$service->price}}">
                                            {{ $service->name }}
                                        </option>
                                    @endforeach
                                </div>
                                <div class="mb-3">
                                    <footer class="blockquote-footer">
                                        <cite>{{ __('ctrl_select_teeth') }}</cite>
                                    </footer>
                                    @include('admin.crm.partial._teeth_map')
                                </div>
                            </div>

                            <div class="col-8">
                                <form method="POST" id="addServiceForm" action="{{route('admin.crm.addservice',$patient->id)}}">
                                    @csrf
                                    <input type="hidden" id="patient_id" value="{{ $patient->id }}">
                                    <div class="service-area mb-3">
                                        <div class="row align-items-end g-2">
                                            <div class="col-md-3">
                                                <select class="form-select select2 price-selector" name="service_id[]" required>
                                                    <option value="1">{{ __('select_service') }}</option>
                                                    @foreach($services as $service)
                                                        <option value="{{$service->id}}" data-price="{{$service->price}}">
                                                            {{$service->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-2">
                                                <input type="number" name="price[]" class="form-control price" placeholder="{{ __('price') }}" required>
                                            </div>
                                            <div class="col-2">
                                                <input type="number" name="percent[]" min="0" max="100" step="0.01" class="form-control percent" placeholder="{{ __('discount') }}" value="0" required>
                                            </div>
                                            <div class="col-2">
                                                <input type="number" name="price_net[]" class="form-control price_net" step="0.01" placeholder="{{ __('total_price') }}" required>
                                            </div>
                                            <div class="col-2">
                                                <input type="text" name="note[]" class="form-control" placeholder="{{ __('note') }}">
                                            </div>
                                            <div class="col-auto">
                                                <button class="btn btn-primary btn-sm addService" type="button">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="top-label">
                                        <textarea class="form-control" name="comment"></textarea>
                                        <span>{{ __('general_note') }}</span>
                                    </label>

                                    <button class="btn btn-primary mt-3" type="submit">{{ __('add') }}</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade modal-close-out" id="showBalanceLogs" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title">{{__('balance_history')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @include('admin.crm.partial._balance_doctors')
                        @include('admin.crm.partial._balance_history')
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modal-close-out" id="prescription" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title">{{__('prescription')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @include('admin.crm.partial._prescription_form')
                        @include('admin.crm.partial._prescription_history')
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modal-close-out" id="profitModal" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title">{{__('profit_report')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>{{__('patient_total_income')}}: {{ $patientServiceTotal }} AZN</p>
                        <p>{{__('partner_total_payment')}} : {{ $partnerPurchaseTotal }} AZN</p>
                        <p>{{__('net_profit')}}: {{ $patientServiceTotal - $partnerPurchaseTotal }} AZN</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modal-close-out" id="reservation" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title">{{__('reservation')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @include('admin.crm.partial._reservation_form')
                        @include('admin.crm.partial._reservation_history')
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="toothServicesModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="toothServicesModalTitle">{{__('tooth_services')}}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body bg-light p-3" id="toothServicesModalBody">
                        Yüklənir...
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modal-close-out" id="patientFiles" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title">{{__('files')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @include('admin.crm.partial._file_upload')
                        @include('admin.crm.partial._files_table')
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modal-close-out" id="partnerAction" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title">{{__('partner_operation')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('admin.partners.purchase',$patient->id) }}">
                            @csrf
                            <div class="mb-2">
                                <label>{{__('select_partner')}}</label>
                                <select class="form-select select2" name="partner_id" required>
                                    <option value="">{{__('select_partner')}}</option>
                                    @foreach($partners as $partner)
                                        <option value="{{$partner->id}}">{{$partner->name}}
                                            | {{ $partner->type == 'supplier' ? 'Təchizatçı' : 'Texnik' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>{{__('amount')}}</label>
                                <input type="text" name="amount" class="form-control" placeholder="{{__('amount')}}">
                            </div>

                            <div class="mb-2">
                                <label>{{__('note')}}</label>
                                <textarea class="form-control" name="note" placeholder="{{__('note')}}"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">{{__('add')}}</button>
                        </form>
                        <hr>
                        @if($partnerPatientBalances->count())
                            <div class="table-responsive mb-2">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                    <tr>
                                        <th>{{__('partner')}}</th>
                                        <th>{{__('debt')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($partnerPatientBalances as $item)
                                        <tr>
                                            <td>{{ $item->partner->name ?? '---' }}</td>
                                            <td>{{ number_format($item->balance, 2) }} ₼</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('admin.crm.partial._pay_modal')
    </div>
@endsection

