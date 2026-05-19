<!DOCTYPE html>
<html lang="en" data-url-prefix="/" data-footer="true"
@isset($html_tag_data)
    @foreach ($html_tag_data as $key=> $value) data-{{$key}}='{{$value}}' @endforeach
@endisset
>
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <title>StomaSoft | {{$title}}</title>
        <meta name="description" content=""/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('admin._layout.head')
    </head>
    <body>
        <div id="root">
            @if(session('impersonate_original_id'))
                <div style="background:#e24b4a; color:#fff; padding:10px 20px; text-align:center; font-size:13px;z-index: 999999;position: fixed;bottom:0;">
                    ⚠️ Siz {{ auth()->user()->fullname }} kimi girişsiniz.
                    <a href="{{ route('admin.impersonate.stop') }}" style="color:#fff; font-weight:700; margin-left:12px;">
                        Öz profilinə qayıt →
                    </a>
                </div>
            @endif
            @if(!empty($subscriptionWarning))
                <div style="background:#dc3545;color:white;text-align:center;padding:10px;font-weight:600;top:0;width: 100%;z-index: 999999;position: absolute">
                    Abunəliyiniz {{ $subscriptionEndDate->format('d.m.Y') }} tarixində bitir.
                    Ödəniş edilməsə sistem dayandırılacaq.
                </div>
            @endif
            <div id="nav" class="nav-container d-flex" @isset($custom_nav_data) @foreach ($custom_nav_data as $key=> $value)
            data-{{$key}}="{{$value}}"
                @endforeach
                @endisset
            >
                @include('admin._layout.nav')
            </div>
            <main>
                @yield('content')
            </main>
            @include('admin._layout.footer')
        </div>

        @include('admin._layout.modal_settings')
        @include('admin._layout.modal_search')
        @include('admin._layout.scripts')
    </body>
</html>
