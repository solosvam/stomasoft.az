<div class="modal fade" id="payModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.crm.pay', $patient->id) }}" class="modal-content">
            @csrf

            <input type="hidden" name="mode" id="pay_mode" value="doctor">
            <input type="hidden" name="doctor_id" id="pay_doctor_id" value="">

            <div class="modal-header p-3">
                <h5 class="modal-title" id="pay_title">{{ __('payment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">{{__('amount')}}</label>
                    <input type="number" step="0.01" min="0.01"
                           class="form-control"
                           name="amount"
                           id="pay_amount"
                           required>
                    <div class="form-text" id="pay_hint"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{__('payment_type')}}</label>
                    <select class="form-select" name="method" required>
                        <option value="cash">{{__('cash')}}</option>
                        <option value="pos">{{__('pos')}}</option>
                        <option value="c2c">{{__('c2c')}}</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{__('note')}}</label>
                    <input type="text" class="form-control" name="note">
                </div>

            </div>

            <div class="modal-footer p-3">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                    {{ __('close') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('confirm') }}
                </button>
            </div>
        </form>
    </div>
</div>
