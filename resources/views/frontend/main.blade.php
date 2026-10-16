<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $isAz = app()->getLocale() === 'az';
        $jsonld = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                [
                    '@type' => 'Organization',
                    '@id'   => 'https://svam.az/#org',
                    'name'  => 'SVAM MMC',
                    'url'   => 'https://svam.az',
                ],
                [
                    '@type'               => 'SoftwareApplication',
                    'name'                => 'StomaSoft',
                    'url'                 => $L->canonical(),
                    'image'               => 'https://' . config('app.domain') . '/frontend/img/og-image.jpg',
                    'description'         => __('site.seo_home_desc'),
                    'applicationCategory' => 'BusinessApplication',
                    'operatingSystem'     => 'Web',
                    'inLanguage'          => ['az', 'en', 'ru', 'tr'],
                    'publisher'           => ['@id' => 'https://svam.az/#org'],
                    'offers'              => [
                        '@type'         => 'AggregateOffer',
                        'priceCurrency' => $isAz ? 'AZN' : 'USD',
                        'lowPrice'      => $isAz ? '50' : '29',
                        'highPrice'     => $isAz ? '99' : '59',
                        'offerCount'    => 2,
                    ],
                ],
            ],
        ];
    @endphp
    @include('frontend.partials.head', [
        'title'  => __('site.seo_home_title'),
        'desc'   => __('site.seo_home_desc'),
        'jsonld' => $jsonld,
    ])
</head>
<body>

@include('frontend.partials.nav', ['active' => 'home'])

{{-- Hero --}}
<section class="hero">
    <img class="hero-mark" src="{{ asset('frontend/img/logo-mark.svg') }}" alt="">
    <div class="wrap hero-grid">
        <div>
            <div class="pill"><i></i>{{ __('site.hero_badge') }}</div>
            <h1>{{ __('site.hero_title') }}</h1>
            <p class="lead">{{ __('site.hero_text') }}</p>

            <div class="cta">
                <a href="{{ $demoUrl ?? 'https://demo.' . config('app.domain') }}" class="btn btn-primary">
                    {{ __('landing.demo_btn') }} @include('frontend.partials.icon', ['n' => 'arrow'])
                </a>
                <a href="https://app.{{ config('app.domain') }}" class="btn btn-ghost">{{ __('site.doctor_login') }}</a>
            </div>

            <div class="trust">
                <span>@include('frontend.partials.icon', ['n' => 'check']){{ __('site.trust_1') }}</span>
                <span>@include('frontend.partials.icon', ['n' => 'check']){{ __('site.trust_2') }}</span>
                <span>@include('frontend.partials.icon', ['n' => 'check']){{ __('site.trust_3') }}</span>
            </div>
        </div>

        <div class="hero-visual">
            <div class="win">
                <div class="win-bar"><i></i><i></i><i></i><span class="win-url">app.stomasoft.az/reservations</span></div>
                <div class="win-body">
                    <div class="win-title">{{ __('site.m_today') }} <small>Dr. N. Əliyeva</small></div>
                    <div class="row"><span class="t">10:00</span><span class="n">Aysel M.<small>{{ __('site.svc_clean') }}</small></span><span class="chip ok">{{ __('site.m_done') }}</span></div>
                    <div class="row now"><span class="t">11:30</span><span class="n">Nicat H.<small>{{ __('site.svc_caries') }}</small></span><span class="chip blue">{{ __('site.m_now') }}</span></div>
                    <div class="row"><span class="t">13:00</span><span class="n">Leyla Q.<small>{{ __('site.svc_implant') }}</small></span><span class="chip wait">{{ __('site.m_waiting') }}</span></div>
                    <div class="row" style="margin-bottom:0"><span class="t">14:30</span><span class="n">Tural S.<small>{{ __('site.svc_whitening') }}</small></span><span class="chip wait">{{ __('site.m_waiting') }}</span></div>
                </div>
            </div>
            <div class="float a green">{{ __('site.m_income_month') }}<b>1 150 ₼</b></div>
            <div class="float b">{{ __('site.m_online') }}<b>+3</b></div>
        </div>
    </div>
</section>

