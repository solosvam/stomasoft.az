@php
    $siteTitle = 'StomaSoft — ' . $title;
    $siteDesc  = $desc ?? __('welcome.meta_description');
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $siteTitle }}</title>

<link rel="canonical" href="{{ $canonical }}">
<meta name="robots" content="index, follow">
<meta name="description" content="{{ $siteDesc }}">
<meta name="keywords" content="{{ __('welcome.meta_keywords') }}">
<meta name="author" content="StomaSoft">
<meta name="theme-color" content="#0a62d8">

<meta property="og:title" content="{{ $siteTitle }}">
<meta property="og:description" content="{{ $siteDesc }}">
<meta property="og:image" content="{{ asset('frontend/img/og-image.jpg') }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:type" content="website">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $siteTitle }}">
<meta name="twitter:description" content="{{ $siteDesc }}">

<link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/site.css') }}?v={{ filemtime(public_path('frontend/css/site.css')) }}">
