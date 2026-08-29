@extends('layouts.app')

@section('title', $application->application_no)

@section('content')
<div class="mx-auto max-w-4xl p-6">
    <p class="font-[var(--font-mono)] text-xs text-[var(--ink-faint)]">{{ $application->application_no }}</p>
    <h1 class="mt-1 font-[var(--font-display)] text-3xl">{{ $application->user->name }}</h1>
    <p class="mt-1 text-sm text-[var(--ink-muted)]">{{ $application->post->title }}</p>

    <x-status :status="session('status')" />

    <form method="POST" action="{{ route('admin.scrutiny.open', $application) }}" class="mt-6">
        @csrf
        <button type="submit" class="rounded border border-[var(--rule-strong)] px-3 py-2 text-sm">
            {{ __('scrutiny.open') }}
        </button>
    </form>

    <h2 class="mt-8 text-sm font-semibold uppercase tracking-[0.08em]">{{ __('scrutiny.gates') }}</h2>

    {{-- Only the gates this post actually has. The legacy modal offered all
         three regardless, so an officer could record a written-test decision
         for a post with no written test. --}}
    @foreach ($application->eligibilityDecisions as $decision)
        <form method="POST" action="{{ route('admin.scrutiny.gates', $application) }}"
              class="mt-4 rounded border border-[var(--rule)] p-4"
              x-data="gateRow" @submit.prevent="submit($event)">
            @csrf
            <input type="hidden" name="gate" value="{{ $decision->gate->value }}">

            <p class="text-sm font-semibold">{{ $decision->gate->label() }}</p>
            <p class="mt-1 text-sm" x-text="label || @js(\App\Enums\GateDecision::label($decision->decision))"></p>

            <div class="mt-3 flex flex-wrap items-center gap-3">
                <label class="text-xs font-semibold uppercase tracking-[0.08em]" for="decision-{{ $decision->getKey() }}">
                    {{ __('scrutiny.decide') }}
                </label>
                <select id="decision-{{ $decision->getKey() }}" name="decision"
                        class="rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
                    <option value="">{{ __('application.decision_pending') }}</option>
                    <option value="eligible">{{ __('application.decision_eligible') }}</option>
                    <option value="rejected">{{ __('application.decision_rejected') }}</option>
                </select>

                <label class="sr-only" for="remark-{{ $decision->getKey() }}">{{ __('scrutiny.remark') }}</label>
                <input id="remark-{{ $decision->getKey() }}" name="remark" type="text"
                       value="{{ $decision->remark }}" placeholder="{{ __('scrutiny.remark') }}"
                       class="min-w-64 flex-1 rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">

                <button type="submit" class="rounded bg-[var(--green)] px-4 py-2 text-white" :disabled="busy">
                    {{ __('scrutiny.decide') }}
                </button>
            </div>
        </form>
    @endforeach

    <h2 class="mt-8 text-sm font-semibold uppercase tracking-[0.08em]">{{ __('scrutiny.raise_deficiency') }}</h2>
    <form method="POST" action="{{ route('admin.scrutiny.deficiencies.store', $application) }}" class="mt-2 space-y-3">
        @csrf
        <label class="block text-xs font-semibold uppercase tracking-[0.08em]" for="field_reference">
            {{ __('scrutiny.deficiency_field') }}
        </label>
        <input id="field_reference" name="field_reference" type="text"
               class="w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">

        <label class="block text-xs font-semibold uppercase tracking-[0.08em]" for="description">
            {{ __('scrutiny.deficiency_description') }}
        </label>
        <textarea id="description" name="description" rows="3" required
                  class="w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2"></textarea>

        <button type="submit" class="rounded bg-[var(--green)] px-4 py-2 text-white">
            {{ __('scrutiny.raise_deficiency') }}
        </button>
    </form>
</div>
@endsection
