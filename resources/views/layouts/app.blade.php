<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[var(--paper)] text-[var(--ink)]">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:p-3">{{ __('global.skip_to_content') }}</a>

    <main id="main">
        @yield('content')
    </main>
</body>
</html>
