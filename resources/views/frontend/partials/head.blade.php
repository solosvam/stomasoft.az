@php
    $siteTitle = 'StomaSoft — ' . $title;
    $canonical = $L->canonical();
    $ogImage   = 'https://' . config('app.domain') . '/frontend/img/og-image.jpg';
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $siteTitle }}</title>

<link rel="canonical" href="{{ $canonical }}">
@foreach($L->alternates() as $hreflang => $href)
<link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $href }}">
@endforeach
<meta name="robots" content="index, follow, max-image-preview:large">
<meta name="description" content="{{ $desc }}">
<meta name="author" content="StomaSoft">
<meta name="theme-color" content="#0a62d8">

<meta property="og:site_name" content="StomaSoft">
<meta property="og:title" content="{{ $siteTitle }}">
<meta property="og:description" content="{{ $desc }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="StomaSoft">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:type" content="website">
<meta property="og:locale" content="{{ $L->ogLocale() }}">
@foreach(\App\Support\Landing::LOCALES as $loc)
    @if($loc !== app()->getLocale())
<meta property="og:locale:alternate" content="{{ $L->ogLocale($loc) }}">
    @endif
@endforeach

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $siteTitle }}">
<meta name="twitter:description" content="{{ $desc }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/site.css') }}?v={{ filemtime(public_path('frontend/css/site.css')) }}">
@isset($jsonld)
<script type="application/ld+json">{!! json_encode($jsonld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endisset
