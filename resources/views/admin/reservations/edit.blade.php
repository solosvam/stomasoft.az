@php
    $html_tag_data = [];
    $title = __('reservation_edit');

    $breadcrumbs = [
    "/admin"=>"White Dent",
    ""=>__('reservation_edit')
    ];
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
                    <h2 class="small-title">{{ __('reservation_edit') }}</h2>
                    <div class="card h-100-card">
                        <div class="card-body">
                            <form action="{{route('admin.reservations.update',$reservation->id)}}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('date') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <input type="date" class="form-control" name="date" id="reservation_date" value="{{$reservation->date}}" data-doctor="{{$reservation->doctor_id}}">
                                    </div>
                                </div>

                                <input type="hidden" name="hour" id="reservation_hour" value="{{ \Carbon\Carbon::parse($reservation->hour)->format('H:i') }}">

                                <div class="dates d-flex flex-wrap gap-2 mb-2">
                                    @foreach($slots as $slot)
                                        @if($slot['type'] == 'busy')
                                            <button type="button" class="btn btn-danger" onclick="window.open('{{ $slot['url'] }}','_blank')">
                                                {{ $slot['time'] }}
                                            </button>
                                        @elseif($slot['type'] == 'selected')
                                            <button type="button" class="btn btn-primary" id="{{ $slot['id'] }}" onclick="setTime('{{ $slot['id'] }}','{{ $slot['time'] }}')">
                                                {{ $slot['time'] }}
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-success" id="{{ $slot['id'] }}" onclick="setTime('{{ $slot['id'] }}','{{ $slot['time'] }}')">
                                                {{ $slot['time'] }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('service') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <select class="form-select" name="service_id" required>
                                            <option value="1">{{ __('select_service') }}</option>
                                            @foreach($services as $service)
                                                <option value="{{$service->id}}" @if($reservation->service_id == $service->id) selected @endif>{{$service->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('note') }}</label>
                                    <div class="col-sm-8 col-md-9 col-lg-10">
                                        <textarea class="form-control" name="note">{{ $reservation->note }}</textarea>
                                    </div>
                                </div>

                                @if(!$reservation->patient_id)
                                    <div class="mb-3 row position-relative">
                                        <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">{{ __('search_patient') }}</label>
                                        <div class="col-sm-8 col-md-9 col-lg-10 position-relative">
                                            <input type="text" class="form-control patient-search-reservation" placeholder="{{ __('search_patient') }}" autocomplete="off">
                                            <input type="hidden" name="patient_id" class="patient_id">
                                            <div class="search-results-reservation d-none"></div>
                                        </div>
                                    </div>
                                @endif

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
