<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('landing.title') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}">

    <style>
        body{font-family:Inter,Arial,sans-serif;background:#f7fbff;color:#102033}
        .hero{min-height:100vh;background:linear-gradient(135deg,#eef8ff,#ffffff)}
        .brand{font-size:28px;font-weight:800;color:#0d6efd}
        .hero-title{font-size:56px;font-weight:900;line-height:1.05}
        .hero-text{font-size:20px;color:#5b6b7d}
        .btn-main{padding:14px 28px;border-radius:14px;font-weight:700}
        .card-soft{border:0;border-radius:24px;box-shadow:0 15px 45px rgba(13,110,253,.10)}
        .feature-icon{width:58px;height:58px;border-radius:18px;background:#e7f1ff;color:#0d6efd;display:flex;align-items:center;justify-content:center;font-size:26px}
        .section-title{font-weight:900}
        .price-card{border:2px solid #0d6efd}
        .footer{background:#102033;color:#c8d3df}

        @media(max-width:991px){
            .navbar .container{gap:10px}
            .brand{font-size:24px}
            .hero{min-height:auto;padding-top:120px!important}
            .hero-title{font-size:38px}
            .hero-text{font-size:18px}
            .btn-main{width:100%;text-align:center}
            .mobile-actions{margin-left:auto}
        }

        @media(max-width:575px){
            .brand{font-size:22px}
            .hero-title{font-size:34px}
            .phone-link{width:100%;margin-bottom:5px}
            .top-buttons a{width:100%}
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand brand" href="#">StomaSoft</a>

        <div class="mobile-actions d-flex align-items-center gap-2 order-lg-3">
            <div class="dropdown">
                <button class="btn btn-light border rounded-pill dropdown-toggle px-3" type="button" data-bs-toggle="dropdown">
                    {{ strtoupper(app()->getLocale()) }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    @foreach(config('lang') as $key => $language)
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() == $key ? 'active' : '' }}"
                               href="{{ request()->fullUrlWithQuery(['lang' => $key]) }}">
                                {{ $language['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse order-lg-2" id="mainNavbar">
            <div class="ms-auto d-flex flex-wrap align-items-center gap-3 mt-3 mt-lg-0 top-buttons">
                <a href="tel:+994103227575" class="phone-link text-decoration-none fw-bold text-dark">
                    📞 +994 10 322 75 75
                </a>

                <a href="https://demo.{{ config('app.domain') }}" class="btn btn-outline-primary rounded-pill px-4">
                    {{ __('landing.demo') }}
                </a>

                <a href="https://app.{{ config('app.domain') }}" class="btn btn-primary rounded-pill px-4">
                    {{ __('landing.login') }}
                </a>
            </div>
        </div>
    </div>
</nav>

<section class="hero d-flex align-items-center pt-5">
    <div class="container pt-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-3">
                    {{ __('landing.crm_badge') }}
                </span>

                <h1 class="hero-title mb-4">
                    {{ __('landing.hero_title') }}
                </h1>

                <p class="hero-text mb-4">
                    {{ __('landing.hero_text') }}
                </p>

                <div class="d-flex flex-wrap gap-3">
                    <a href="https://demo.{{ config('app.domain') }}" class="btn btn-primary btn-main">
                        {{ __('landing.demo_btn') }}
                    </a>

                    <a href="https://app.{{ config('app.domain') }}" class="btn btn-outline-primary btn-main">
                        {{ __('landing.client_login') }}
                    </a>
                </div>

                <p class="mt-4 text-muted">
                    {{ __('landing.languages') }}
                </p>
            </div>

            <div class="col-lg-6">
                <div class="card card-soft p-4">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4">{{ __('landing.panel_features') }}</h4>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4">
                                    <h3 class="fw-bold text-primary">CRM</h3>
                                    <p class="mb-0 text-muted">{{ __('landing.patient_archive') }}</p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4">
                                    <h3 class="fw-bold text-primary">{{ __('landing.cashier') }}</h3>
                                    <p class="mb-0 text-muted">{{ __('landing.cashflow') }}</p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4">
                                    <h3 class="fw-bold text-primary">{{ __('landing.reservation') }}</h3>
                                    <p class="mb-0 text-muted">{{ __('landing.reservation_times') }}</p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4">
                                    <h3 class="fw-bold text-primary">{{ __('landing.report') }}</h3>
                                    <p class="mb-0 text-muted">{{ __('landing.finance_stats') }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="section-title">{{ __('landing.what_does') }}</h2>
            <p class="text-muted">{{ __('landing.simplifies') }}</p>
        </div>

        @php
            $features = [
                ['icon' => '🦷', 'title' => __('landing.patient_db'), 'text' => __('landing.patient_desc')],
                ['icon' => '📊', 'title' => __('landing.finance'), 'text' => __('landing.finance_desc')],
                ['icon' => '💳', 'title' => __('landing.cash_payment'), 'text' => __('landing.cash_desc')],
                ['icon' => '📅', 'title' => __('landing.reservation_system'), 'text' => __('landing.reservation_desc')],
                ['icon' => '🤝', 'title' => __('landing.partner'), 'text' => __('landing.partner_desc')],
                ['icon' => '📁', 'title' => __('landing.files'), 'text' => __('landing.files_desc')],
            ];
        @endphp

        <div class="row g-4">
            @foreach($features as $feature)
                <div class="col-md-4">
                    <div class="card card-soft h-100 p-4">
                        <div class="feature-icon mb-3">{{ $feature['icon'] }}</div>
                        <h5 class="fw-bold">{{ $feature['title'] }}</h5>
                        <p class="text-muted mb-0">{{ $feature['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="section-title mb-3">{{ __('landing.saas_title') }}</h2>

                <p class="text-muted fs-5">
                    {{ __('landing.saas_desc') }}
                </p>

                <ul class="list-unstyled fs-5">
                    <li class="mb-2">✅ {{ __('landing.fast') }}</li>
                    <li class="mb-2">✅ {{ __('landing.subscription') }}</li>
                    <li class="mb-2">✅ {{ __('landing.test') }}</li>
                    <li class="mb-2">✅ {{ __('landing.doctor_split') }}</li>
                    <li class="mb-2">✅ {{ __('landing.languages_short') }}</li>
                    <li class="mb-2">✅ {{ __('landing.receipt') }}</li>
                </ul>
            </div>

            <div class="col-lg-6">
                <div class="card card-soft price-card p-4">
                    <div class="card-body text-center">
                        <h3 class="fw-bold">{{ __('landing.package') }}</h3>
                        <p class="text-muted">{{ __('landing.package_desc') }}</p>

                        <div class="display-4 fw-bold text-primary my-4">
                            99 AZN
                            <span class="fs-5 text-muted">/ {{ __('landing.month') }}</span>
                        </div>

                        <p class="text-muted">
                            {{ __('landing.package_full') }}
                        </p>

                        <a href="https://demo.{{ config('app.domain') }}" class="btn btn-primary btn-main w-100">
                            {{ __('landing.demo_go') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-primary text-white">
    <div class="container py-4 text-center">
        <h2 class="fw-bold mb-3">{{ __('landing.try_now') }}</h2>

        <p class="mb-4 fs-5 opacity-75">
            {{ __('landing.try_desc') }}
        </p>

        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="https://demo.{{ config('app.domain') }}" class="btn btn-light btn-main text-primary">
                {{ __('landing.demo_login') }}
            </a>

            <a href="https://app.{{ config('app.domain') }}" class="btn btn-outline-light btn-main">
                {{ __('landing.client_panel') }}
            </a>
        </div>

        <p class="mt-4 mb-0 opacity-75">
            {{ __('landing.contact') }}: +994 10 322 75 75
        </p>
    </div>
</section>

<footer class="footer py-4">
    <div class="container d-flex flex-wrap justify-content-between gap-3 align-items-center">
        <div>
            <b>StomaSoft</b> — {{ __('landing.footer') }}
        </div>

        <div>
            📞 +994 10 322 75 75
        </div>

        <div>
            © {{ date('Y') }}
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
