@extends('layouts.app')

@section('title', $designation->name)

@section('content')
<div class="mx-auto max-w-3xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ $designation->name }}</h1>
    <p class="font-[var(--font-mono)] text-sm text-[var(--ink-faint)]">{{ $designation->code }}</p>

    <x-status :status="session('status')" />

    <dl class="mt-6 grid grid-cols-[12rem_1fr] gap-y-2 text-sm">
        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('establishment.cadre') }}</dt>
        <dd>{{ $designation->cadre->label() }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('establishment.group') }}</dt>
        <dd>{{ $designation->group ?? '—' }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('establishment.pay_level') }}</dt>
        <dd class="font-[var(--font-mono)]">{{ $designation->pay_level }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">Selection</dt>
        <dd>{{ $designation->selection_method->label() }}</dd>
    </dl>

    @if ($designation->essential_qualification)
        <h2 class="mt-8 text-sm font-semibold uppercase tracking-[0.08em]">Essential qualification</h2>
        <pre class="mt-2 overflow-x-auto rounded bg-[var(--paper-sunk)] p-3 text-xs">{{ json_encode($designation->essential_qualification, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
    @endif
</div>
@endsection
