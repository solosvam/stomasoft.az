<div class="row">
    <div class="col-md-4">
        <form method="POST" action="{{ route('admin.subscriptionPayment', $user->id) }}">
            @csrf

            <div class="mb-3">
                <label>Abunəlik bitmə tarixi</label>
                <input type="date" name="subscription_ends_at" class="form-control" value="{{ $user->subscription_ends_at }}">
            </div>

            <div class="mb-3">
                <label>Məbləğ</label>
                <input type="number" step="0.01" name="amount" class="form-control" value="100">
            </div>

            <div class="mb-3">
                <label class="form-label">{{__('payment_type')}}</label>
                <select class="form-select" name="method" required>
                    <option value="cash">{{__('cash')}}</option>
                    <option value="c2c">{{__('c2c')}}</option>
                </select>
            </div>

            <button class="btn btn-success">Ödəniş əlavə et</button>
        </form>
    </div>
    <div class="col-md-8">
        <table class="table">
            <thead>
            <tr>
                <th>Tarix</th>
                <th>Məbləğ</th>
                <th>Dövr</th>
            </tr>
            </thead>

            <tbody>
            @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->created_at }}</td>
                    <td>{{ $payment->amount }} ₼</td>
                    <td>
                        {{ $payment->period_from }}
                        —
                        {{ $payment->period_to }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>



