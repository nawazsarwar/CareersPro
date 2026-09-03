@extends('layouts.app')

@section('title', __('global.dashboard'))

@section('content')
    <div class="mx-auto max-w-4xl p-6">
        <h1 class="font-[var(--font-display)] text-3xl">{{ __('global.dashboard') }}</h1>
        <p class="mt-2 text-sm text-[var(--ink-muted)]">{{ __('global.wave_one_placeholder') }}</p>

        <form method="POST" action="{{ route('frontend.logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="rounded border border-[var(--rule-strong)] px-3 py-2 text-sm">{{ __('auth.sign_out') }}</button>
        </form>
    </div>
@endsection
