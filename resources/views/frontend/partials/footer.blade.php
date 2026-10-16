<footer class="foot">
    <div class="wrap foot-in">
        <div>
            <a href="{{ $L->url('home') }}" class="brand">
                <img src="{{ asset('frontend/img/logo-mark.svg') }}" alt="" width="28" height="26">
                <span>Stoma<b>Soft</b></span>
            </a>
            <div style="font-size:13.5px;color:var(--mut);margin-top:8px">{{ __('landing.footer') }} · © {{ date('Y') }}</div>
            <div class="foot-credit">{{ __('site.developed_by') }}: <a href="https://svam.az" target="_blank" rel="noopener">SVAM MMC</a></div>
        </div>
        <div class="foot-links">
            <a href="tel:+994504321103">+994 50 432 1 103</a>
            <a href="https://demo.{{ config('app.domain') }}">{{ __('landing.demo') }}</a>
            <a href="https://app.{{ config('app.domain') }}">{{ __('site.doctor_login') }}</a>
            <a href="{{ $L->url('patients') }}">{{ __('landing.for_patients') }}</a>
        </div>
    </div>
</footer>
