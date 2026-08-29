@extends('layouts.auth')

@section('title', __('auth.enter_code'))

@section('form')
    <h2 class="font-[var(--font-display)] text-2xl">{{ __('auth.enter_code') }}</h2>

    {{-- The reason is stated here, on the pending screen, and never in
         response to the first submit -- which would report whether an account
         exists (M03 §3, M03-R14). --}}
    @php($message = match ($reason) {
        \App\Domain\Identity\OtpIssueResult::NO_MOBILE => __('auth.otp_no_mobile'),
        \App\Domain\Identity\OtpIssueResult::UNVERIFIED_MOBILE => __('auth.otp_unverified_mobile'),
        \App\Domain\Identity\OtpIssueResult::COOLDOWN => __('auth.otp_cooldown', ['time' => $retryAt]),
        \App\Domain\Identity\OtpIssueResult::HOURLY_CAP => __('auth.otp_hourly_cap', ['time' => $retryAt]),
        \App\Domain\Identity\OtpIssueResult::GATEWAY_FAILED => __('auth.otp_gateway_failed'),
        default => __('auth.otp_sent', ['destination' => $destination ?? '']),
    })

    <p class="mt-2 text-sm text-[var(--ink-muted)]">{{ $message }}</p>

    <x-status :status="session('status')" />

    @if ($reason === \App\Domain\Identity\OtpIssueResult::SENT && $user)
        <form method="POST" action="{{ route('frontend.login.otp.verify') }}" class="mt-6 space-y-4">
            @csrf
            <x-code-input :autofocus="true" />
            <button type="submit" class="w-full rounded bg-[var(--green)] px-4 py-2 text-white">
                {{ __('auth.verify') }}
            </button>
        </form>

        <form method="POST" action="{{ route('frontend.login.otp.resend') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full rounded border border-[var(--rule-strong)] px-4 py-2 text-sm">
                {{ __('auth.resend_code') }}
            </button>
        </form>
    @endif

    <p class="mt-6 text-sm">
        <a class="underline" href="{{ route('frontend.login') }}">{{ __('auth.use_password_instead') }}</a>
    </p>
@endsection
