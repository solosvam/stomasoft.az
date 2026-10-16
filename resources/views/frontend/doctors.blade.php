<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('frontend.partials.head', [
        'title' => __('site.seo_doctors_title'),
        'desc'  => __('site.seo_doctors_desc'),
    ])
</head>
<body>

@include('frontend.partials.nav', ['active' => 'doctors'])

@php
    $isAz = app()->getLocale() == 'az';
    $features = [
        ['users',    'landing.patient_db',          'landing.patient_desc'],
        ['calendar', 'landing.reservation_system',  'landing.reservation_desc'],
        ['wallet',   'landing.cash_payment',        'landing.cash_desc'],
        ['chart',    'landing.finance',             'landing.finance_desc'],
        ['link',     'landing.partner',             'landing.partner_desc'],
        ['file',     'landing.files',               'landing.files_desc'],
        ['rx',       'site.f_prescription',         'site.f_prescription_d'],
        ['box',      'site.f_stock',                'site.f_stock_d'],
        ['shield',   'site.f_roles',                'site.f_roles_d'],
    ];
    $planPoints = ['landing.fast', 'landing.doctor_split', 'landing.languages_short', 'landing.receipt'];
@endphp

<section class="page-head">
    <div class="wrap">
        <div class="pill"><i></i>{{ __('welcome.doctors_title') }}</div>
        <h1>{{ __('landing.what_does') }}</h1>
        <p>{{ __('landing.simplifies') }}</p>
        <div class="cta" style="justify-content:center;margin:0">
            <a href="https://demo.{{ config('app.domain') }}" class="btn btn-primary">
                {{ __('landing.demo_btn') }} @include('frontend.partials.icon', ['n' => 'arrow'])
            </a>
            <a href="https://app.{{ config('app.domain') }}" class="btn btn-ghost">{{ __('site.doctor_login') }}</a>
        </div>
    </div>
</section>

{{-- Features --}}
<section class="section" id="features">
    <div class="wrap">
        <div class="head-c">
            <div class="kicker">{{ __('landing.panel_features') }}</div>
            <h2 class="h2">{{ __('site.features_title') }}</h2>
        </div>
        <div class="grid3">
            @foreach($features as [$icon, $title, $text])
                <div class="card">
                    <div class="ico">@include('frontend.partials.icon', ['n' => $icon])</div>
                    <h3>{{ __($title) }}</h3>
                    <p>{{ __($text) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Pricing --}}
<section class="section alt" id="tarif">
    <div class="wrap">
        <div class="head-c">
            <div class="kicker">{{ __('landing.subscription') }}</div>
            <h2 class="h2">{{ __('landing.saas_title') }}</h2>
            <p class="sub">{{ __('landing.saas_desc') }}</p>
        </div>

        <div class="pricing">
            <div class="price">
                <h3>{{ __('landing.package') }}</h3>
                <p class="d">{{ __('landing.package_desc') }}</p>
                <div class="amt"><b>{{ $isAz ? '99 AZN' : '59 USD' }}</b><span>/ {{ __('landing.month') }}</span></div>
                <p class="tot">{{ __('landing.package_full') }}</p>
                <ul>
                    @foreach($planPoints as $p)
                        <li>@include('frontend.partials.icon', ['n' => 'check']){{ __($p) }}</li>
                    @endforeach
                </ul>
                <a href="https://demo.{{ config('app.domain') }}" class="btn btn-ghost">{{ __('landing.demo_btn') }}</a>
            </div>

            <div class="price hot">
                <span class="badge">{{ __('landing.best_value') }}</span>
                <h3>{{ __('landing.package_3m') }}</h3>
                <p class="d">{{ __('landing.package_3m_desc') }}</p>
                <div class="amt"><b>{{ $isAz ? '50 AZN' : '29 USD' }}</b><span>/ {{ __('landing.month') }}</span></div>
                <p class="tot">{{ __('site.price_3m_total', ['sum' => $isAz ? '150 AZN' : '87 USD']) }}</p>
                <ul>
                    @foreach($planPoints as $p)
                        <li>@include('frontend.partials.icon', ['n' => 'check']){{ __($p) }}</li>
                    @endforeach
                </ul>
                <a href="https://demo.{{ config('app.domain') }}" class="btn btn-primary">{{ __('landing.demo_btn') }}</a>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="section" id="faq">
    <div class="wrap">
        <div class="head-c">
            <h2 class="h2">{{ __('site.faq_title') }}</h2>
        </div>
        <div class="faq">
            @foreach([1, 2, 3, 4, 5] as $i)
                <details>
                    <summary>{{ __('site.faq_' . $i . '_q') }}</summary>
                    <p>{{ __('site.faq_' . $i . '_a') }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="section" style="padding-top:0">
    <div class="wrap">
        <div class="band">
            <h2>{{ __('landing.try_now') }}</h2>
            <p>{{ __('landing.try_desc') }}</p>
            <div class="cta">
                <a href="https://demo.{{ config('app.domain') }}" class="btn btn-primary">{{ __('landing.demo_btn') }}</a>
                <a href="https://app.{{ config('app.domain') }}" class="btn btn-ghost">{{ __('site.doctor_login') }}</a>
                <a href="tel:+994504321103" class="btn btn-ghost">{{ __('landing.contact') }}: +994 50 432 1 103</a>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.footer')
@include('frontend.partials.analytics')
</body>
</html>
