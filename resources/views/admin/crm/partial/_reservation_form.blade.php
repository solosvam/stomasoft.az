<form method="POST" action="{{route('admin.reservations.add',$patient->id)}}">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <label>{{ __('service') }}</label>
            <select class="form-select select2" name="service_id" required>
                <option value="1">{{ __('select_service') }}</option>
                @foreach($services as $service)
                    <option value="{{$service->id}}">{{$service->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label>{{ __('date') }}</label>
            <input type="date" class="form-control" name="date" id="reservation_date" data-doctor="{{\Illuminate\Support\Facades\Auth::id()}}">
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="dates"></div>
        </div>
    </div>
    <input type="hidden" name="hour" id="reservation_hour">
    <button class="btn btn-primary mt-3">{{ __('add') }}</button>
</form>
<hr>
