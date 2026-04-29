@if($sessions->count())
    @foreach($sessions as $session)
        @foreach($session->items as $item)
            <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 mb-2 bg-white">
                <div>
                    <div class="fw-bold">{{ $item->service->name }}</div>
                    <small class="text-muted">{{ $session->date }}</small>
                </div>

                <div class="fw-bold text-primary">
                    {{ $item->price_net }} AZN
                </div>
            </div>
        @endforeach
    @endforeach
@else
    <div class="alert alert-warning mb-0">
        {{ __('no_services_on_tooth') }}
    </div>
@endif
