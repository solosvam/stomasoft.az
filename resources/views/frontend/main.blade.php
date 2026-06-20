<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StomaSoft — {{ __('welcome.meta_title') }}</title>

    <link rel="canonical" href="https://stomasoft.az">
    <meta name="robots" content="index, follow">
    <meta name="description" content="{{ __('welcome.meta_description') }}">
    <meta name="keywords" content="{{ __('welcome.meta_keywords') }}">
    <meta name="author" content="StomaSoft">

    <meta property="og:title" content="StomaSoft — {{ __('welcome.meta_title') }}">
    <meta property="og:description" content="{{ __('welcome.meta_description') }}">
    <meta property="og:image" content="{{ asset('frontend/img/og-image.jpg') }}">
    <meta property="og:url" content="https://stomasoft.az">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="StomaSoft">
    <meta name="twitter:description" content="{{ __('welcome.meta_description') }}">
    <meta name="twitter:image" content="{{ asset('frontend/img/og-image.jpg') }}">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}">

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></noscript>
    <link href="{{ asset('frontend/css/landing.css') }}" rel="stylesheet">
</head>
<body>

{{-- Background SVG (teeth + digital elements) --}}
<svg class="bg-canvas" viewBox="0 0 1440 900" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
    <g fill="none" stroke="rgba(255,255,255,0.055)" stroke-width="1">
        {{-- Tooth shapes --}}
        <path d="M120,160 C120,110 148,82 185,82 C222,82 238,110 232,138 C226,158 210,168 216,196 C222,224 242,232 236,262 C228,296 185,304 170,276 C155,248 162,228 150,206 C136,180 120,205 120,160Z" stroke-width="1.3"/>
        <circle cx="185" cy="138" r="12"/><line x1="173" y1="138" x2="197" y2="138"/><line x1="185" y1="126" x2="185" y2="150"/>

        <path d="M1260,100 C1260,58 1283,36 1312,36 C1341,36 1352,58 1347,80 C1343,97 1332,104 1336,122 C1340,140 1352,145 1348,165 C1342,188 1312,194 1302,174 C1292,154 1298,140 1291,122 C1282,102 1260,130 1260,100Z" stroke-width="1.1"/>
        <circle cx="1312" cy="80" r="10"/><line x1="1302" y1="80" x2="1322" y2="80"/><line x1="1312" y1="70" x2="1312" y2="90"/>

        <path d="M60,520 C60,468 92,438 134,438 C176,438 194,468 186,500 C180,524 160,534 168,566 C176,598 198,608 190,642 C180,682 134,692 116,660 C98,628 108,606 94,580 C76,550 60,568 60,520Z" stroke-width="1"/>

        <path d="M1340,560 C1340,516 1368,490 1400,490 C1432,490 1444,516 1439,540 C1435,558 1422,566 1426,586 C1430,606 1444,612 1440,634 C1433,660 1400,668 1388,644 C1376,620 1384,604 1376,584 C1366,562 1340,584 1340,560Z" stroke-width="0.9"/>

        {{-- Small tooth upper right area --}}
        <path d="M950,50 C950,26 964,12 982,12 C1000,12 1007,26 1004,40 C1001,52 993,57 996,70 C999,83 1007,87 1004,102 C1000,118 982,122 974,107 C966,92 971,83 966,70 C959,56 950,72 950,50Z" stroke-width="0.8"/>

        {{-- Digital/circuit elements --}}
        <rect x="580" y="40" width="100" height="80" rx="8"/>
        <line x1="580" y1="80" x2="680" y2="80"/>
        <line x1="600" y1="56" x2="660" y2="56"/>
        <line x1="600" y1="96" x2="650" y2="96"/>
        <line x1="680" y1="60" x2="720" y2="60"/>
        <line x1="680" y1="100" x2="720" y2="100"/>
        <circle cx="726" cy="60" r="5"/>
        <circle cx="726" cy="100" r="5"/>

        <rect x="200" y="750" width="80" height="65" rx="6"/>
        <line x1="200" y1="782" x2="280" y2="782"/>
        <line x1="212" y1="764" x2="268" y2="764"/>
        <line x1="212" y1="800" x2="248" y2="800"/>
        <line x1="160" y1="775" x2="200" y2="775"/>
        <circle cx="154" cy="775" r="5"/>

        <rect x="1100" y="700" width="90" height="70" rx="7"/>
        <line x1="1100" y1="735" x2="1190" y2="735"/>
        <line x1="1114" y1="716" x2="1176" y2="716"/>
        <line x1="1114" y1="754" x2="1162" y2="754"/>

        {{-- Circuit dots and lines --}}
        <circle cx="820" cy="160" r="40"/><circle cx="820" cy="160" r="28"/><circle cx="820" cy="160" r="14"/>
        <line x1="780" y1="160" x2="760" y2="160"/><line x1="860" y1="160" x2="880" y2="160"/>
        <line x1="820" y1="120" x2="820" y2="100"/><line x1="820" y1="200" x2="820" y2="220"/>

        <circle cx="380" cy="700" r="30"/><circle cx="380" cy="700" r="18"/>
        <line x1="350" y1="700" x2="330" y2="700"/><line x1="410" y1="700" x2="430" y2="700"/>
        <line x1="380" y1="670" x2="380" y2="650"/><line x1="380" y1="730" x2="380" y2="750"/>

        {{-- Dashed data flow lines --}}
        <path d="M0,300 Q80,270 160,300 Q240,330 320,300 Q400,270 480,300" stroke-dasharray="6,5"/>
        <path d="M960,600 Q1040,570 1120,600 Q1200,630 1280,600 Q1360,570 1440,600" stroke-dasharray="6,5"/>
        <path d="M400,820 Q520,790 640,820 Q760,850 880,820" stroke-dasharray="6,5"/>

        {{-- Grid dots --}}
        <g fill="rgba(255,255,255,0.07)" stroke="none">
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

        {{-- Small cross markers --}}
        <g stroke="rgba(79,163,247,0.15)" stroke-width="1.2">
            <line x1="455" y1="245" x2="465" y2="245"/><line x1="460" y1="240" x2="460" y2="250"/>
            <line x1="1000" y1="380" x2="1010" y2="380"/><line x1="1005" y1="375" x2="1005" y2="385"/>
            <line x1="280" y1="480" x2="290" y2="480"/><line x1="285" y1="475" x2="285" y2="485"/>
            <line x1="1180" y1="150" x2="1190" y2="150"/><line x1="1185" y1="145" x2="1185" y2="155"/>
        </g>
    </g>
