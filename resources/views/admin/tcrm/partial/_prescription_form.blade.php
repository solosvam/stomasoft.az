<form method="POST" action="{{route('admin.crm.addprescription',$patient->id)}}">
    @csrf
    <div class="prescription-area mb-3">
        <div class="row align-items-end g-2">
            <div class="col-md-4">
                <input type="text" name="medicine_name[]" class="form-control" placeholder="{{ __('medicine') }}" required>
            </div>
            <div class="col-3">
                <input type="text" name="dose[]" class="form-control" placeholder="{{ __('dose') }}">
            </div>
            <div class="col-4">
                <input type="text" name="usage_text[]" class="form-control" placeholder="{{ __('usage_instruction') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn-sm addPrescription" type="button">+</button>
            </div>
        </div>
    </div>

    <button class="btn btn-primary mt-3" type="submit">{{ __('add') }}</button>
</form>
