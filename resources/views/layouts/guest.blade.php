<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CareersPro') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Apply dark mode based on system preference to prevent flash -->
    <script>
        (function() {
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            if (systemTheme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark', 'bg-[#0c120f]');
            } else {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark', 'bg-[#0c120f]');
            }
        })();
    </script>
</head>
<body class="min-h-screen bg-gray-50 dark:bg-[#0c120f] text-gray-900 dark:text-gray-100 font-sans antialiased overflow-x-hidden selection:bg-indigo-600 selection:text-white relative">
    @yield('content')
</body>
</html>
