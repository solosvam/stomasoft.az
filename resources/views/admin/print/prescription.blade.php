<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('clinic_prescription') }}</title>

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
    $patientName = $prescription->patient->fullname ?? trim(($prescription->patient->name ?? '') . ' ' . ($prescription->patient->surname ?? ''));
@endphp

<div class="center">
    <div class="title">{{ __('clinic_prescription') }}</div>
    <div class="clinic">{{ $prescription->doctor->clinic_name ?? '' }}</div>
    <div class="muted">{{ $prescription->doctor->clinic_address ?? '' }}</div>
    <div class="muted">{{ $prescription->doctor->mobile ?? '' }}</div>
</div>

<div class="line"></div>

<div class="row">
    <span class="label">{{ __('date') }}:</span>
    <span>{{ \Carbon\Carbon::parse($prescription->created_at)->format('d.m.Y H:i') }}</span>
</div>

<div class="row">
    <span class="label">{{ __('sick_patient') }}:</span>
    <span>{{ $patientName ?: '-' }}</span>
</div>

<div class="row">
    <span class="label">{{ __('doctor') }}:</span>
    <span>{{ $prescription->doctor->fullname ?? $prescription->doctor->name ?? '-' }}</span>
</div>

<div class="line"></div>

<table>
    <thead>
    <tr>
        <th style="width:8%;">#</th>
        <th style="width:34%;">{{ __('medicine') }}</th>
        <th style="width:20%;">{{ __('dose') }}</th>
        <th style="width:38%;">{{ __('usage_instruction') }}</th>
    </tr>
    </thead>
    <tbody>
    @forelse($prescription->items as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->medicine_name ?? '-' }}</td>
            <td>{{ $item->dose ?: '-' }}</td>
            <td>{{ $item->usage_text ?: '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="center">{{ __('no_prescription') }}</td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="line"></div>

<div class="row">
    <span class="label">{{ __('signature') }}:</span>
    <span>________________</span>
</div>

<div class="line"></div>

<div class="center small">
    {{ __('use_medicines_as_directed') }}
</div>

</body>
</html>
