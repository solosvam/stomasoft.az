@php
    $title = 'Login';
    $description = 'Panelə giriş';
@endphp
@extends('admin.layout_full',['title'=>$title, 'description'=>$description])
@section('js_vendor')
    <script src="{{asset('backend/js/vendor/bootstrap-notify.min.js')}}"></script>
@endsection

@section('content_left')
    <div class="min-h-100 d-flex align-items-center position-relative overflow-hidden">

        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25"></div>

        <div class="w-100 px-5 position-relative">
            <img src="{{ asset('backend/img/logo/stomasoft-logo.svg') }}"
                 style="max-width:420px;width:70%;filter:drop-shadow(0 25px 45px rgba(0,0,0,.25));background: #fff;padding: 20px;border-radius: 20px"
                 class="mb-5">

            <h1 class="display-3 text-white fw-bold mb-4">
                Klinik idarəetməni<br>sadələşdir
            </h1>

            <p class="h5 text-white opacity-75 lh-lg mb-4">
                Pasient CRM, rezervasiya, kassa və hesabatlar bir paneldə.
            </p>

            <div class="d-flex flex-column gap-2 text-white">
                <div>✓ Pasient və xidmət idarəsi</div>
                <div>✓ Həkim balansı və kassa nəzarəti</div>
                <div>✓ Abunəlik əsaslı SaaS sistem</div>
            </div>
        </div>
    </div>
@endsection

@section('content_right')
    <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
        <div class="sw-lg-50 px-5">
            <div class="sh-11">
                <a href="/">
                    <div class="logo-default"></div>
                </a>
            </div>
            <div class="mb-5">
                <h2 class="cta-1 mb-0 text-primary">Xoş gəldin,</h2>
                <h2 class="cta-1 text-primary">başlayaq!</h2>
            </div>
            <div>
                @php
                    $isDemo = str_starts_with(request()->getHost(),'demo.');
                @endphp
                <form id="loginForm" class="tooltip-end-bottom" action="{{ route('admin.loginpost') }}" method="POST">
                    @csrf
                    <div class="mb-3 filled form-group tooltip-end-top">
                        <i data-acorn-icon="user"></i>

                        <input
                            class="form-control"
                            placeholder="Login"
                            name="login"
                            value="{{ $isDemo ? 'demo' : old('login') }}"
                        />

                        {!! validationResult('login',$errors) !!}
                    </div>

                    <div class="mb-3 filled form-group tooltip-end-top">
                        <i data-acorn-icon="lock-off"></i>

                        <input
                            class="form-control pe-7"
                            name="password"
                            type="{{ $isDemo ? 'text' : 'password' }}"
                            placeholder="Parol"
                            value="{{ $isDemo ? 'demo12345' : '' }}"
                        />

                        {!! validationResult('password',$errors) !!}
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary">Daxil ol</button>
                </form>
            </div>
        </div>
    </div>
@endsection
