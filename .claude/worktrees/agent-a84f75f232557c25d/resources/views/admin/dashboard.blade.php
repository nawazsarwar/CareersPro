@extends('layouts.app')

@section('title', __('global.admin_dashboard'))

@section('content')
    <div class="mx-auto max-w-4xl p-6">
        <h1 class="font-[var(--font-display)] text-3xl">{{ __('global.admin_dashboard') }}</h1>
        <p class="mt-2 text-sm text-[var(--ink-muted)]">{{ __('global.wave_one_placeholder') }}</p>
    </div>
@endsection
