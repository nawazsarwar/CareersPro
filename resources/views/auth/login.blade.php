@extends('layouts.guest')

@section('content')
<div class="flex flex-col lg:flex-row min-h-screen w-full relative">

    <!-- Mobile Header -->
    <div class="lg:hidden w-full px-4 py-3.5 flex items-center justify-center z-20 shrink-0 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-[#0c120f]/80 backdrop-blur-md">
        <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">CareersPro</h1>
    </div>

    <!-- Login Container -->
    <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-0 lg:absolute lg:top-1/2 lg:left-1/3 lg:-translate-x-1/2 lg:-translate-y-1/2 z-30 w-full sm:w-[480px]">
        <div class="w-full bg-white dark:bg-[#151b18] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.2)] border border-gray-100 dark:border-gray-800 p-8 sm:p-10 relative overflow-hidden backdrop-blur-xl">

            <div class="mb-8">
                <h2 class="text-2xl sm:text-[1.75rem] font-bold text-gray-900 dark:text-white tracking-tight leading-tight">Welcome back</h2>
                <p class="text-[0.95rem] text-gray-500 dark:text-gray-400 mt-2 font-sans">Please enter your credentials to sign in.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-[0.72rem] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5 font-sans">
                        Email address
                    </label>
                    <div class="relative group">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700/50 rounded-xl text-gray-900 dark:text-gray-100 text-[0.95rem] placeholder-gray-400 dark:placeholder-gray-500 focus:bg-white dark:focus:bg-[#1a211e] focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200"
                            placeholder="name@university.ac.in">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between text-xs pt-1 mb-1.5">
                        <label for="password" class="block text-[0.72rem] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 font-sans">
                            Password
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors" tabindex="4">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative group" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700/50 rounded-xl text-gray-900 dark:text-gray-100 text-[0.95rem] placeholder-gray-400 dark:placeholder-gray-500 focus:bg-white dark:focus:bg-[#1a211e] focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-1">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-gray-600 dark:text-gray-400 select-none font-sans">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900">
                        <span class="text-[0.9rem]">Remember me for 30 days</span>
                    </label>
                </div>

                <div class="pt-3">
                    <button type="submit" class="relative w-full flex items-center justify-center py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium text-[0.95rem] shadow-sm shadow-indigo-600/20 transition-all duration-200 hover:shadow-md hover:shadow-indigo-600/30 active:scale-[0.98] group overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            Sign in
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </span>
                    </button>
                </div>

                <div class="text-center pt-2 pb-1">
                    <p class="text-[0.95rem] text-gray-600 dark:text-gray-400 font-sans">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-medium text-gray-900 dark:text-white hover:underline decoration-gray-300 dark:decoration-gray-600 underline-offset-4 transition-all">Create one now</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side Branding Pane (Desktop) -->
    <div class="hidden lg:block w-2/3 min-h-screen bg-indigo-900 shrink-0 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-900 via-indigo-800 to-indigo-900 opacity-90 z-10"></div>
        <div class="absolute inset-0 bg-[url('https://amuonline.ac.in/images/amu-victoria.jpg')] bg-cover bg-center mix-blend-overlay opacity-30 z-0"></div>

        <div class="absolute bottom-10 right-10 z-20 text-white/90">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-black/40 backdrop-blur-md border border-white/15 text-sm shadow-lg font-sans">
                CareersPro Application Engine
            </div>
        </div>
    </div>

</div>
@endsection
