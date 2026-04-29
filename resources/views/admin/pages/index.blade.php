@php
    $html_tag_data = [];
    $title = __('dashboard');
    $breadcrumbs = ["/admin"=>"White Dent", "/"=> $title]
@endphp
@extends('admin.layout',['html_tag_data'=>$html_tag_data, 'title'=>$title])
@section('css')
    <link rel="stylesheet" href="{{asset('backend/css/vendor/select2.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('backend/css/vendor/select2-bootstrap4.min.css')}}"/>
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
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Add New Button Start -->
                    <button type="button" class="btn btn-outline-danger btn-icon btn-icon-end w-100 w-sm-auto me-2" data-bs-toggle="modal" data-bs-target="#newReservationManual">
                        {{ __('add_reservation') }}
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-end w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#newAdmin">
                        <span>{{ __('new_patient') }}</span>
                        <i data-acorn-icon="plus"></i>
                    </button>
                    <!-- Add New Button End -->
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-xl-6 mb-5">
                <h2 class="small-title">{{ __('today_reservations') }}</h2>
                <div class="card h-100-card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($todaySlots as $slot)
                                @if($slot['type'] == 'busy')
                                    <button type="button" class="btn btn-danger"
                                            @if($slot['url']) onclick="window.open('{{ $slot['url'] }}','_blank')" @endif>
                                        {{ $slot['time'] }}
                                    </button>
                                @elseif($slot['type'] == 'past')
                                    <button type="button" class="btn btn-muted">
                                        {{ $slot['time'] }}
                                    </button>
                                @else
                                    <button type="button"
                                            class="btn btn-success open-reservation-modal"
                                            data-date="{{ now()->format('Y-m-d') }}"
                                            data-time="{{ $slot['time'] }}">
                                        {{ $slot['time'] }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-6 mb-5">
                <h2 class="small-title">{{ __('tomorrow_reservations') }}</h2>
                <div class="card h-100-card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tomorrowSlots as $slot)
                                @if($slot['type'] == 'busy')
                                    <button type="button" class="btn btn-danger"
                                            @if($slot['url']) onclick="window.open('{{ $slot['url'] }}','_blank')" @endif>
                                        {{ $slot['time'] }}
                                    </button>
                                @else
                                    <button type="button"
                                            class="btn btn-success open-reservation-modal"
                                            data-date="{{ now()->addDay()->format('Y-m-d') }}"
                                            data-time="{{ $slot['time'] }}">
                                        {{ $slot['time'] }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-12 mb-5">
                <h2 class="small-title">{{ __('reservation_list') }}</h2>
                <div class="card h-100-card">
                    <div class="position-relative">
                        <button type="button" class="btn btn-outline-danger hover-outline btn-icon btn-sm position-absolute e-2 t-2 z-index-1" data-bs-toggle="modal" data-bs-target="#newReservationManual">
                            <i data-acorn-icon="plus"></i> {{ __('add_reservation') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table align-middle mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('patient') }}</th>
                                <th>{{ __('date') }}</th>
                                <th>{{ __('hour') }}</th>
                                <th>{{ __('service') }}</th>
                                <th>{{ __('note') }}</th>
                                <th>{{ __('status') }}</th>
                                <th class="text-end">{{ __('operation') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reservations as $reservation)
                                @php
                                    $dt = \Carbon\Carbon::parse($reservation->date . ' ' . $reservation->hour);
                                    $isPast = $dt->lt(now());
                                @endphp
                                <tr>
                                    <td class="{{ $isPast ? 'bg-danger text-white' : '' }}">{{ $loop->iteration }}</td>

                                    <td>
                                        @if($reservation->patient)
                                            <a href="{{ route('admin.crm.info', $reservation->patient->id) }}" class="text-decoration-none fw-medium">
                                                {{ $reservation->patient->fullname }}
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($reservation->date)->format('d.m.Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->hour)->format('H:i') }}</td>
                                    <td>{{ $reservation->service?->name ?? '-' }}</td>
                                    <td>{{ $reservation->note ?: '—' }}</td>

                                    <td>
                                        @if($reservation->status == 'pending')
                                            <span class="badge bg-warning text-dark">{{ __('waiting') }}</span>
                                        @elseif($reservation->status == 'done')
                                            <span class="badge bg-success">{{ __('completed') }}</span>
                                        @elseif($reservation->status == 'cancelled')
                                            <span class="badge bg-danger">{{ __('cancelled') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('unknown') }}</span>
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        @if($reservation->status == 'pending')
                                            <form action="{{ route('admin.reservations.updateStatus', $reservation->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="done">
                                                <button type="submit" class="btn btn-sm btn-success">{{ __('complete') }}</button>
                                            </form>

                                            <form action="{{ route('admin.reservations.updateStatus', $reservation->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-sm btn-warning">{{ __('cancel') }}</button>
                                            </form>

                                            <a href="{{ route('admin.reservations.edit', $reservation->id) }}" class="btn btn-sm btn-primary">{{ __('edit') }}</a>
                                        @endif

                                        <a href="{{ route('admin.reservations.delete', $reservation->id) }}" class="btn btn-sm btn-danger">{{ __('delete') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6 mb-5">
                <h2 class="small-title">{{ __('today_served_patients') }}</h2>
                <div class="card h-100-card">
                    <div class="card-body">
                        <div class="scroll-out">
                            <div class="scroll-by-count mb-n2" data-childSelector=".scroll-item" data-count="3">
                                @foreach($sessions as $session)
                                    <div class="mb-2 scroll-item">
                                        <div class="row g-0 sh-10 sh-sm-7">
                                            <div class="col-auto">
                                                <img src="{{ asset('backend/img/profile/' . ($session->patient->sex == 0 ? 'female.png' : 'male.png')) }}" class="card-img rounded-xl sh-6 sw-6" alt="thumb" />
                                            </div>
                                            <div class="col">
                                                <div class="card-body d-flex flex-column flex-sm-row pt-0 pb-0 ps-3 pe-0 h-100 align-items-sm-center justify-content-sm-between">
                                                    <div class="d-flex flex-column mb-2 mb-sm-0">
                                                        <div>{{$session->patient->fullname}}</div>
                                                        <div class="text-small text-muted">{{ \Carbon\Carbon::parse($session->patient->bday)->age }} {{ __('age') }}</div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <a class="btn btn-outline-secondary btn-sm" href="{{route('admin.crm.info',$session->patient->id)}}">CRM</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6 mb-5">
                <h2 class="small-title">{{ __('last_patients') }}</h2>
                <div class="card h-100-card">
                    <div class="position-relative">
                        <button type="button" class="btn btn-outline-danger hover-outline btn-icon btn-sm position-absolute e-2 t-2 z-index-1" data-bs-toggle="modal" data-bs-target="#newAdmin">
                            <i data-acorn-icon="plus"></i> {{ __('new_patient') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="scroll-out">
                            <div class="scroll-by-count mb-n2" data-childSelector=".scroll-item" data-count="3">
                                @foreach($patients as $key => $patient)
                                    <div class="mb-2 scroll-item">
                                        <div class="row g-0 sh-10 sh-sm-7">
                                            <div class="col-auto">
                                                <img src="{{ asset('backend/img/profile/' . ($patient->sex == 0 ? 'female.png' : 'male.png')) }}" class="card-img rounded-xl sh-6 sw-6" alt="thumb" />
                                            </div>
                                            <div class="col">
                                                <div class="card-body d-flex flex-column flex-sm-row pt-0 pb-0 ps-3 pe-0 h-100 align-items-sm-center justify-content-sm-between">
                                                    <div class="d-flex flex-column mb-2 mb-sm-0">
                                                        <div>{{$patient->fullname}}</div>
                                                        <div class="text-small text-muted">{{ \Carbon\Carbon::parse($patient->bday)->age }} {{ __('age') }}</div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <a class="btn btn-outline-secondary btn-sm" href="{{route('admin.crm.info',$patient->id)}}">CRM</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                               placeholder="{{ __('mobile') }} 0103227575"
                               value="{{ old('mobile') }}"
                               required>
                        <label>{{ __('birth_date') }}</label>
                        <input type="text" class="form-control" name="bday" id="datePickerBasic" value="{{old('bday')}}">
                        <label>{{ __('note') }}</label>
                        <textarea name="comment" class="form-control" placeholder="{{ __('note') }}">{{old('comment')}}</textarea>
                        <label>{{ __('address') }}</label>
                        <input type="text" name="address" class="form-control" value="{{old('address')}}" placeholder="{{ __('address') }}">
                        <label>FIN</label>
                        <input type="text" name="fin" class="form-control" value="{{old('fin')}}" placeholder="FİN">
                        <hr>
                        <button type="submit" class="btn btn-primary">{{ __('add') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-right fade" id="newReservationManual" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('add_reservation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{route('admin.reservations.create')}}">
                        @csrf
                        <div class="position-relative">
                            <label>{{ __('search_patient') }}</label>
                            <input type="text" class="form-control patient-search-reservation" placeholder="{{ __('search_patient') }}" autocomplete="off">
                            <input type="hidden" name="patient_id" class="patient_id">
                            <div class="search-results-reservation d-none"></div>
                        </div>
                        <label>{{ __('service') }}</label>
                        <select class="form-select select2" name="service_id" required>
                            <option value="">{{ __('select') }}</option>
                            @foreach($services as $service)
                                <option value="{{$service->id}}">{{$service->name}}</option>
                            @endforeach
                        </select>
                        <label>{{ __('date') }}</label>
                        <input type="date" class="form-control" name="date" id="reservation_date" data-doctor="{{\Illuminate\Support\Facades\Auth::id()}}">
                        <input type="hidden" name="hour" id="reservation_hour">
                        <div class="dates"></div>
                        <label>{{ __('note') }}</label>
                        <textarea class="form-control" name="note" rows="5"></textarea>
                        <button type="submit" class="btn btn-primary mt-3">{{ __('add') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-right fade" id="newReservationSlot" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('add_reservation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.reservations.create') }}">
                        @csrf
                        <div class="position-relative">
                            <label>{{ __('search_patient') }}</label>
                            <input type="text" class="form-control patient-search-reservation" placeholder="{{ __('search_patient') }}" autocomplete="off">
                            <input type="hidden" name="patient_id" class="patient_id">
                            <div class="search-results-reservation d-none"></div>
                        </div>
                        <label>{{ __('service') }}</label>
                        <select class="form-select select2" name="service_id" required>
                            <option value="">{{ __('select') }}</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>

                        <input type="hidden" name="date" id="reservation_date_slot">
                        <input type="hidden" name="hour" id="reservation_hour_slot">

                        <div class="alert alert-light mt-3 mb-3">
                            <b>{{ __('date') }}:</b> <span id="selected_reservation_date"></span><br>
                            <b>{{ __('hour') }}:</b> <span id="selected_reservation_hour"></span>
                        </div>

                        <label>{{ __('note') }}</label>
                        <textarea class="form-control" name="note" rows="5"></textarea>

                        <button type="submit" class="btn btn-primary mt-3">{{ __('add') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