{{-- Showcase --}}
<section class="section" id="features">
    <div class="wrap">
        <div class="head-c">
            <div class="kicker">{{ __('landing.what_does') }}</div>
            <h2 class="h2">{{ __('site.show_title') }}</h2>
            <p class="sub">{{ __('landing.simplifies') }}</p>
        </div>

        {{-- A. Reservation --}}
        <div class="split">
            <div class="copy">
                <div class="kicker">{{ __('site.a_kicker') }}</div>
                <h3 class="h2">{{ __('site.a_title') }}</h3>
                <p class="sub">{{ __('site.a_text') }}</p>
                <ul class="ticks">
                    @foreach(['a_b1', 'a_b2', 'a_b3'] as $k)
                        <li>@include('frontend.partials.icon', ['n' => 'check']){{ __('site.' . $k) }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="win">
                <div class="win-bar"><i></i><i></i><i></i><span class="win-url">stomasoft.az/patients</span></div>
                <div class="win-body">
                    <div class="mini-field"><small>{{ __('patients.service') }}</small>{{ __('site.svc_caries') }}</div>
                    <div class="mini-field"><small>{{ __('patients.date') }}</small>16.10.2026</div>
                    <div class="win-title" style="margin:14px 0 8px">{{ __('patients.choose_time') }}</div>
                    <div class="slots">
                        <span class="x">10:00</span><span class="x">10:30</span><span>11:00</span><span>11:30</span>
                        <span class="sel">12:00</span><span>12:30</span><span class="x">13:00</span><span>13:30</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- B. Patient card --}}
        <div class="split rev">
            <div class="copy">
                <div class="kicker">{{ __('site.b_kicker') }}</div>
                <h3 class="h2">{{ __('site.b_title') }}</h3>
                <p class="sub">{{ __('site.b_text') }}</p>
                <ul class="ticks">
                    @foreach(['b_b1', 'b_b2', 'b_b3'] as $k)
                        <li>@include('frontend.partials.icon', ['n' => 'check']){{ __('site.' . $k) }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="win">
                <div class="win-bar"><i></i><i></i><i></i><span class="win-url">app.stomasoft.az/patient</span></div>
                <div class="win-body pcard">
                    <div class="pcard-side">
                        <div class="ava">AM</div>
                        <div class="nm">Aysel Məmmədova</div>
                        <div class="ph">+994 50 000 00 00</div>
                        <span class="pbtn">{{ __('site.m_balance') }} 40 ₼</span>
                        <span class="pbtn red">{{ __('landing.reservation') }}</span>
                        <span class="pbtn">{{ __('landing.files') }} 2</span>
                    </div>
                    <table class="tbl">
                        <thead><tr><th>{{ __('site.m_tooth') }}</th><th>{{ __('patients.service') }}</th><th>{{ __('amount') }}</th></tr></thead>
                        <tbody>
                            <tr><td>16</td><td>{{ __('site.svc_caries') }}</td><td>60 ₼</td></tr>
                            <tr><td>26</td><td>{{ __('site.svc_clean') }}</td><td>40 ₼</td></tr>
                            <tr><td>21</td><td>{{ __('site.svc_crown') }}</td><td>180 ₼</td></tr>
                            <tr class="tot"><td colspan="2">{{ __('total') }}</td><td>280 ₼</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- C. Cashier --}}
        <div class="split">
            <div class="copy">
                <div class="kicker">{{ __('site.c_kicker') }}</div>
                <h3 class="h2">{{ __('site.c_title') }}</h3>
                <p class="sub">{{ __('site.c_text') }}</p>
                <ul class="ticks">
                    @foreach(['c_b1', 'c_b2', 'c_b3'] as $k)
                        <li>@include('frontend.partials.icon', ['n' => 'check']){{ __('site.' . $k) }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="win">
                <div class="win-bar"><i></i><i></i><i></i><span class="win-url">app.stomasoft.az/cashier</span></div>
                <div class="win-body">
                    <div class="stats3">
                        <div class="stat in">{{ __('site.m_income_month') }}<b>1 150 ₼</b></div>
                        <div class="stat out">{{ __('site.m_expense_month') }}<b>670 ₼</b></div>
                        <div class="stat net">{{ __('site.m_net') }}<b>480 ₼</b></div>
                    </div>
                    <div class="slots" style="grid-template-columns:repeat(3,1fr);margin-top:12px">
                        <span>{{ __('cash') }}</span><span>{{ __('pos') }}</span><span>{{ __('c2c') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- D. Partners --}}
        <div class="split rev">
            <div class="copy">
                <div class="kicker">{{ __('landing.partner') }}</div>
                <h3 class="h2">{{ __('site.d_title') }}</h3>
                <p class="sub">{{ __('site.d_text') }}</p>
                <ul class="ticks">
                    @foreach(['d_b1', 'd_b2', 'd_b3'] as $k)
                        <li>@include('frontend.partials.icon', ['n' => 'check']){{ __('site.' . $k) }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="win">
                <div class="win-bar"><i></i><i></i><i></i><span class="win-url">app.stomasoft.az/partners</span></div>
                <div class="win-body">
                    <div class="win-title">DentaLab <small>{{ __('site.m_ledger') }}</small></div>
                    <table class="tbl">
                        <thead><tr><th>{{ __('site.m_job') }}</th><th>{{ __('amount') }}</th></tr></thead>
                        <tbody>
                            <tr><td>{{ __('site.svc_crown') }} · Aysel M.</td><td>120 ₼</td></tr>
                            <tr><td>{{ __('site.svc_crown') }} · Tural S.</td><td>90 ₼</td></tr>
                            <tr><td>{{ __('site.m_payment') }}</td><td style="color:var(--ok)">− 170 ₼</td></tr>
                            <tr class="tot"><td>{{ __('site.m_owe') }}</td><td>40 ₼</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Audience --}}
