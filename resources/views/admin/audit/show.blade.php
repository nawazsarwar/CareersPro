@extends('layouts.app')

@section('title', __('audit.entry', ['sequence' => $entry->sequence]))

@section('content')
<div class="mx-auto max-w-3xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ __('audit.entry', ['sequence' => $entry->sequence]) }}</h1>

    <dl class="mt-6 grid grid-cols-[10rem_1fr] gap-y-2 text-sm">
        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('audit.event') }}</dt>
        <dd class="font-[var(--font-mono)]">{{ $entry->event }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('audit.occurred_at') }}</dt>
        <dd class="tabular">{{ $entry->occurred_at->format('d-m-Y H:i:s.u') }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('audit.actor') }}</dt>
        <dd>{{ $entry->actor_id ?? __('audit.system') }} · {{ $entry->actor_ip }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('audit.actor_role') }}</dt>
        <dd class="font-[var(--font-mono)]">{{ $entry->actor_role }}</dd>
    </dl>

    <h2 class="mt-8 text-sm font-semibold uppercase tracking-[0.08em]">{{ __('audit.chain_position') }}</h2>
    <div class="mt-2 space-y-1 overflow-x-auto font-[var(--font-mono)] text-xs">
        <p>{{ __('audit.previous_hash') }}: {{ $entry->previous_hash }}</p>
        <p>{{ __('audit.hash') }}: {{ $entry->hash }}</p>
    </div>

    <h2 class="mt-8 text-sm font-semibold uppercase tracking-[0.08em]">{{ __('audit.properties') }}</h2>
    {{-- Redacted at write time: a secret that reaches this page is a secret
         that reached the table. --}}
    <pre class="mt-2 overflow-x-auto rounded bg-[var(--paper-sunk)] p-3 text-xs">{{ json_encode($entry->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
</div>
@endsection
