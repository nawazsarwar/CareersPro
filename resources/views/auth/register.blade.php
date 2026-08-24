@extends('layouts.guest')

@section('content')
<h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">Create an Account</h2>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div>
        <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Full Name</label>
        <input id="name" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
        @error('name')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="mt-4">
        <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email Address</label>
        <input id="email" type="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white" name="email" value="{{ old('email') }}" required autocomplete="email">
        @error('email')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="mt-4">
        <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Password</label>
        <input id="password" type="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white" name="password" required autocomplete="new-password">
        @error('password')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="mt-4">
        <label for="password_confirmation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Confirm Password</label>
        <input id="password_confirmation" type="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white" name="password_confirmation" required autocomplete="new-password">
    </div>

    <div class="flex items-center justify-end mt-4">
        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="{{ route('login') }}">
            Already registered?
        </a>

        <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            Register
        </button>
    </div>
</form>
@endsection
