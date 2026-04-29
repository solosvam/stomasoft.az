@php
    $title = __('partner_balance');
    $html_tag_data = [];
    $breadcrumbs = ["/admin"=>"White Dent", "admin/partners/list"=>__('menu_partners'),"#"=>__('partner_doctor_balance_details')];
@endphp
@extends('admin.layout',['html_tag_data'=> $html_tag_data, 'title'=>$title])

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
                            @if(count($rows))
                                <p>
                                    {{ __('partner') }} :
                                    {{ $rows[0]->partner->name }}

                                    | {{ __('doctor') }} :
                                    {{$rows[0]->doctor->fullname}}
                                    {{ __('partner_doctor_balance_details') }}
                                </p>
                            @endif
                            <!-- Hover Table Start -->
                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-muted text-small text-uppercase">#</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('patient') }}</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('debt') }}</th>
                                    <th width="25%" class="text-muted text-small text-uppercase">{{ __('operation') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rows as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($row->patient_id && $row->patient)
                                                <a href="{{ route('admin.crm.info', $row->patient->id) }}">
                                                    {{ $row->patient->fullname }}
                                                </a>
                                            @else
                                                <span class="text-muted">{{ __('general_purchase') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-alternate">{{ number_format((float)$row->balance, 2) }} ₼</td>
                                        <td class="text-alternate">
                                            <button type="button"
                                                    class="btn btn-success btn-sm open-partner-pay"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#partnerPayModal"
                                                    data-patient-id="{{ $row->patient_id ?? 0 }}"
                                                    data-patient-name="{{ $row->patient?->fullname ?? __('general_purchase') }}"
                                                    data-max="{{ (float) $row->balance }}">
                                                {{ __('pay') }}
                                            </button>

                                            <a href="{{ route('admin.partners.doctor.patient', [$partner->id, $doctorId, $row->patient_id ?? 0]) }}"
                                               class="btn btn-primary btn-sm">
                                                {{ __('debt_details') }}
                                            </a>
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

        @include('admin.partners.partial._partner_pay_modal')
    </div>
@endsection
