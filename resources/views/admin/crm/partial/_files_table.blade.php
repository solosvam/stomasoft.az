<hr>
<table class="table table-sm table-striped align-middle mb-0 mt-2">
    <thead>
    <tr>
        <th>#</th>
        <th>{{ __('file') }}</th>
        <th>{{ __('date') }}</th>
        <th class="text-end">{{ __('operation') }}</th>
    </tr>
    </thead>
    <tbody id="patientFilesTbody">
    @forelse($patient->files as $file)
        <tr id="file-row-{{ $file->id }}">
            <td>{{ $loop->iteration }}</td>
            <td>
                <a href="{{ asset('backend/uploads/patients/'.$patient->id.'/'.$file->file) }}"
                   target="_blank">
                    {{ $file->file }}
                </a>
            </td>
            <td>{{ \Carbon\Carbon::parse($file->created_at)->format('d.m.Y H:i') }}</td>
            <td class="text-end">
                <button type="button"
                        class="btn btn-sm btn-outline-danger remove-file-btn"
                        data-id="{{ $file->id }}">
                    {{ __('delete') }}
                </button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center text-muted">{{ __('no_file') }}</td>
        </tr>
    @endforelse
    </tbody>
</table>
