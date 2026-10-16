@php
    $phone     = '+994 50 432 1 103';
    $phoneTel  = '+994504321103';
    $demoUrl   = 'https://demo.' . config('app.domain');
    $appUrl    = 'https://app.' . config('app.domain');
    $active    = $active ?? '';
@endphp
<header class="nav">
    <div class="wrap nav-in">
        <a href="{{ $L->url('home') }}" class="brand">
            <img src="{{ asset('frontend/img/logo-mark.svg') }}" alt="" width="34" height="32">
            <span class="brand-t">Stoma<b>Soft</b></span>
        </a>

        <nav class="nav-links">
            <a href="{{ $L->url('home') }}#features" @class(['on' => $active === 'home'])>{{ __('site.nav_features') }}</a>
            <a href="{{ $L->url('home') }}#audience">{{ __('site.nav_audience') }}</a>
            <a href="{{ $L->url('doctors') }}#tarif" @class(['on' => $active === 'doctors'])>{{ __('site.nav_pricing') }}</a>
            <a href="{{ $L->url('doctors') }}#faq">{{ __('site.nav_faq') }}</a>
        </nav>

        <div class="nav-right">
            <details class="lang">
                <summary>{{ strtoupper(app()->getLocale()) }}</summary>
                <div class="lang-menu">
                    @foreach(config('lang') as $key => $language)
                        <a href="{{ $L->switchUrl($key) }}" hreflang="{{ $key }}" lang="{{ $key }}" @class(['on' => app()->getLocale() === $key])>{{ $language['name'] }}</a>
                    @endforeach
                </div>
            </details>
            <a href="{{ $demoUrl }}" class="btn btn-ghost btn-sm">{{ __('landing.demo') }}</a>
            <a href="{{ $L->url('patients') }}" @class(['btn', 'btn-soft', 'btn-sm', 'on' => $active === 'patients'])>{{ __('landing.for_patients') }}</a>
            <a href="{{ $appUrl }}" class="btn btn-primary btn-sm">{{ __('site.doctor_login') }}</a>
        </div>
    </div>
</header>
