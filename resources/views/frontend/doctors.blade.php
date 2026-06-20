<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StomaSoft — {{ __('welcome.doctors_title') }}</title>

    <link rel="canonical" href="https://stomasoft.az/doctors">
    <meta name="robots" content="index, follow">
    <meta name="description" content="{{ __('welcome.meta_description') }}">
    <meta name="keywords" content="{{ __('welcome.meta_keywords') }}">
    <meta name="author" content="StomaSoft">

    <meta property="og:title" content="StomaSoft — {{ __('welcome.doctors_title') }}">
    <meta property="og:description" content="{{ __('welcome.meta_description') }}">
    <meta property="og:image" content="{{ asset('frontend/img/og-image.jpg') }}">
    <meta property="og:url" content="https://stomasoft.az/doctors">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="StomaSoft">
    <meta name="twitter:description" content="{{ __('welcome.meta_description') }}">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}">

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></noscript>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #07111f;
            color: #fff;
            min-height: 100vh;
        }

        /* ── Background SVG (same motif family as main / patients) ── */
        .bg-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.06;
        }

        .glow {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .glow-blue { width: 600px; height: 600px; background: radial-gradient(circle, rgba(79,163,247,0.18) 0%, transparent 70%); top: -160px; right: -120px; }
        .glow-mint { width: 500px; height: 500px; background: radial-gradient(circle, rgba(46,204,153,0.14) 0%, transparent 70%); bottom: -120px; left: -100px; }

        /* ── Navbar (patients-style: back arrow + logo) ── */
        .top-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 40px;
            background: rgba(7,17,31,0.75);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 0.5px solid rgba(255,255,255,0.07);
        }

        .nav-left { display: flex; align-items: center; gap: 16px; }

        .nav-back {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
            transition: color 0.15s;
        }
        .nav-back:hover { color: rgba(255,255,255,0.7); }
        .nav-back svg { width: 16px; height: 16px; }

        .nav-sep { width: 1px; height: 16px; background: rgba(255,255,255,0.1); }

        .nav-logo {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            letter-spacing: -0.3px;
        }
        .nav-logo span { color: #4fa3f7; }

        .nav-right { display: flex; align-items: center; gap: 8px; }

        .nav-phone {
            font-size: 13px;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
        }

        .lang-dropdown { position: relative; }
        .lang-dropdown .btn {
            background: rgba(255,255,255,0.06);
            border: 0.5px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.6);
            font-size: 12px;
            padding: 5px 12px;
            border-radius: 20px;
        }
        .lang-dropdown .dropdown-menu {
            background: #0d1e35;
            border: 0.5px solid rgba(255,255,255,0.1);
            min-width: 100px;
        }
        .lang-dropdown .dropdown-item { color: rgba(255,255,255,0.6); font-size: 13px; }
        .lang-dropdown .dropdown-item:hover,
        .lang-dropdown .dropdown-item.active { background: rgba(46,204,153,0.15); color: #2ecc99; }

        .nav-cta {
            display: flex;
            gap: 8px;
        }
        .nav-cta a {
            font-size: 13px;
            font-weight: 600;
            padding: 7px 16px;
            border-radius: 20px;
            text-decoration: none;
            transition: opacity 0.15s, background 0.15s;
            white-space: nowrap;
        }
        .nav-cta .btn-ghost {
            color: rgba(255,255,255,0.7);
            background: rgba(255,255,255,0.06);
            border: 0.5px solid rgba(255,255,255,0.12);
        }
        .nav-cta .btn-ghost:hover { background: rgba(255,255,255,0.1); }
        .nav-cta .btn-solid {
            color: #07111f;
            background: #4fa3f7;
        }
        .nav-cta .btn-solid:hover { opacity: 0.88; }

        /* ── Page ── */
        .page {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
            padding: 120px 24px 0;
        }

        /* ── Hero ── */
        .hero {
            text-align: center;
            margin-bottom: 56px;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(79,163,247,0.12);
            border: 0.5px solid rgba(79,163,247,0.25);
            color: #4fa3f7;
            font-size: 12px;
            padding: 5px 16px;
            border-radius: 20px;
            margin-bottom: 18px;
        }
        .badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #2ecc99;
            box-shadow: 0 0 0 3px rgba(46,204,153,0.18);
        }

        .hero h1 {
            font-size: 38px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 14px;
            letter-spacing: -0.6px;
            line-height: 1.15;
        }

        .hero p {
            font-size: 15.5px;
            color: rgba(255,255,255,0.45);
            max-width: 480px;
            margin: 0 auto 30px;
            line-height: 1.65;
        }

        .hero-cta {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 26px;
        }
        .hero-cta a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14.5px;
            font-weight: 700;
            padding: 12px 26px;
            border-radius: 12px;
            text-decoration: none;
            transition: opacity 0.15s, transform 0.15s;
        }
        .btn-primary-cta { background: #4fa3f7; color: #07111f; }
        .btn-primary-cta:hover { transform: translateY(-1px); opacity: 0.92; }
        .btn-outline-cta {
            background: rgba(255,255,255,0.05);
            border: 0.5px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.85);
        }
        .btn-outline-cta:hover { background: rgba(255,255,255,0.09); }

        .hero-lang-note {
            font-size: 13px;
            color: rgba(255,255,255,0.3);
        }

        /* ── Feature pill strip ── */
        .panel-strip {
            display: flex;
            justify-content: center;
            gap: 28px;
            flex-wrap: wrap;
            padding: 22px 0 56px;
            border-bottom: 0.5px solid rgba(255,255,255,0.07);
            margin-bottom: 56px;
        }
        .panel-strip .feat-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255,255,255,0.45);
        }
        .feat-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #2ecc99;
        }

        /* ── Section scaffolding ── */
        .section { margin-bottom: 72px; }
        .section-head {
            text-align: center;
            max-width: 560px;
            margin: 0 auto 36px;
        }
        .section-kicker {
            font-size: 12px;
            font-weight: 700;
            color: #4fa3f7;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 10px;
        }
        .section-head h2 {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.4px;
            margin-bottom: 10px;
        }
        .section-head p {
            font-size: 14.5px;
            color: rgba(255,255,255,0.4);
            line-height: 1.6;
        }

        /* ── Feature grid ── */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .feature-card {
            background: rgba(255,255,255,0.04);
            border: 0.5px solid rgba(255,255,255,0.1);
            border-radius: 18px;
            padding: 24px 22px;
            transition: background 0.2s, border-color 0.2s, transform 0.2s;
        }
        .feature-card:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(79,163,247,0.3);
            transform: translateY(-3px);
        }
        .feature-icon-wrap {
            width: 42px; height: 42px;
            border-radius: 11px;
            background: rgba(79,163,247,0.12);
            display: flex; align-items: center; justify-content: center;
            font-size: 19px;
            margin-bottom: 16px;
        }
        .feature-card h3 {
            font-size: 15.5px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 7px;
        }
        .feature-card p {
            font-size: 13.5px;
            color: rgba(255,255,255,0.4);
            line-height: 1.6;
        }

        /* ── Pricing ── */
        .pricing-wrap {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }
        .price-card {
            background: rgba(255,255,255,0.04);
            border: 0.5px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 30px 28px;
            position: relative;
        }
        .price-card.feat {
            border: 0.5px solid rgba(46,204,153,0.35);
            background: rgba(46,204,153,0.05);
        }
        .price-badge {
            position: absolute;
            top: -12px; left: 28px;
            background: #2ecc99;
            color: #07111f;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            letter-spacing: 0.02em;
        }
        .price-card h3 { font-size: 16.5px; font-weight: 700; color: #fff; margin-bottom: 5px; }
        .price-card .sub { font-size: 13px; color: rgba(255,255,255,0.4); margin-bottom: 22px; }
        .price-amount { display: flex; align-items: baseline; gap: 7px; margin-bottom: 4px; }
        .price-amount .num { font-size: 36px; font-weight: 800; color: #fff; letter-spacing: -0.5px; }
        .price-amount .per { font-size: 13.5px; color: rgba(255,255,255,0.4); font-weight: 600; }
        .price-total { font-size: 12.5px; color: rgba(255,255,255,0.35); margin-bottom: 22px; }

        .price-list { list-style: none; margin-bottom: 24px; }
        .price-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            color: rgba(255,255,255,0.7);
            padding: 6px 0;
        }
        .check {
            width: 17px; height: 17px;
            border-radius: 50%;
            background: rgba(46,204,153,0.18);
            color: #2ecc99;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .price-btn {
            display: block;
            text-align: center;
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: opacity 0.15s, background 0.15s;
        }
        .price-btn.ghost {
            background: rgba(255,255,255,0.06);
            border: 0.5px solid rgba(255,255,255,0.14);
            color: rgba(255,255,255,0.85);
        }
        .price-btn.ghost:hover { background: rgba(255,255,255,0.1); }
        .price-btn.solid { background: #2ecc99; color: #07111f; }
        .price-btn.solid:hover { opacity: 0.9; }

        /* ── CTA band ── */
        .cta-band {
            background: rgba(255,255,255,0.04);
            border: 0.5px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 56px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-band::before {
            content: "";
            position: absolute;
            width: 420px; height: 420px;
            background: radial-gradient(circle, rgba(46,204,153,0.16), transparent 70%);
            top: -180px; right: -100px;
        }
        .cta-band h2 {
            position: relative; z-index: 1;
            font-size: 27px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 12px;
            letter-spacing: -0.4px;
        }
        .cta-band p {
            position: relative; z-index: 1;
            font-size: 14.5px;
            color: rgba(255,255,255,0.45);
            margin-bottom: 28px;
        }
        .cta-actions {
            position: relative; z-index: 1;
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .cta-actions a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            transition: opacity 0.15s;
        }
        .cta-phone {
            position: relative; z-index: 1;
            margin-top: 22px;
            font-size: 13px;
            color: rgba(255,255,255,0.3);
        }

        /* ── Footer ── */
        .page-footer {
            margin-top: 56px;
            padding: 28px 0 40px;
            border-top: 0.5px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
        }
        .footer-link { font-size: 13px; color: rgba(255,255,255,0.25); text-decoration: none; }
        .footer-sep { width: 1px; height: 14px; background: rgba(255,255,255,0.08); }

        /* ── Mobile ── */
        @media (max-width: 760px) {
            .feature-grid { grid-template-columns: 1fr 1fr; }
            .pricing-wrap { grid-template-columns: 1fr; }
        }
        @media (max-width: 600px) {
            .top-nav { padding: 14px 16px; }
            .nav-phone { display: none; }
            .nav-cta .btn-ghost { display: none; }
            .page { padding: 100px 16px 0; }
            .hero h1 { font-size: 28px; }
            .feature-grid { grid-template-columns: 1fr; }
            .cta-band { padding: 40px 24px; }
        }

        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; }
        }
    </style>
</head>
<body>

{{-- Background SVG — same dental + digital motif family as main / patients --}}
<svg class="bg-canvas" viewBox="0 0 1440 900" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
    <g fill="none" stroke="white" stroke-width="1">
        <path d="M120,160 C120,110 148,82 185,82 C222,82 238,110 232,138 C226,158 210,168 216,196 C222,224 242,232 236,262 C228,296 185,304 170,276 C155,248 162,228 150,206 C136,180 120,205 120,160Z" stroke-width="1.2"/>
        <circle cx="185" cy="138" r="12"/><line x1="173" y1="138" x2="197" y2="138"/><line x1="185" y1="126" x2="185" y2="150"/>

        <path d="M1260,100 C1260,58 1283,36 1312,36 C1341,36 1352,58 1347,80 C1343,97 1332,104 1336,122 C1340,140 1352,145 1348,165 C1342,188 1312,194 1302,174 C1292,154 1298,140 1291,122 C1282,102 1260,130 1260,100Z"/>
        <circle cx="1312" cy="80" r="10"/><line x1="1302" y1="80" x2="1322" y2="80"/><line x1="1312" y1="70" x2="1312" y2="90"/>

        <path d="M60,520 C60,468 92,438 134,438 C176,438 194,468 186,500 C180,524 160,534 168,566 C176,598 198,608 190,642 C180,682 134,692 116,660 C98,628 108,606 94,580 C76,550 60,568 60,520Z" stroke-width="1"/>

        <rect x="580" y="40" width="100" height="80" rx="8"/>
        <line x1="580" y1="80" x2="680" y2="80"/>
        <line x1="600" y1="56" x2="660" y2="56"/>
        <line x1="600" y1="96" x2="650" y2="96"/>
        <line x1="680" y1="60" x2="720" y2="60"/>
        <line x1="680" y1="100" x2="720" y2="100"/>
        <circle cx="726" cy="60" r="5"/>
        <circle cx="726" cy="100" r="5"/>

        <rect x="1100" y="700" width="90" height="70" rx="7"/>
        <line x1="1100" y1="735" x2="1190" y2="735"/>
        <line x1="1114" y1="716" x2="1176" y2="716"/>
        <line x1="1114" y1="754" x2="1162" y2="754"/>

        <circle cx="820" cy="160" r="40"/><circle cx="820" cy="160" r="28"/><circle cx="820" cy="160" r="14"/>
        <line x1="780" y1="160" x2="760" y2="160"/><line x1="860" y1="160" x2="880" y2="160"/>
        <line x1="820" y1="120" x2="820" y2="100"/><line x1="820" y1="200" x2="820" y2="220"/>

        <circle cx="380" cy="700" r="30"/><circle cx="380" cy="700" r="18"/>
        <line x1="350" y1="700" x2="330" y2="700"/><line x1="410" y1="700" x2="430" y2="700"/>
        <line x1="380" y1="670" x2="380" y2="650"/><line x1="380" y1="730" x2="380" y2="750"/>

        <path d="M0,300 Q80,270 160,300 Q240,330 320,300 Q400,270 480,300" stroke-dasharray="6,5"/>
        <path d="M960,600 Q1040,570 1120,600 Q1200,630 1280,600 Q1360,570 1440,600" stroke-dasharray="6,5"/>
        <path d="M400,820 Q520,790 640,820 Q760,850 880,820" stroke-dasharray="6,5"/>

        <g fill="white" opacity="0.8">
            <circle cx="100"  cy="400" r="2.5"/>
            <circle cx="300"  cy="200" r="2"/>
            <circle cx="500"  cy="500" r="2.5"/>
            <circle cx="700"  cy="350" r="2"/>
            <circle cx="900"  cy="80"  r="2"/>
            <circle cx="1050" cy="450" r="2.5"/>
            <circle cx="1200" cy="280" r="2"/>
            <circle cx="1400" cy="400" r="2.5"/>
            <circle cx="200"  cy="620" r="2"/>
            <circle cx="650"  cy="750" r="2.5"/>
            <circle cx="1100" cy="600" r="2"/>
            <circle cx="750"  cy="500" r="2"/>
        </g>
    </g>
</svg>

<div class="glow glow-blue"></div>
<div class="glow glow-mint"></div>

{{-- Navbar --}}
<nav class="top-nav">
    <div class="nav-left">
        <a href="{{ url('/') }}" class="nav-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            {{ __('welcome.headline') }}
        </a>
        <div class="nav-sep"></div>
        <a href="{{ url('/') }}" class="nav-logo">Stoma<span>Soft</span></a>
    </div>

    <div class="nav-right">
        <a href="tel:+994504321103" class="nav-phone d-none d-md-block">📞 +994 50 432 1 103</a>

        <div class="dropdown lang-dropdown">
            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
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

        <div class="nav-cta">
            <a href="https://demo.{{ config('app.domain') }}" class="btn-ghost">{{ __('landing.demo') }}</a>
            <a href="https://app.{{ config('app.domain') }}" class="btn-solid">{{ __('landing.login') }}</a>
        </div>
    </div>
</nav>

{{-- Page --}}
<div class="page">

    {{-- Hero --}}
    <div class="hero">
        <div class="badge-pill"><span class="badge-dot"></span>{{ __('landing.crm_badge') }}</div>

        <h1>{{ __('landing.hero_title') }}</h1>
        <p>{{ __('landing.hero_text') }}</p>

        <div class="hero-cta">
            <a href="https://demo.{{ config('app.domain') }}" class="btn-primary-cta">
                {{ __('landing.demo_btn') }}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="https://app.{{ config('app.domain') }}" class="btn-outline-cta">{{ __('landing.client_login') }}</a>
        </div>

        <div class="hero-lang-note">🌐 {{ __('landing.languages') }}</div>
    </div>

    {{-- Panel feature strip --}}
    <div class="panel-strip">
        <div class="feat-item"><span class="feat-dot"></span>CRM</div>
        <div class="feat-item"><span class="feat-dot"></span>{{ __('landing.cashier') }}</div>
        <div class="feat-item"><span class="feat-dot"></span>{{ __('landing.reservation') }}</div>
        <div class="feat-item"><span class="feat-dot"></span>{{ __('landing.report') }}</div>
    </div>

    {{-- What does StomaSoft do --}}
    <div class="section">
        <div class="section-head">
            <div class="section-kicker">{{ __('landing.what_does') }}</div>
            <h2>{{ __('landing.what_does') }}</h2>
            <p>{{ __('landing.simplifies') }}</p>
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

        <div class="feature-grid">
            @foreach($features as $feature)
                <div class="feature-card">
                    <div class="feature-icon-wrap">{{ $feature['icon'] }}</div>
                    <h3>{{ $feature['title'] }}</h3>
                    <p>{{ $feature['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Pricing --}}
    @php $isAz = app()->getLocale() == 'az'; @endphp

    <div class="section">
        <div class="section-head">
            <div class="section-kicker">{{ __('landing.saas_title') }}</div>
            <h2>{{ __('landing.saas_title') }}</h2>
            <p>{{ __('landing.saas_desc') }}</p>
        </div>

        <div class="pricing-wrap">
            <div class="price-card">
                <h3>{{ __('landing.package') }}</h3>
                <p class="sub">{{ __('landing.package_desc') }}</p>

                <div class="price-amount">
                    <span class="num">{{ $isAz ? '99 AZN' : '59 USD' }}</span>
                    <span class="per">/ {{ __('landing.month') }}</span>
                </div>
                <p class="price-total">{{ __('landing.package_full') }}</p>

                <ul class="price-list">
                    <li><span class="check">✓</span>{{ __('landing.fast') }}</li>
                    <li><span class="check">✓</span>{{ __('landing.doctor_split') }}</li>
                    <li><span class="check">✓</span>{{ __('landing.languages_short') }}</li>
                    <li><span class="check">✓</span>{{ __('landing.receipt') }}</li>
                </ul>

                <a href="https://demo.{{ config('app.domain') }}" class="price-btn ghost">{{ __('landing.demo_go') }}</a>
            </div>

            <div class="price-card feat">
                <span class="price-badge">{{ __('landing.best_value') }}</span>

                <h3>{{ __('landing.package_3m') }}</h3>
                <p class="sub">{{ __('landing.package_3m_desc') }}</p>

                <div class="price-amount">
                    <span class="num">{{ $isAz ? '50 AZN' : '29 USD' }}</span>
                    <span class="per">/ {{ __('landing.month') }}</span>
                </div>
                <p class="price-total">{{ $isAz ? 'Cəmi 150 AZN, 3 ay üçün ödəniş' : 'Total 87 USD, billed for 3 months' }}</p>

                <ul class="price-list">
                    <li><span class="check">✓</span>{{ __('landing.fast') }}</li>
                    <li><span class="check">✓</span>{{ __('landing.doctor_split') }}</li>
                    <li><span class="check">✓</span>{{ __('landing.languages_short') }}</li>
                    <li><span class="check">✓</span>{{ __('landing.receipt') }}</li>
                </ul>

                <a href="https://demo.{{ config('app.domain') }}" class="price-btn solid">{{ __('landing.demo_go') }}</a>
            </div>
        </div>
    </div>

    {{-- CTA band --}}
    <div class="section">
        <div class="cta-band">
            <h2>{{ __('landing.try_now') }}</h2>
            <p>{{ __('landing.try_desc') }}</p>

            <div class="cta-actions">
                <a href="https://demo.{{ config('app.domain') }}" class="btn-primary-cta">{{ __('landing.demo_login') }}</a>
                <a href="https://app.{{ config('app.domain') }}" class="btn-outline-cta">{{ __('landing.client_panel') }}</a>
            </div>

            <p class="cta-phone">{{ __('landing.contact') }}: +994 50 432 1 103</p>
        </div>
    </div>

    {{-- Footer --}}
    <div class="page-footer">
        <span class="footer-link">{{ __('landing.footer') }}</span>
        <div class="footer-sep"></div>
        <a href="tel:+994504321103" class="footer-link">+994 50 432 1 103</a>
        <div class="footer-sep"></div>
        <span class="footer-link">© {{ date('Y') }} StomaSoft</span>
    </div>

</div>

<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    window.addEventListener('load', function () {
        setTimeout(function () {
            var s = document.createElement('script');
            s.async = true;
            s.src = 'https://www.googletagmanager.com/gtag/js?id=G-0NQDFDGYRT';
            document.head.appendChild(s);
            window.dataLayer = window.dataLayer || [];
            function gtag(){ dataLayer.push(arguments); }
            window.gtag = gtag;
            gtag('js', new Date());
            gtag('config', 'G-0NQDFDGYRT');
        }, 2500);
    });
</script>

</body>
</html>
