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

    {{-- M25 §7: persistent, high-contrast, and it names both parties. An
         administrator who forgets they are impersonating will act as somebody
         else; the record will be right and the action will still be wrong. --}}
    @isset($impersonator)
        @if ($impersonator)
            <div role="status" class="flex items-center justify-between gap-4 bg-[var(--brass)] px-4 py-2 text-sm text-white">
                <span>{{ __('access.impersonating', ['name' => auth()->user()?->name]) }}</span>
                <form method="POST" action="{{ route('frontend.impersonate.stop') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="underline">{{ __('access.end_impersonation') }}</button>
                </form>
            </div>
        @endif
    @endisset

    <main id="main">
        @yield('content')
    </main>
</body>
</html>
