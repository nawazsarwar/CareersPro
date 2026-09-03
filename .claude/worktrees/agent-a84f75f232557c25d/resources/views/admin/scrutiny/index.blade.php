@extends('layouts.app')

@section('title', __('scrutiny.queue'))

@section('content')
<div class="mx-auto max-w-6xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ __('scrutiny.queue') }}</h1>

    <x-status :status="session('status')" />

    {{-- A plain GET form. Filtering through script would put the queue out of
         reach on a degraded connection, which is exactly where an officer
         works. --}}
    <form method="GET" action="{{ route('admin.scrutiny.index') }}" class="mt-6 flex flex-wrap gap-3">
        <label class="sr-only" for="scrutiny">{{ __('scrutiny.gates') }}</label>
        <select id="scrutiny" name="scrutiny" class="rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
            <option value="">{{ __('scrutiny.filter_all') }}</option>
            <option value="pending" @selected(($filters['scrutiny'] ?? null) === 'pending')>{{ __('scrutiny.filter_pending') }}</option>
            <option value="eligible" @selected(($filters['scrutiny'] ?? null) === 'eligible')>{{ __('scrutiny.filter_eligible') }}</option>
            <option value="rejected" @selected(($filters['scrutiny'] ?? null) === 'rejected')>{{ __('scrutiny.filter_rejected') }}</option>
        </select>

        <button type="submit" class="rounded bg-[var(--green)] px-4 py-2 text-white">{{ __('recruitment.filter') }}</button>
    </form>

    <table class="mt-6 w-full border-collapse text-[13px]">
        <thead>
            <tr class="border-b border-[var(--rule-strong)] bg-[var(--paper-sunk)] text-left">
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('scrutiny.application') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('scrutiny.candidate') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('scrutiny.post') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('scrutiny.gates') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($applications as $application)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2 font-[var(--font-mono)]">
                        <a class="underline" href="{{ route('admin.scrutiny.show', $application) }}">
                            {{ $application->application_no }}
                        </a>
                    </td>
                    <td class="px-3 py-2">{{ $application->user->name }}</td>
                    <td class="px-3 py-2">{{ $application->post->title }}</td>
                    <td class="px-3 py-2">
                        @foreach ($application->eligibilityDecisions as $decision)
                            {{-- Three states, never two: a candidate nobody has
                                 examined must not look like one who was refused. --}}
                            <span class="mr-3">{{ \App\Enums\GateDecision::label($decision->decision) }}</span>
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-3 py-6 text-[var(--ink-faint)]">Nothing awaiting scrutiny.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $applications->links() }}</div>
</div>
@endsection
