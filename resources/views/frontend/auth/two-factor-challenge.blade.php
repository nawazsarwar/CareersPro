@extends('layouts.auth')

@section('title', __('auth.second_factor'))

@section('form')
    <h2 class="font-[var(--font-display)] text-2xl">{{ __('auth.second_factor') }}</h2>

    <p class="mt-2 text-sm text-[var(--ink-muted)]">
        @if ($factor === \App\Enums\AuthFactor::Totp)
            {{ __('auth.second_factor_totp') }}
        @else
            {{ __('auth.second_factor_sent', ['destination' => $destination ?? '']) }}
        @endif
    </p>

    <x-status :status="session('status')" />

    <form method="POST" action="{{ route('frontend.two-factor.challenge') }}" class="mt-6 space-y-4">
        @csrf
        <x-code-input :autofocus="true" />
        <button type="submit" class="w-full rounded bg-[var(--green)] px-4 py-2 text-white">
            {{ __('auth.verify') }}
        </button>
    </form>

    @if ($factor !== \App\Enums\AuthFactor::Totp)
        <form method="POST" action="{{ route('frontend.two-factor.challenge.resend') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full rounded border border-[var(--rule-strong)] px-4 py-2 text-sm">
                {{ __('auth.resend_code') }}
            </button>
        </form>
    @endif

    <p class="mt-6 text-xs text-[var(--ink-faint)]">{{ __('auth.recovery_code_hint') }}</p>
@endsection
