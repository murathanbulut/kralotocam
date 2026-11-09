@props(['seo', 'bodyClass' => ''])
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo->fullTitle() }}</title>
    @if ($seo->metaDescription())
        <meta name="description" content="{{ $seo->metaDescription() }}">
    @endif
    @if ($seo->keywords)
        <meta name="keywords" content="{{ $seo->keywords }}">
    @endif
    <meta name="robots" content="{{ $seo->noindex ? 'noindex, nofollow' : 'index, follow, max-image-preview:large' }}">
    <link rel="canonical" href="{{ $seo->canonical }}">

    {{-- Open Graph / Twitter --}}
    <meta property="og:locale" content="tr_TR">
    <meta property="og:type" content="{{ $seo->type }}">
    <meta property="og:site_name" content="{{ setting('site_name') }}">
    <meta property="og:title" content="{{ $seo->fullTitle() }}">
    @if ($seo->metaDescription())
        <meta property="og:description" content="{{ $seo->metaDescription() }}">
    @endif
    <meta property="og:url" content="{{ $seo->canonical }}">
    @if ($seo->image)
        <meta property="og:image" content="{{ $seo->image }}">
    @endif
    @if ($seo->publishedTime)
        <meta property="article:published_time" content="{{ $seo->publishedTime }}">
        <meta property="article:modified_time" content="{{ $seo->modifiedTime }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo->fullTitle() }}">
    @if ($seo->metaDescription())
        <meta name="twitter:description" content="{{ $seo->metaDescription() }}">
    @endif
    @if ($seo->image)
        <meta name="twitter:image" content="{{ $seo->image }}">
    @endif
    @if (setting('google_site_verification'))
        <meta name="google-site-verification" content="{{ setting('google_site_verification') }}">
    @endif

    <link rel="icon" type="image/png" href="{{ media_url(setting('favicon'), 'images/theme/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ media_url(setting('favicon'), 'images/theme/favicon.png') }}">
    <meta name="theme-color" content="#151515">

    @if (feature('light_theme'))
        {{-- Açık tema yalnızca ziyaretçi seçtiyse uygulanır; sistem teması dikkate alınmaz. Varsayılan: koyu. --}}
        <script>try { if (localStorage.getItem('kral-theme') === 'light') { document.documentElement.setAttribute('data-theme', 'light'); } } catch (e) {}</script>
    @endif
    @fonts
    @vite(array_values(array_filter([
        'resources/css/app.css',
        feature('light_theme') ? 'resources/css/light-theme.css' : null,
        'resources/js/app.js',
    ])))

    @foreach ($seo->allSchema() as $schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    @endforeach
    @stack('head')
</head>
<body class="{{ trim($bodyClass.' '.feature_body_classes()) }}">
    <a class="skip-link" href="#icerik">İçeriğe geç</a>

    @include('partials.header')

    <main class="wrapper" id="icerik">
        {{ $slot }}
    </main>

    @include('partials.footer')
    @include('partials.floating-contact')

    @stack('scripts')
</body>
</html>
