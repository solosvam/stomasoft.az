<table class="table table-striped table-sm">
    <thead>
    <tr>
        <th>{{ __('no') }}</th>
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
        <td>{{ $loop->iteration }}</td>
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
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