</svg>

<div class="glow glow-blue"></div>
<div class="glow glow-teal"></div>

{{-- Navbar --}}
<nav class="top-nav">
    <a class="nav-logo" href="#">Stoma<span>Soft</span></a>

    <div class="nav-right">
        <a href="tel:+994504321103" class="nav-phone d-none d-md-block">
            📞 +994 10 322 75 75
        </a>

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

{{-- Main content --}}
<div class="page">

    {{-- Hero --}}
    <div class="hero-top">
        <div class="badge-pill">{{ __('welcome.badge') }}</div>
        <div class="hero-logo">Stoma<span>Soft</span></div>
        <div class="hero-headline">{{ __('welcome.headline') }}</div>
        <div class="hero-sub">{{ __('welcome.subheadline') }}</div>
    </div>

    {{-- Selection cards --}}
    <div class="cards-grid">

        {{-- Doctors --}}
        <a href="{{ url('/doctors') }}" class="sel-card blue">
            <div class="card-icon-wrap icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
            </div>
            <div>
                <div class="card-label label-blue">{{ __('welcome.for_label') }}</div>
                <div class="card-title">{{ __('welcome.doctors_title') }}</div>
            </div>
            <div class="card-url-hint">stomasoft.az/doctors</div>
            <div class="card-desc">{{ __('welcome.doctors_desc') }}</div>
            <div class="card-cta cta-blue">
                {{ __('welcome.doctors_cta') }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        {{-- Patients --}}
        <a href="{{ url('/patients') }}" class="sel-card green">
            <div class="card-icon-wrap icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                    <polyline points="9 16 11 18 15 14"/>
                </svg>
            </div>
            <div>
                <div class="card-label label-green">{{ __('welcome.for_label') }}</div>
                <div class="card-title">{{ __('welcome.patients_title') }}</div>
            </div>
            <div class="card-url-hint">stomasoft.az/patients</div>
            <div class="card-desc">{{ __('welcome.patients_desc') }}</div>
            <div class="card-cta cta-green">
                {{ __('welcome.patients_cta') }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

    </div>

    {{-- Feature strip --}}
    <div class="features-strip">
        @foreach(['welcome.feature_1','welcome.feature_2','welcome.feature_3','welcome.feature_4'] as $feat)
            <div class="feat-item">
                <div class="feat-dot"></div>
                <span>{{ __($feat) }}</span>
            </div>
        @endforeach
    </div>

    {{-- Footer --}}
    <div class="page-footer">
        <a href="tel:+994504321103" class="footer-link">+994 10 322 75 75</a>
        <div class="footer-sep"></div>
        <a href="https://demo.{{ config('app.domain') }}" class="footer-link">{{ __('welcome.demo') }}</a>
        <div class="footer-sep"></div>
        <a href="https://app.{{ config('app.domain') }}" class="footer-link">{{ __('welcome.login') }}</a>
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
