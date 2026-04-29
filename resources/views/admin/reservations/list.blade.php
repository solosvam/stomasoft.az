@php
    $html_tag_data = [];
    $title = __('reservation_list');

    $breadcrumbs = [
    "/admin"=>"White Dent",
    ""=>__('reservation_list')
    ];
@endphp
@extends('admin.layout',['html_tag_data'=>$html_tag_data, 'title'=>$title])

@section('js_page')
    <script src="{{asset('backend/js/plugins/lists.js')}}"></script>

    <script src="{{asset('backend/js/vendor/list.js')}}"></script>
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
            </div>
        </div>
        <div class="row">
            <div class="col">
                <section class="scroll-section" id="hover">
                    <div class="card mb-5">
                        <div class="card-body">
                            <!-- Hover Table Start -->
                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>{{ __('patient') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('hour') }}</th>
                                    <th>{{ __('service') }}</th>
                                    <th>{{ __('note') }}</th>
                                    <th>{{ __('status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reservations as $reservation)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <a href="{{ $reservation->patient ? route('admin.crm.info', $reservation->patient->id) : '#' }}">
                                                {{ $reservation->patient->fullname ?? '-' }}
                                            </a>
                                        </td>
                                        <td>{{$reservation->date}}</td>
                                        <td>{{$reservation->hour}}</td>
                                        <td>{{$reservation->service->name}}</td>
                                        <td>{{$reservation->note}}</td>
                                        <td>
                                            @if($reservation->status == 'pending')
                                                <form action="{{ route('admin.reservations.updateStatus', $reservation->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="done">
                                                    <button class="btn btn-sm btn-success">{{ __('complete') }}</button>
                                                </form>

                                                <form action="{{ route('admin.reservations.updateStatus', $reservation->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button class="btn btn-sm btn-danger">{{ __('cancel') }}</button>
                                                </form>
                                                <a href="{{ route('admin.reservations.edit', $reservation->id) }}" class="btn btn-sm btn-primary">{{ __('edit') }}</a>
                                            @elseif($reservation->status == 'done')
                                                <span class="badge bg-success">{{ __('completed') }}</span>
                                            @elseif($reservation->status == 'cancelled')
                                                <span class="badge bg-danger">{{ __('cancelled') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('unknown') }}</span>
                                            @endif
                                                <a href="{{ route('admin.reservations.delete', $reservation->id) }}" class="btn btn-sm btn-danger">{{ __('delete') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$reservations->links('admin.pagination')}}
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
