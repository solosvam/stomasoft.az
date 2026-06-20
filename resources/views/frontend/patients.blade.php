<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StomaSoft — {{ __('welcome.patients_title') }}</title>

    <link rel="canonical" href="https://stomasoft.az/patients">
    <meta name="robots" content="index, follow">
    <meta name="description" content="{{ __('welcome.meta_description') }}">
    <meta name="keywords" content="{{ __('welcome.meta_keywords') }}">
    <meta name="author" content="StomaSoft">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        /* ── Background SVG ── */
        .bg-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.05;
        }

        .glow {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .glow-green { width: 600px; height: 600px; background: radial-gradient(circle, rgba(15,110,86,0.2) 0%, transparent 70%); top: -150px; left: -100px; }
        .glow-blue  { width: 500px; height: 500px; background: radial-gradient(circle, rgba(24,95,165,0.15) 0%, transparent 70%); bottom: -100px; right: -80px; }

        /* ── Navbar ── */
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

        /* ── Page ── */
        .page {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
            padding: 120px 24px 80px;
        }

        /* ── Hero ── */
        .hero {
            text-align: center;
            margin-bottom: 48px;
        }

        .badge-pill {
            display: inline-block;
            background: rgba(46,204,153,0.12);
            border: 0.5px solid rgba(46,204,153,0.25);
            color: #2ecc99;
            font-size: 12px;
            padding: 5px 16px;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .hero h1 {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .hero p {
            font-size: 15px;
            color: rgba(255,255,255,0.4);
            max-width: 420px;
            margin: 0 auto 28px;
            line-height: 1.6;
        }

        /* ── Search ── */
        .search-wrap {
            max-width: 460px;
            margin: 0 auto;
            position: relative;
        }

        .search-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: rgba(255,255,255,0.25);
        }

        .search-wrap input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 0.5px solid rgba(255,255,255,0.12);
            border-radius: 12px;
            padding: 12px 16px 12px 40px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s;
        }
        .search-wrap input:focus { border-color: rgba(46,204,153,0.35); }
        .search-wrap input::placeholder { color: rgba(255,255,255,0.25); }

        /* ── Grid ── */
        .clinics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
        }

        /* ── Clinic card ── */
        .clinic-card {
            background: rgba(255,255,255,0.04);
            border: 0.5px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 24px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .clinic-card:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(46,204,153,0.3);
            transform: translateY(-3px);
        }

        .card-top { display: flex; align-items: center; gap: 14px; }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .clinic-name { font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 3px; }
        .doctor-name { font-size: 13px; color: rgba(255,255,255,0.4); }

        .card-address {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 13px;
            color: rgba(255,255,255,0.3);
            line-height: 1.5;
        }
        .card-address svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 0.5px solid rgba(255,255,255,0.06);
        }

        .avail-badge {
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 20px;
        }
        .avail-open  { background: rgba(46,204,153,0.1); color: #2ecc99; border: 0.5px solid rgba(46,204,153,0.2); }
        .avail-busy  { background: rgba(226,75,74,0.1);  color: #e24b4a; border: 0.5px solid rgba(226,75,74,0.2); }

        .book-btn {
            font-size: 13px;
            font-weight: 600;
            color: #2ecc99;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: gap 0.15s;
        }
        .clinic-card:hover .book-btn { gap: 8px; }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255,255,255,0.25);
            font-size: 15px;
            display: none;
        }

        /* ── Modal overlay ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.65);
            z-index: 300;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(4px);
        }
        .modal-overlay.active { display: flex; }

        /* ── Modal box ── */
        .modal-box {
            background: #0d1e35;
            border: 0.5px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 28px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.2s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .modal-title { font-size: 18px; font-weight: 700; color: #fff; }

        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,0.06);
            border: none;
            color: rgba(255,255,255,0.4);
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            transition: background 0.15s;
        }
        .modal-close:hover { background: rgba(255,255,255,0.1); color: #fff; }

        /* ── Modal clinic info ── */
        .modal-clinic-info {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.04);
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 22px;
        }

        .modal-avatar {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .modal-clinic-name { font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 3px; }
        .modal-doctor-name { font-size: 12px; color: rgba(255,255,255,0.4); }

        /* ── Form fields ── */
        .field { margin-bottom: 16px; }

        .field-label {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.35);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 7px;
            display: block;
        }

        .field input,
        .field select {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 0.5px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            padding: 11px 14px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s;
            appearance: none;
        }
        .field input:focus,
        .field select:focus { border-color: rgba(46,204,153,0.4); }
        .field input::placeholder { color: rgba(255,255,255,0.2); }

        .field select option { background: #0d1e35; color: #fff; }

        /* ── Time slots ── */
        .time-section { margin-bottom: 20px; }

        .time-label {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.35);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
            display: block;
        }

        #hours-container .slot-btn {
            border-radius: 10px;
            padding: 10px 6px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            min-width: 70px;
            transition: all 0.15s;
        }

        #hours-container .btn-success {
            background: rgba(46,204,153,0.15);
            color: #2ecc99;
            border: 0.5px solid rgba(46,204,153,0.25);
        }
        #hours-container .btn-success:hover {
            background: rgba(46,204,153,0.3);
        }
        #hours-container .btn-success.selected {
            background: #2ecc99;
            color: #07111f;
            border-color: #2ecc99;
        }

        #hours-container .btn-danger {
            background: rgba(226,75,74,0.08);
            color: rgba(226,75,74,0.4);
            border: 0.5px solid rgba(226,75,74,0.15);
            text-decoration: line-through;
            cursor: not-allowed;
        }

        .hours-loading {
            text-align: center;
            padding: 20px;
            color: rgba(255,255,255,0.25);
            font-size: 13px;
        }

        .hours-placeholder {
            text-align: center;
            padding: 20px;
            color: rgba(255,255,255,0.2);
            font-size: 13px;
            border: 0.5px dashed rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        /* ── Alert messages ── */
        .alert-custom {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .alert-success { background: rgba(46,204,153,0.12); border: 0.5px solid rgba(46,204,153,0.25); color: #2ecc99; }
        .alert-error   { background: rgba(226,75,74,0.12);  border: 0.5px solid rgba(226,75,74,0.25);  color: #e24b4a; }

        /* ── Submit button ── */
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #2ecc99;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            color: #07111f;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.1s;
            margin-top: 4px;
        }
        .submit-btn:hover   { opacity: 0.9; }
        .submit-btn:active  { transform: scale(0.98); }
        .submit-btn:disabled { opacity: 0.4; cursor: not-allowed; }

        /* ── Footer ── */
        .page-footer {
            margin-top: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
        }
        .footer-link { font-size: 13px; color: rgba(255,255,255,0.2); text-decoration: none; }
        .footer-sep  { width: 1px; height: 14px; background: rgba(255,255,255,0.08); }

        /* ── Mobile ── */
        @media (max-width: 600px) {
            .top-nav { padding: 14px 16px; }
            .nav-phone { display: none; }
            .page { padding: 100px 16px 60px; }
            .hero h1 { font-size: 24px; }
            .clinics-grid { grid-template-columns: 1fr; }
            .modal-box { padding: 20px; }
        }
    </style>
</head>
<body>

{{-- Background --}}
<svg class="bg-canvas" viewBox="0 0 1440 900" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
    <g fill="none" stroke="white" stroke-width="1">
        <path d="M120,160 C120,110 148,82 185,82 C222,82 238,110 232,138 C226,158 210,168 216,196 C222,224 242,232 236,262 C228,296 185,304 170,276 C155,248 162,228 150,206 C136,180 120,205 120,160Z" stroke-width="1.2"/>
        <circle cx="185" cy="138" r="12"/><line x1="173" y1="138" x2="197" y2="138"/><line x1="185" y1="126" x2="185" y2="150"/>
        <path d="M1260,100 C1260,58 1283,36 1312,36 C1341,36 1352,58 1347,80 C1343,97 1332,104 1336,122 C1340,140 1352,145 1348,165 C1342,188 1312,194 1302,174 C1292,154 1298,140 1291,122 C1282,102 1260,130 1260,100Z"/>
        <rect x="580" y="40" width="100" height="80" rx="8"/><line x1="580" y1="80" x2="680" y2="80"/><line x1="600" y1="56" x2="660" y2="56"/>
        <circle cx="820" cy="160" r="40"/><circle cx="820" cy="160" r="28"/><circle cx="820" cy="160" r="14"/>
        <line x1="780" y1="160" x2="760" y2="160"/><line x1="860" y1="160" x2="880" y2="160"/>
        <path d="M0,300 Q80,270 160,300 Q240,330 320,300 Q400,270 480,300" stroke-dasharray="6,5"/>
        <path d="M960,600 Q1040,570 1120,600 Q1200,630 1280,600 Q1360,570 1440,600" stroke-dasharray="6,5"/>
        <g fill="white" opacity="0.8">
            <circle cx="100" cy="400" r="2.5"/><circle cx="500" cy="500" r="2.5"/>
            <circle cx="900" cy="80"  r="2"/><circle cx="1200" cy="280" r="2"/>
            <circle cx="650" cy="750" r="2.5"/>
        </g>
    </g>
</svg>

<div class="glow glow-green"></div>
<div class="glow glow-blue"></div>

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
        <a href="tel:+994504321103" class="nav-phone d-none d-md-block">📞 +994 10 322 75 75</a>

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
    </div>
</nav>

{{-- Page --}}
<div class="page">

    {{-- Hero --}}
    <div class="hero">
        <div class="badge-pill">🦷 {{ __('patients.badge') }}</div>
        <h1>{{ __('patients.headline') }}</h1>
        <p>{{ __('patients.subheadline') }}</p>

        <div class="search-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" id="searchInput" placeholder="{{ __('patients.search_placeholder') }}">
        </div>
    </div>

    {{-- Clinics grid --}}
    <div class="clinics-grid" id="clinicsGrid">
        @php
            $colors = [
                ['bg' => 'rgba(46,204,153,0.15)',  'color' => '#2ecc99'],
                ['bg' => 'rgba(79,163,247,0.15)',  'color' => '#4fa3f7'],
                ['bg' => 'rgba(239,159,39,0.15)',  'color' => '#ef9f27'],
                ['bg' => 'rgba(212,83,126,0.15)',  'color' => '#d4537e'],
                ['bg' => 'rgba(127,119,221,0.15)', 'color' => '#7f77dd'],
            ];
        @endphp

        @forelse($doctors as $index => $doctor)
            @php
                $color   = $colors[$index % count($colors)];
                $initials = collect(explode(' ', $doctor->clinic_name))
                                ->take(2)
                                ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                ->implode('');
                $services = $doctor->services ?? collect();
            @endphp

            <div class="clinic-card"
                 data-search="{{ strtolower($doctor->clinic_name . ' ' . $doctor->fullname . ' ' . $doctor->clinic_address) }}"
                 onclick="openModal({{ $doctor->id }}, '{{ addslashes($doctor->clinic_name) }}', '{{ addslashes($doctor->fullname) }}', '{{ addslashes($doctor->clinic_address) }}', '{{ $initials }}', '{{ $color['bg'] }}', '{{ $color['color'] }}', {{ $services->toJson() }})">

                <div class="card-top">
                    <div class="avatar" style="background: {{ $color['bg'] }}; color: {{ $color['color'] }}">
                        {{ $initials }}
                    </div>
                    <div>
                        <div class="clinic-name">{{ $doctor->clinic_name }}</div>
                        <div class="doctor-name">Dr. {{ $doctor->fullname }}</div>
                    </div>
                </div>

                <div class="card-address">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                        <circle cx="12" cy="9" r="2.5"/>
                    </svg>
                    {{ $doctor->clinic_address }}
                </div>

                <div class="card-footer">
                    <span class="avail-badge avail-open">{{ __('patients.available') }}</span>
                    <span class="book-btn">
                        {{ __('patients.book') }}
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </div>

        @empty
            <div class="empty-state" style="display:block; grid-column: 1/-1;">
                {{ __('patients.no_clinics') }}
            </div>
        @endforelse
    </div>

    <div class="empty-state" id="noResults">{{ __('patients.no_results') }}</div>

    {{-- Footer --}}
    <div class="page-footer">
        <a href="tel:+994504321103" class="footer-link">+994 10 322 75 75</a>
        <div class="footer-sep"></div>
        <a href="{{ url('/') }}" class="footer-link">StomaSoft</a>
        <div class="footer-sep"></div>
        <span class="footer-link">© {{ date('Y') }}</span>
    </div>

</div>

{{-- ═══════════════════════════════════════
     MODAL
════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalOverlay" onclick="closeModalOnBg(event)">
    <div class="modal-box" id="modalBox">

        <div class="modal-header">
            <div class="modal-title">{{ __('patients.modal_title') }}</div>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>

        {{-- Clinic info --}}
        <div class="modal-clinic-info">
            <div class="modal-avatar" id="modalAvatar"></div>
            <div>
                <div class="modal-clinic-name" id="modalClinicName"></div>
                <div class="modal-doctor-name" id="modalDoctorName"></div>
            </div>
        </div>

        {{-- Flash messages --}}
        <div id="modalAlert" style="display:none"></div>

        {{-- Form --}}
        <form id="reservationForm" onsubmit="submitReservation(event)">
            @csrf
            <input type="hidden" id="doctorIdInput" name="doctor_id">

            <div class="field">
                <label class="field-label">{{ __('patients.fullname') }}</label>
                <input type="text" name="fullname" id="fullnameInput"
                       placeholder="{{ __('patients.fullname_placeholder') }}" autocomplete="name">
            </div>

            <div class="field">
                <label class="field-label">{{ __('patients.mobile') }}</label>
                <input type="tel" name="mobile" id="mobileInput"
                       placeholder="+994 XX XXX XX XX" autocomplete="tel">
            </div>

            <div class="field">
                <label class="field-label">{{ __('patients.service') }}</label>
                <select name="service_id" id="serviceSelect">
                    <option value="">{{ __('patients.service_placeholder') }}</option>
                </select>
            </div>

            <div class="field">
                <label class="field-label">{{ __('patients.date') }}</label>
                <input type="date" name="date" id="dateInput" min="{{ date('Y-m-d') }}"
                       onchange="loadHours()">
            </div>

            <input type="hidden" name="hour" id="hourInput">

            <div class="time-section">
                <span class="time-label">{{ __('patients.choose_time') }}</span>
                <div id="hours-container">
                    <div class="hours-placeholder">{{ __('patients.date_first') }}</div>
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                {{ __('patients.submit') }}
            </button>
        </form>

    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let currentDoctorId = null;

    /* ── Open modal ── */
    function openModal(doctorId, clinicName, doctorName, address, initials, avatarBg, avatarColor, services) {
        currentDoctorId = doctorId;

        document.getElementById('doctorIdInput').value = doctorId;
        document.getElementById('modalClinicName').textContent = clinicName;
        document.getElementById('modalDoctorName').textContent = 'Dr. ' + doctorName;

        const avatar = document.getElementById('modalAvatar');
        avatar.textContent = initials;
        avatar.style.background = avatarBg;
        avatar.style.color = avatarColor;

        const serviceSelect = document.getElementById('serviceSelect');
        serviceSelect.innerHTML = '<option value="">{{ __('patients.service_placeholder') }}</option>';
        if (services && services.length) {
            services.forEach(function(s) {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.name;
                serviceSelect.appendChild(opt);
            });
        }

        document.getElementById('dateInput').value = '';
        document.getElementById('hourInput').value = '';
        document.getElementById('hours-container').innerHTML = '<div class="hours-placeholder">{{ __('patients.date_first') }}</div>';
        document.getElementById('modalAlert').style.display = 'none';
        document.getElementById('reservationForm').reset();
        document.getElementById('doctorIdInput').value = doctorId;

        document.getElementById('modalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    /* ── Close modal ── */
    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('active');
        document.body.style.overflow = '';
        currentDoctorId = null;
    }

    function closeModalOnBg(e) {
        if (e.target === document.getElementById('modalOverlay')) closeModal();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    /* ── Load hours via AJAX ── */
    function loadHours() {
        const date = document.getElementById('dateInput').value;
        const doctorId = currentDoctorId;

        if (!date || !doctorId) return;

        document.getElementById('hourInput').value = '';
        document.getElementById('hours-container').innerHTML = '<div class="hours-loading">{{ __('patients.loading') }}</div>';

        fetch('{{ route('page.reservation.hours') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ date: date, doctor: doctorId })
        })
        .then(function(r) { return r.text(); })
        .then(function(html) {
            document.getElementById('hours-container').innerHTML = html || '<div class="hours-placeholder">{{ __('patients.no_slots') }}</div>';
        })
        .catch(function() {
            document.getElementById('hours-container').innerHTML = '<div class="hours-placeholder">{{ __('patients.error') }}</div>';
        });
    }

    /* ── Time slot select (called from reservationHours HTML) ── */
    function setFrontTime(id, time) {
        document.querySelectorAll('#hours-container .slot-btn.btn-success').forEach(function(btn) {
            btn.classList.remove('selected');
        });
        const btn = document.getElementById(id);
        if (btn) btn.classList.add('selected');
        document.getElementById('hourInput').value = time;
    }

    /* ── Submit reservation ── */
    function submitReservation(e) {
        e.preventDefault();

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = '{{ __('patients.sending') }}';

        const formData = new FormData(document.getElementById('reservationForm'));

        fetch('{{ route('page.reservation.store') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            const alertBox = document.getElementById('modalAlert');
            alertBox.style.display = 'block';

            if (data.success) {
                alertBox.className = 'alert-custom alert-success';
                alertBox.textContent = data.message || '{{ __('patients.success') }}';
                document.getElementById('reservationForm').reset();
                document.getElementById('hours-container').innerHTML = '<div class="hours-placeholder">{{ __('patients.date_first') }}</div>';
                setTimeout(closeModal, 2500);
            } else {
                alertBox.className = 'alert-custom alert-error';
                alertBox.textContent = data.message || '{{ __('patients.error') }}';
            }
        })
        .catch(function() {
            const alertBox = document.getElementById('modalAlert');
            alertBox.style.display = 'block';
            alertBox.className = 'alert-custom alert-error';
            alertBox.textContent = '{{ __('patients.error') }}';
        })
        .finally(function() {
            btn.disabled = false;
            btn.textContent = '{{ __('patients.submit') }}';
        });
    }

    /* ── Search / filter ── */
    document.getElementById('searchInput').addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.clinic-card');
        let visible = 0;

        cards.forEach(function(card) {
            const match = !q || card.dataset.search.includes(q);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
    });
</script>

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
