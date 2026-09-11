@php
    $html_tag_data = [];
    $title = 'Həkim profili';

    $breadcrumbs = [
        "/admin" => "StomaSoft",
        route('admin.tcrm.index') => __('menu_doctors'),
        "#" => $doctor->fullname
    ];

    $locationMapData = $locations->mapWithKeys(fn($location) => [
        (string) $location->code => $location->id
    ]);
@endphp

@extends('admin.layout',['html_tag_data'=>$html_tag_data, 'title'=>$title])

@section('css')
    <style>
        #newJobModal .job-pending-tooth,
        #newJobModal .job-assigned-tooth {
            fill: #0d6efd !important;
        }

        #jobServicesModal .job-used-tooth {
            fill: #dc3545 !important;
        }

        #jobServicesModal .job-pending-tooth {
            fill: #0d6efd !important;
        }

        .tcrm-job-header {
            border-bottom: 1px solid var(--separator);
            padding-bottom: 12px;
            margin-bottom: 10px;
        }

        .tcrm-job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 24px;
            align-items: center;
        }

        .tcrm-job-meta-item {
            white-space: nowrap;
        }

        .tcrm-job-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: end;
            gap: 8px;
        }

        .tcrm-job-table th,
        .tcrm-job-table td {
            vertical-align: middle;
        }

        .tcrm-existing-group {
            background: var(--background);
        }
        #editJobModal .job-pending-tooth {
            fill: #0d6efd !important;
        }

        #editJobModal .job-assigned-tooth {
            fill: #0d6efd !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">

        <div id="tcrmDoctorConfig" data-location-map='@json($locationMapData)'></div>

        <div class="page-title-container">
            <div class="row">
                <div class="col-12 col-md-7">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                    @include('admin._layout.breadcrumb',['breadcrumbs'=>$breadcrumbs])
                </div>

                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newJobModal">
                        Yeni iş götür
                    </button>
                </div>
            </div>
        </div>

        <div class="row gx-4 gy-5">

            {{-- Sol tərəf --}}
            <div class="col-12 col-xl-3 col-xxl-3">
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex align-items-center flex-column mb-4">
                            <div class="sw-13 position-relative mb-3">
                                <img src="{{ asset('backend/img/profile/male.png') }}" class="img-fluid rounded-xl" alt="thumb"/>
                            </div>

                            <div class="h5 mb-1">{{ $doctor->fullname }}</div>

                            <div class="text-muted">
                                <i data-acorn-icon="mobile" data-acorn-size="16" class="me-1"></i>
                                <span class="align-middle">{{ $doctor->mobile }}</span>
                            </div>

                            @if($doctor->clinic_name)
                                <div class="text-muted">
                                    <i data-acorn-icon="building" data-acorn-size="16" class="me-1"></i>
                                    <span class="align-middle">{{ $doctor->clinic_name }}</span>
                                </div>
                            @endif

                            <div class="text-muted">
                                <i data-acorn-icon="pin" data-acorn-size="16" class="me-1"></i>
                                <span class="align-middle">{{ $doctor->address ?? '---' }}</span>
                            </div>
                        </div>

                        <div class="d-flex w-100 mb-4">
                            <a href="#" class="btn btn-outline-primary w-100 me-2">
                                <i data-acorn-icon="edit" data-acorn-size="16" class="me-1"></i>
                                {{ __('edit') }}
                            </a>

                            <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#newJobModal">
                                <i data-acorn-icon="plus" data-acorn-size="16" class="me-1"></i>
                                Yeni iş
                            </button>
                        </div>

                        <div class="mb-2">
                            <button type="button" class="btn btn-outline-info w-100" data-bs-toggle="modal" data-bs-target="#showBalanceLogs">
                                <i data-acorn-icon="dollar" data-acorn-size="16" class="me-1"></i>
                                Balans {{ number_format($balance, 2) }} ₼
                            </button>
                        </div>

                        <div class="mb-2">
                            <button type="button" class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#doctorPayModal">
                                <i data-acorn-icon="dollar" data-acorn-size="16" class="me-1"></i>
                                Ödəniş qəbul et
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Sağ tərəf --}}
            <div class="col-12 col-xl-9 col-xxl-9">

                @forelse($doctor->jobs as $job)
                    <div class="card mb-3">
                        <div class="card-body p-3">

                            <div class="tcrm-job-header">
                                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">

                                    <div class="tcrm-job-meta">
                                        <div class="tcrm-job-meta-item">
                                            <strong>#{{ $job->id }}</strong>
                                        </div>

                                        <div class="tcrm-job-meta-item">
                                            <span class="text-muted">Pasient:</span>
                                            <strong>{{ $job->patient_name ?? '---' }}</strong>
                                        </div>

                                        <div class="tcrm-job-meta-item">
                                            <span class="text-muted">Qəbul:</span>
                                            <strong>{{ $job->received_at?->format('d.m.Y H:i') }}</strong>
                                        </div>

                                        <div class="tcrm-job-meta-item">
                                            <span class="text-muted">Təhvil:</span>
                                            <strong>{{ $job->due_at?->format('d.m.Y H:i') ?? '---' }}</strong>
                                        </div>

                                        <div class="tcrm-job-meta-item">
                                            <span class="text-muted">Məbləğ:</span>
                                            <strong>{{ number_format($job->total_amount, 2) }} ₼</strong>
                                        </div>
                                    </div>

                                    <div>
                                        @if($job->status === 'active')
                                            <span class="badge bg-warning">Aktiv</span>
                                        @elseif($job->status === 'completed')
                                            <span class="badge bg-success">Tamamlandı</span>
                                        @elseif($job->status === 'delivered')
                                            <span class="badge bg-primary">Təhvil verildi</span>
                                        @elseif($job->status === 'cancelled')
                                            <span class="badge bg-danger">Ləğv edildi</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            @if($job->note)
                                <div class="alert alert-light py-2 px-3 mb-3">
                                    <strong>Ümumi qeyd:</strong> {{ $job->note }}
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-sm tcrm-job-table mb-0">
                                    <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">Dişlər</th>
                                        <th>Xidmət</th>
                                        <th width="25%">Qiymət</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($job->items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($item->locations->count())
                                                    {{ $item->locations->pluck('location.code')->filter()->implode(', ') }}
                                                @else
                                                    ---
                                                @endif
                                            </td>
                                            <td>{{ $item->service->name ?? '---' }}</td>
                                            <td>
                                                {{ number_format($item->price, 2) }} ₼
                                                × {{ $item->quantity }}
                                                =
                                                <strong>{{ number_format($item->total_price, 2) }} ₼</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($job->status === 'active')
                            <div class="tcrm-job-actions mt-3">

                                <button type="button" class="btn btn-outline-primary btn-sm openJobServices" data-job-id="{{ $job->id }}" data-services-url="{{ route('admin.tcrm.job.services', $job->id) }}" data-add-url="{{ route('admin.tcrm.job.services.add', $job->id) }}" data-bs-toggle="modal" data-bs-target="#jobServicesModal">Xidmətlər</button>

                                <form method="POST" action="{{ route('admin.tcrm.job.complete', $job->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-dark">Tamamla</button>
                                </form>

                                <button type="button" class="btn btn-sm btn-warning openJobEdit" data-edit-url="{{ route('admin.tcrm.job.edit', $job->id) }}" data-update-url="{{ route('admin.tcrm.job.update', $job->id) }}" data-bs-toggle="modal" data-bs-target="#editJobModal">{{ __('edit') }}</button>

                                <form method="POST" action="{{ route('admin.tcrm.job.delete', $job->id) }}" class="d-inline" onsubmit="return confirm('Bu işi silmək istədiyinizə əminsiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('delete') }}</button>
                                </form>
                            </div>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center text-muted">
                            Bu həkim üzrə hələ iş yoxdur
                        </div>
                    </div>
                @endforelse

            </div>
        </div>

        {{-- Balans tarixçəsi --}}
        <div class="modal fade" id="showBalanceLogs" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title">Balans tarixçəsi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <th>Tarix</th>
                                <th>Növ</th>
                                <th>Məbləğ</th>
                                <th>Qeyd</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($rows as $row)
                                <tr>
                                    <td>{{ $row->created_at?->format('d.m.Y H:i') }}</td>
                                    <td>{{ $row->type === 'job' ? 'İş' : 'Ödəniş' }}</td>

                                    <td class="{{ $row->type === 'payment' ? 'text-success' : 'text-danger' }}">
                                        {{ $row->type === 'payment' ? '-' : '+' }}
                                        {{ number_format($row->amount, 2) }} ₼
                                    </td>

                                    <td>{{ $row->note ?? '---' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Balans əməliyyatı yoxdur
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Yeni iş --}}
        <div class="modal fade modal-close-out" id="newJobModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title">Yeni iş</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form method="POST" id="newJobForm" action="{{ route('admin.tcrm.job.add', $doctor->id) }}">
                            @csrf

                            <div class="row">
                                <div class="col-4">
                                    <footer class="blockquote-footer">
                                        <cite>{{ __('ctrl_select_teeth') }}</cite>
                                    </footer>

                                    @include('admin.crm.partial.maps._tooth_map')

                                    <button type="button" class="btn btn-outline-primary w-100 mt-3" id="addSelectedTeethGroup">
                                        Seçilən dişlər üçün xidmət əlavə et
                                    </button>
                                </div>

                                <div class="col-8">
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <label>Pasient</label>
                                            <input type="text" name="patient_name" class="form-control" placeholder="Pasient">
                                        </div>

                                        <div class="col-md-6">
                                            <label>İşi kim gətirib</label>
                                            <select class="form-select" name="received_type" required>
                                                <option value="">{{ __('select') }}</option>
                                                <option value="doctor">Həkim gətirib</option>
                                                <option value="technician">Texnik götürüb</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Qəbul tarixi</label>
                                            <input type="datetime-local" name="received_at" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Təhvil tarixi</label>
                                            <input type="datetime-local" name="due_at" class="form-control" required>
                                        </div>
                                    </div>

                                    <hr>

                                    <div id="jobGroups"></div>

                                    <label class="top-label mt-3">
                                        <textarea class="form-control" name="note"></textarea>
                                        <span>{{ __('general_note') }}</span>
                                    </label>

                                    <div class="text-end mt-3">
                                        <strong>Ümumi məbləğ: <span id="jobTotal">0.00</span> ₼</strong>
                                    </div>

                                    <button class="btn btn-primary mt-3" type="submit">
                                        İş yarat
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mövcud işə xidmət əlavə et --}}
        <div class="modal fade modal-close-out" id="jobServicesModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title">İş xidmətləri</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form method="POST" id="jobServicesForm">
                            @csrf

                            <div class="row">
                                <div class="col-4">
                                    <footer class="blockquote-footer">
                                        <cite>{{ __('ctrl_select_teeth') }}</cite>
                                    </footer>

                                    @include('admin.crm.partial.maps._tooth_map')

                                    <button type="button" class="btn btn-outline-primary w-100 mt-3" id="addJobServiceGroup">
                                        Seçilən dişlər üçün xidmət əlavə et
                                    </button>
                                </div>

                                <div class="col-8">
                                    <div class="mb-3">
                                        <h6>Mövcud xidmətlər</h6>
                                        <div id="existingJobGroups"></div>
                                    </div>

                                    <div id="newJobGroups"></div>

                                    <div class="text-end mt-3">
                                        <strong>Yeni əlavə məbləği: <span id="newJobServiceTotal">0.00</span> ₼</strong>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3">
                                        Əlavə et
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- İşin redaktəsi --}}
        <div class="modal fade modal-close-out" id="editJobModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <div class="modal-header p-3">
                        <h5 class="modal-title">İşi düzəliş et</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form method="POST" id="editJobForm">
                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <footer class="blockquote-footer">
                                        <cite>{{ __('ctrl_select_teeth') }}</cite>
                                    </footer>

                                    @include('admin.crm.partial.maps._tooth_map')

                                    <button type="button" class="btn btn-outline-primary w-100 mt-3" id="addEditJobGroup">Seçilən dişlər üçün xidmət əlavə et</button>
                                </div>

                                <div class="col-8">

                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <label>Pasient</label>
                                            <input type="text" name="patient_name" id="editPatientName" class="form-control" placeholder="Pasient">
                                        </div>

                                        <div class="col-md-6">
                                            <label>İşi kim gətirib</label>
                                            <select class="form-select" name="received_type" id="editReceivedType" required>
                                                <option value="">{{ __('select') }}</option>
                                                <option value="doctor">Həkim gətirib</option>
                                                <option value="technician">Texnik götürüb</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Qəbul tarixi</label>
                                            <input type="datetime-local" name="received_at" id="editReceivedAt" class="form-control" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Təhvil tarixi</label>
                                            <input type="datetime-local" name="due_at" id="editDueAt" class="form-control" required>
                                        </div>
                                    </div>

                                    <hr>

                                    <div id="editJobGroups"></div>

                                    <label class="top-label mt-3">
                                        <textarea class="form-control" name="note" id="editJobNote"></textarea>
                                        <span>{{ __('general_note') }}</span>
                                    </label>

                                    <div class="text-end mt-3">
                                        <strong>Ümumi məbləğ: <span id="editJobTotal">0.00</span> ₼</strong>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3">Yadda saxla</button>

                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        {{-- Xidmət option template --}}
        <div id="jobServiceTemplate" class="d-none">
            <option value="">{{ __('select_service') }}</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                    {{ $service->name }}
                </option>
            @endforeach
        </div>
    </div>
@endsection

@section('js_page')
    <script src="{{ asset('backend/js/pages/tcrm.js?v=1.0.0') }}"></script>
@endsection
