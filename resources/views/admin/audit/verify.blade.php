@extends('layouts.app')

@section('title', __('audit.verification'))

@section('content')
<div class="mx-auto max-w-3xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ __('audit.verification') }}</h1>

    {{-- A broken chain is a P1 security incident, so it is stated as one
         rather than shown as a yellow warning (M26 §7). --}}
    <div role="status" class="mt-6 rounded border-l-4 px-4 py-3
        {{ $report->intact ? 'border-[var(--eligible)] bg-[var(--green-wash)]' : 'border-[var(--rejected)] bg-white' }}">
        <p class="font-semibold">
            {{ $report->intact ? '✓ '.__('audit.intact') : '✕ '.__('audit.broken') }}
        </p>
        <p class="mt-1 text-sm">{{ $report->summary() }}</p>

        @unless ($report->intact)
            <p class="mt-3 text-sm font-semibold">{{ __('audit.incident_notice') }}</p>
        @endunless
    </div>

    <p class="mt-4 text-sm text-[var(--ink-muted)]">
        {{ __('audit.verified_count', ['count' => number_format($report->verified)]) }}
    </p>
</div>
@endsection
