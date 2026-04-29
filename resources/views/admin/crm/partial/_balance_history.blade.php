<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>{{ __('no') }}</th>
            <th>{{ __('date') }}</th>
            <th>{{ __('doctor') }}</th>
            <th>{{ __('type') }}</th>
            <th>{{ __('amount') }}</th>
            <th>{{ __('description') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $i => $r)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d.m.Y H:i') }}</td>
                <td>{{ $r->doctor?->fullname ?? '-' }}</td>
                <td class="{{ $r->type === 'service' ? 'text-danger' : 'text-success' }}">
                    <i data-acorn-icon="{{ $r->type === 'payment' ? 'arrow-bottom' : 'arrow-top' }}"
                       data-acorn-size="16" class="me-1"></i>
                    {{ $r->type === 'service' ? __('service') : ($r->type === 'payment' ? __('payment') : $r->type) }}
                </td>
                <td>{{ number_format($r->signed_amount, 2) }} ₼</td>
                <td>{{ $r->note }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
