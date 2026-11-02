{{--
    Layout cho link chia sẻ bài làm IELTS (/ielts-share/{token}) — người xem
    có thể CHƯA đăng nhập nên không dùng layout panel (cần $authUser).
    Nạp cùng CSS nền với panel để trang kết quả / chữa bài hiển thị y hệt.

    $og = [
        'title'       => 'IELTS Cam 18 - Edtika',
        'description' => 'Luyện IELTS thông minh cùng Edtika',
        'url'         => link chia sẻ,
        'image'       => ['url', 'width', 'height', 'alt'] — ảnh 1:1 theo mức điểm,
    ]
--}}
@php
    $og = $og ?? [];
    $ogImage = $og['image'] ?? [];
    $siteName = !empty($generalSettings['site_name']) ? $generalSettings['site_name'] : 'Edtika';
    $favIcon = !empty($generalSettings['fav_icon']) ? url($generalSettings['fav_icon']) : null;
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Trang cá nhân của học viên: không cho Google lập chỉ mục --}}
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $og['title'] ?? ($pageTitle ?? $siteName) }}</title>
    <meta name="description" content="{{ $og['description'] ?? '' }}">
    @if($favIcon)
        <link rel="icon" href="{{ $favIcon }}">
    @endif

    {{-- ── Social Link Card ─────────────────────────────────────────── --}}
    <link rel="canonical" href="{{ $og['url'] ?? url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:url" content="{{ $og['url'] ?? url()->current() }}">
    <meta property="og:title" content="{{ $og['title'] ?? '' }}">
    <meta property="og:description" content="{{ $og['description'] ?? '' }}">
    @if(!empty($ogImage['url']))
        <meta property="og:image" content="{{ $ogImage['url'] }}">
        @if(\Illuminate\Support\Str::startsWith($ogImage['url'], 'https://'))
            <meta property="og:image:secure_url" content="{{ $ogImage['url'] }}">
        @endif
        @if(!empty($ogImage['width']) && !empty($ogImage['height']))
            <meta property="og:image:width" content="{{ $ogImage['width'] }}">
            <meta property="og:image:height" content="{{ $ogImage['height'] }}">
        @endif
        <meta property="og:image:alt" content="{{ $ogImage['alt'] ?? '' }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $og['title'] ?? '' }}">
    <meta name="twitter:description" content="{{ $og['description'] ?? '' }}">
    @if(!empty($ogImage['url']))
        <meta name="twitter:image" content="{{ $ogImage['url'] }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/design_1/css/app.min.css">
    <link rel="stylesheet" href="/assets/design_1/css/panel.min.css">

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        :root { --main-font-family: 'Roboto', sans-serif !important; }
        body, h1, h2, h3, h4, h5, h6, p, a, span, button, input, textarea, select, .btn, div {
            font-family: 'Roboto', sans-serif !important;
        }
    </style>
</head>
<body class="{{ ($userThemeColorMode ?? 'light') }}-mode ielts-share-page">

<div id="panel_app">
    @yield('content')

    @stack('panel_modals')
</div>

@stack('styles_bottom')
@stack('scripts_bottom')
@stack('scripts_bottom2')
</body>
</html>