@extends('layouts.app')

@section('title', __('audit.title'))

@section('content')
<div class="mx-auto max-w-6xl p-6">
    <div class="flex items-center justify-between">
        <h1 class="font-[var(--font-display)] text-3xl">{{ __('audit.title') }}</h1>

        <form method="POST" action="{{ route('admin.audit.verify') }}">
            @csrf
            <button type="submit" class="rounded border border-[var(--rule-strong)] px-3 py-2 text-sm">
                {{ __('audit.verify_chain') }}
            </button>
        </form>
    </div>

    <table class="mt-6 w-full border-collapse text-[13px]">
        <thead>
            <tr class="border-b border-[var(--rule-strong)] bg-[var(--paper-sunk)] text-left">
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('audit.sequence') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('audit.occurred_at') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('audit.event') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('audit.subject') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('audit.actor') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('audit.actor_role') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entries as $entry)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2 font-[var(--font-mono)] tabular">
                        <a class="underline" href="{{ route('admin.audit.show', $entry) }}">{{ $entry->sequence }}</a>
                    </td>
                    <td class="px-3 py-2 tabular">{{ $entry->occurred_at->format('d-m-Y H:i:s') }}</td>
                    <td class="px-3 py-2 font-[var(--font-mono)]">{{ $entry->event }}</td>
                    <td class="px-3 py-2">{{ $entry->subject_type }} {{ $entry->subject_id }}</td>
                    <td class="px-3 py-2 tabular">
                        {{ $entry->actor_id ?? __('audit.system') }}
                        @if ($entry->impersonator_id)
                            <span class="text-[var(--brass)]">{{ __('audit.via', ['actor' => $entry->impersonator_id]) }}</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 font-[var(--font-mono)] text-xs">{{ $entry->actor_role }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $entries->links() }}</div>
</div>
@endsection
