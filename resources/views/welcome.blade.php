<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
    <main class="mx-auto flex min-h-screen max-w-xl flex-col justify-center px-6">
        <h1 class="text-2xl font-semibold tracking-tight">{{ config('app.name') }}</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            {{ __('global.foundation_placeholder') }}
        </p>
    </main>
</body>
</html>