<section class="section alt" id="audience">
    <div class="wrap">
        <div class="head-c">
            <div class="kicker">{{ __('site.nav_audience') }}</div>
            <h2 class="h2">{{ __('site.aud_title') }}</h2>
            <p class="sub">{{ __('site.aud_text') }}</p>
        </div>
        <div class="grid3">
            <div class="card">
                <div class="ico">@include('frontend.partials.icon', ['n' => 'users'])</div>
                <h3>{{ __('site.aud_solo_t') }}</h3>
                <p>{{ __('site.aud_solo_d') }}</p>
            </div>
            <div class="card">
                <div class="ico">@include('frontend.partials.icon', ['n' => 'shield'])</div>
                <h3>{{ __('site.aud_clinic_t') }}</h3>
                <p>{{ __('site.aud_clinic_d') }}</p>
            </div>
            <div class="card">
                <div class="ico">@include('frontend.partials.icon', ['n' => 'calendar'])</div>
                <h3>{{ __('landing.for_patients') }}</h3>
                <p>{{ __('site.aud_patient_d') }}</p>
                <a href="{{ $L->url('patients') }}" class="more">{{ __('patients.book') }} @include('frontend.partials.icon', ['n' => 'arrow'])</a>
            </div>
        </div>
    </div>
</section>

{{-- Steps --}}
<section class="section">
    <div class="wrap">
        <div class="head-c">
            <h2 class="h2">{{ __('site.steps_title') }}</h2>
        </div>
        <div class="steps">
            @foreach([1, 2, 3, 4] as $i)
                <div class="step">
                    <h3>{{ __('site.step_' . $i . '_t') }}</h3>
                    <p>{{ __('site.step_' . $i . '_d') }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="section" style="padding-top:0">
    <div class="wrap">
        <div class="band">
            <h2>{{ __('site.cta_title') }}</h2>
            <p>{{ __('site.cta_text') }}</p>
            <div class="cta">
                <a href="https://demo.{{ config('app.domain') }}" class="btn btn-primary">{{ __('landing.demo_btn') }}</a>
                <a href="{{ $L->url('doctors') }}#tarif" class="btn btn-ghost">{{ __('site.see_pricing') }}</a>
                <a href="tel:+994504321103" class="btn btn-ghost">+994 50 432 1 103</a>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.footer')
@include('frontend.partials.analytics')
</body>
</html>
