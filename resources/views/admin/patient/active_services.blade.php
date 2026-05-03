@php
    $html_tag_data = [];
    $title = __('active_services_patients');

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
                            <!-- Hover Table Start -->
                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('fullname') }}</th>
                                    <th>{{ __('gender') }}</th>
                                    <th>{{ __('age') }}</th>
                                    <th>{{ __('mobile') }}</th>
                                    <th>{{ __('debt') }}</th>
                                    <th>{{ __('operation') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($patients as $patient)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$patient->fullname}}</td>
                                        <td class="text-alternate">{{ $patient->sex ? __('male') : __('female') }}</td>
                                        <td class="text-alternate">{{ \Carbon\Carbon::parse($patient->bday)->age }}</td>
                                        <td class="text-alternate">{{$patient->mobile}}</td>
                                        <td class="text-alternate">{{$patient->balance}} AZN</td>
                                        <td class="text-alternate">
                                            <a href="{{route('admin.crm.info',$patient->id)}}" class="btn btn-primary btn-sm">{{ __('menu_crm') }}</a>
                                            <a href="{{route('admin.patient.edit',$patient->id)}}" class="btn btn-primary btn-sm">{{ __('edit') }}</a>
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
