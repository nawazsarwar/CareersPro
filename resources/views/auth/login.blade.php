@extends('layouts.guest')

@section('content')
<h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">Login to CareersPro</h2>

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div>
        <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            Email Address
        </label>
        <input id="email" type="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="mt-4">
        <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            Password
        </label>
        <input id="password" type="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white" name="password" required autocomplete="current-password">
        @error('password')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="block mt-4">
        <label for="remember_me" class="inline-flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
        </label>
    </div>

    <div class="flex items-center justify-end mt-4">
        @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="{{ route('password.request') }}">
                Forgot your password?
            </a>
        @endif

        <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            Log in
        </button>
    </div>

    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
        Don't have an account?
        <a href="{{ route('register') }}" class="underline hover:text-gray-900 dark:hover:text-white">Register here</a>
    </div>
</form>
@endsection
