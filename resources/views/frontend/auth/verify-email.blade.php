@extends('layouts.auth')

@section('title', __('auth.verify_email'))

@section('form')
    <h2 class="font-[var(--font-display)] text-2xl">{{ __('auth.verify_email') }}</h2>
    <p class="mt-2 text-sm text-[var(--ink-muted)]">{{ __('auth.verify_email_hint') }}</p>

    <x-status :status="session('status')" />

    <form method="POST" action="{{ route('frontend.verification.send') }}" class="mt-6">
        @csrf
        <button type="submit" class="w-full rounded bg-[var(--green)] px-4 py-2 text-white">{{ __('auth.resend_verification') }}</button>
    </form>

    <form method="POST" action="{{ route('frontend.logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="w-full rounded border border-[var(--rule-strong)] px-4 py-2 text-sm">{{ __('auth.sign_out') }}</button>
    </form>
@endsection
