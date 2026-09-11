@if($patient->prescriptions->count())
    <hr class="my-3">

    <h5 class="mb-2">{{ __('written_prescriptions') }}</h5>

    @foreach($patient->prescriptions as $prescription)
        <div class="border rounded p-2 mb-2">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <small>{{ \Carbon\Carbon::parse($prescription->created_at)->format('d.m.Y H:i') }}</small>
                </div>

                <div>
                    <span class="badge badge-primary">{{ $prescription->items->count() }} {{ __('medicine') }}</span>
                    <a href="{{ route('admin.print.prescription', $prescription->id) }}" target="_blank" class="btn btn-sm btn-primary ml-1">
                        {{ __('print') }}
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('medicine') }}</th>
                        <th>{{ __('dose') }}</th>
                        <th>{{ __('usage_instruction') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($prescription->items as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->medicine_name }}</td>
                            <td>{{ $item->dose ?: '---' }}</td>
                            <td>{{ $item->usage_text ?: '---' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endif
