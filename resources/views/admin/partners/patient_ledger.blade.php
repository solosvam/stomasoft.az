@php
    $html_tag_data = [];
    $title = __('patient_partner');

    $breadcrumbs = [
        "/admin" => "White Dent",
        "" => __('partner_doctor_patient_balance_details')
    ];
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
                            <p>
                                {{ __('partner') }} : {{ $rows[0]->partner->name }}
                                | {{ __('doctor') }} : {{ $rows[0]->doctor->fullname }}
                                | {{ __('patient') }} :
                                {{ $rows[0]->patient?->fullname ?? __('general_purchase') }}
                                {{ __('balance') }} {{ __('debt_details') }}
                            </p>
                            <!-- Hover Table Start -->
                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('type') }}</th>
                                    <th>{{ __('note') }}</th>
                                    <th>{{ __('delete') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rows as $row)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$row->created_at}}</td>
                                        <td>{{$row->amount}} ₼</td>
                                        <td class="{{ $row->type == 'payment' ? 'text-success' : 'text-danger' }}">
                                            <i
                                                data-acorn-icon="{{ $row->type == 'payment' ? 'arrow-bottom' : 'arrow-top' }}"
                                                data-acorn-size="16"
                                                class="me-1">
                                            </i> {{ $row->type == 'payment' ? __('payment') : __('purchase') }}
                                        </td>
                                        <td class="text-alternate">
                                            {{$row->note}}
                                        </td>
                                        <td>
                                            @if($row->type == 'purchase' && empty($paymentAfterIds[$row->id]))
                                                <a href="{{ route('admin.partners.deletePurchase',$row->id) }}"
                                                   class="btn btn-sm btn-danger">
                                                    {{ __('delete') }}
                                                </a>
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


    </div>
@endsection
