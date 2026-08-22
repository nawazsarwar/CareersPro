<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>
                </div>
            @endif

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center pt-0 sm:justify-center sm:pt-0">
                    <!--<img src="https://oeps.amucoe.ac.in/images/logo_nav.png" width="300">-->
                    <img src="https://oeps.amucoe.ac.in/images/logo.png" width="250">
                </div>
                <div class="flex justify-center pt-0 sm:justify-center sm:pt-0">
                    <center>
                        <h1 style="font-size:2.5rem;color:darkred;margin-top: 0px;">{{ config('app.name') }}<br>@<br>Aligarh Muslim University</h1>
                        <h1 style="font-size:1.5rem;color:darkred;margin-top: -20px;">by Nawaz Sarwar</h1>
                    </center>
                </div>
            </div>
        </div>
    </body>
</html>
