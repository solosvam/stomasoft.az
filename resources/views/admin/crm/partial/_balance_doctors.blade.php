<div class="mb-3">
    <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead>
            <tr>
                <th>{{ __('doctor') }}</th>
                <th class="text-end">{{ __('debt') }}</th>
                <th class="text-end" style="width: 140px"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($doctorTotals as $dt)
                <tr>
                    <td class="fw-bold">{{ $dt->doctor?->fullname ?? '-' }}</td>
                    <td class="text-end text-danger fw-bold">{{ number_format($dt->balance,2) }} ₼</td>
                    <td class="text-end">
                        @can('cashier')
                        <button
                            type="button"
                            class="btn btn-outline-success btn-sm open-pay-modal"
                            data-mode="doctor"
                            data-doctor-id="{{ $dt->doctor_id }}"
                            data-doctor-name="{{ $dt->doctor?->fullname ?? '-' }}"
                            data-max="{{ (float)$dt->balance }}"
                            data-bs-toggle="modal"
                            data-bs-target="#payModal">
                            {{ __('payment') }}
                        </button>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>
</div>
