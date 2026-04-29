<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('service_invoice') }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 2mm;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.35;
        }

        body {
            width: 76mm;
            margin: 0 auto;
            padding: 2mm;
        }

        .print-btn {
            display: block;
            width: 100%;
            margin: 0 0 8px 0;
            padding: 8px;
            border: 1px solid #000;
            background: #000;
            color: #fff;
            font-size: 12px;
            cursor: pointer;
        }

        .center {
            text-align: center;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .clinic {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .muted {
            font-size: 11px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .row {
            margin-bottom: 4px;
        }

        .label {
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        th, td {
            padding: 4px 2px;
            vertical-align: top;
            text-align: left;
            font-size: 11px;
        }

        thead th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            font-weight: 700;
        }

        tbody td {
            border-bottom: 1px dashed #bbb;
        }

        .text-right {
            text-align: right;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            padding: 3px 0;
            font-size: 12px;
        }

        .summary-row.total {
            font-size: 14px;
            font-weight: 700;
            border-top: 1px solid #000;
            margin-top: 4px;
            padding-top: 6px;
        }

        .small {
            font-size: 10px;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                width: 76mm;
                margin: 0 auto;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<button type="button" onclick="window.print()" class="print-btn">{{ __('print') }}</button>

@php
    $patientName = $patient->fullname ?? trim(($patient->name ?? '') . ' ' . ($patient->surname ?? ''));
    $grandTotal = 0;
    $totalDiscount = 0;
    $netTotal = 0;
    $row = 1;
@endphp

<div class="center">
    <div class="title">{{ __('service_invoice') }}</div>
    <div class="clinic">{{ $patient->doctor->clinic_name ?? '' }}</div>
    <div class="muted">{{ $patient->doctor->clinic_address ?? '' }}</div>
    <div class="muted">{{ $patient->doctor->mobile ?? '' }}</div>
</div>

<div class="line"></div>

<div class="row">
    <span class="label">{{ __('date') }}:</span>
    <span>{{ now()->format('d.m.Y H:i') }}</span>
</div>

<div class="row">
    <span class="label">{{ __('sick_patient') }}:</span>
    <span>{{ $patientName ?: '-' }}</span>
</div>

<div class="row">
    <span class="label">{{ __('doctor') }}:</span>
    <span>{{ $patient->doctor->fullname ?? '-' }}</span>
</div>

<div class="line"></div>

<table>
    <thead>
    <tr>
        <th style="width:8%;">#</th>
        <th style="width:12%;">{{ __('tooth') }}</th>
        <th style="width:34%;">{{ __('service') }}</th>
        <th class="text-right" style="width:15%;">{{ __('price') }}</th>
        <th class="text-right" style="width:13%;">{{ __('discount') }}.</th>
        <th class="text-right" style="width:18%;">{{ __('total') }}</th>
    </tr>
    </thead>
    <tbody>
    @forelse($patient->sessions as $session)
        @foreach($session->items as $item)
            @php
                $price = (float) ($item->price ?? 0);
                $percent = (float) ($item->percent ?? 0);
                $priceNet = (float) ($item->price_net ?? $price);
                $discountAmount = $price - $priceNet;

                $grandTotal += $price;
                $totalDiscount += $discountAmount;
                $netTotal += $priceNet;
            @endphp
            <tr>
                <td>{{ $row++ }}</td>
                <td>{{ $item->tooth_id }}</td>
                <td>{{ $item->service->name ?? '-' }}</td>
                <td class="text-right">{{ number_format($price, 2) }}</td>
                <td class="text-right">
                    @if($percent > 0)
                        -{{ number_format($discountAmount, 2) }}
                    @else
                        0.00
                    @endif
                </td>
                <td class="text-right">{{ number_format($priceNet, 2) }}</td>
            </tr>
        @endforeach
    @empty
        <tr>
            <td colspan="6" class="center">{{ __('no_service') }}</td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="line"></div>

<div class="summary-row">
    <span>{{ __('initial_total') }}</span>
    <strong>{{ number_format($grandTotal, 2) }} azn</strong>
</div>

<div class="summary-row">
    <span>{{ __('discount') }}</span>
    <strong>-{{ number_format($totalDiscount, 2) }} azn</strong>
</div>

<div class="summary-row">
    <span>{{ __('discounted_total') }}</span>
    <strong>{{ number_format($netTotal, 2) }} azn</strong>
</div>

<div class="summary-row total">
    <span>{{ __('payable') }}</span>
    <strong>
        @if((float)$totalDebt > 0)
            {{ number_format((float)$totalDebt, 2) }} azn
        @else
            0.00 azn
        @endif
    </strong>
</div>

<div class="line"></div>

<div class="center small">
    {{ __('thanks') }}
</div>

</body>
</html>
