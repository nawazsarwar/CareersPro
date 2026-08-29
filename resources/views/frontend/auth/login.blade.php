@extends('layouts.auth')

@section('title', __('auth.sign_in'))

@section('form')
    <h2 class="font-[var(--font-display)] text-2xl">{{ __('auth.sign_in') }}</h2>
    <p class="mt-1 text-sm text-[var(--ink-muted)]">{{ __('auth.sign_in_hint') }}</p>

    <x-status :status="session('status')" />

    <form method="POST" action="{{ route('frontend.login') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="login" class="block text-xs font-semibold uppercase tracking-[0.08em]">
                {{ __('auth.login_label') }}
            </label>
            {{-- One field. The credential resolver decides whether it is
                 matched against email or employee ID (DR-008). --}}
            <input id="login" name="login" type="text" required autofocus autocomplete="username"
                   value="{{ old('login') }}"
                   class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
            <p class="mt-1 text-xs text-[var(--ink-faint)]">{{ __('auth.login_example') }}</p>
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-[0.08em]">
                {{ __('auth.password') }}
            </label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" value="1"> {{ __('auth.remember_me') }}
        </label>

        <button type="submit" class="w-full rounded bg-[var(--green)] px-4 py-2 text-white hover:bg-[var(--green-lift)]">
            {{ __('auth.sign_in') }}
        </button>

        {{-- A secondary submit on the same card, not a tab and not a toggle:
             the password field stays the default and the code path is one
             click away (M03 §7).

             It is a second submit button on the SAME form, using formaction,
             rather than a second form fed by JavaScript. That way the
             identifier the user already typed is posted with it whether or not
             JavaScript is running -- which is the whole of M03-R29. --}}
        <button type="submit" formaction="{{ route('frontend.login.otp.request') }}" formnovalidate
                class="w-full rounded border border-[var(--rule-strong)] px-4 py-2 text-sm">
            {{ __('auth.send_code_instead') }}
        </button>
    </form>

    <div class="mt-6 flex justify-between text-sm">
        <a class="underline" href="{{ route('frontend.password.request') }}">{{ __('auth.forgot_password') }}</a>
        <a class="underline" href="{{ route('frontend.register') }}">{{ __('auth.create_account') }}</a>
    </div>
@endsection
