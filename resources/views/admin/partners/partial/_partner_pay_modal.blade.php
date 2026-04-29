<div class="modal fade" id="partnerPayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('admin.partners.pay_patient', [$partner->id, $doctorId, 0]) }}"
              class="modal-content"
              id="partnerPayForm">
            @csrf

            <div class="modal-header p-3">
                <h5 class="modal-title" id="partnerPayTitle">
                    {{ __('partner_payment') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('amount') }}</label>
                    <input type="number" step="0.01" min="0.01" class="form-control" name="amount" id="partnerPayAmount" required>
                    <div class="form-text" id="partnerPayHint"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('note') }}</label>
                    <input type="text" class="form-control" name="note">
                </div>
            </div>

            <div class="modal-footer p-3">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">{{ __('close') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('pay') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('click', function(e){
        const btn = e.target.closest('.open-partner-pay');
        if(!btn) return;

        const patientId = btn.dataset.patientId;
        const patientName = btn.dataset.patientName || '';
        const max = parseFloat(btn.dataset.max || '0');

        const form = document.getElementById('partnerPayForm');
        form.action = form.action.replace(/\/patient\/\d+\/pay$|\/patient\/0\/pay$/, '/patient/' + patientId + '/pay');

        document.getElementById('partnerPayTitle').textContent =
            patientName
                ? patientName + ' {{ __("for_partner_payment") }}'
                : '{{ __("partner_payment") }}';

        const amountInput = document.getElementById('partnerPayAmount');
        amountInput.value = '';
        amountInput.max = max > 0 ? max : '';

        document.getElementById('partnerPayHint').textContent =
            max > 0
                ? '{{ __("maximum") }}: ' + max.toFixed(2) + ' ₼'
                : '';
    });
</script>
