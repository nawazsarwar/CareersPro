@extends('layouts.app')

@section('title', $application->application_no)

@section('content')
<div class="mx-auto max-w-3xl p-6">
    <p class="font-[var(--font-mono)] text-xs text-[var(--ink-faint)]">{{ $application->application_no }}</p>
    <h1 class="mt-1 font-[var(--font-display)] text-3xl">{{ $application->post->title }}</h1>

    <x-status :status="session('status')" />

    <h2 class="mt-8 text-sm font-semibold uppercase tracking-[0.08em]">{{ __('application.gates') }}</h2>
    <table class="mt-2 w-full border-collapse text-sm">
        <tbody>
            @foreach ($application->eligibilityDecisions as $decision)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2">{{ $decision->gate->label() }}</td>
                    {{-- Three states, never two. A candidate nobody has yet
                         examined must not look identical to one who has been
                         refused. --}}
                    <td class="px-3 py-2">{{ \App\Enums\GateDecision::label($decision->decision) }}</td>
                    <td class="px-3 py-2 text-[var(--ink-muted)]">{{ $decision->remark }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($application->deficiencies->isNotEmpty())
        <h2 class="mt-8 text-sm font-semibold uppercase tracking-[0.08em]">{{ __('application.deficiencies') }}</h2>
        <ul class="mt-2 space-y-2 text-sm">
            @foreach ($application->deficiencies as $deficiency)
                <li class="border-l-2 border-[var(--brass)] pl-3">
                    <p>{{ $deficiency->description }}</p>
                    @if ($deficiency->isOpen() && $deficiency->rectification_window_closes_at)
                        <p class="text-xs text-[var(--ink-faint)]">
                            {{ __('application.rectify_by', ['date' => $deficiency->rectification_window_closes_at->format('d-m-Y H:i')]) }}
                        </p>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
