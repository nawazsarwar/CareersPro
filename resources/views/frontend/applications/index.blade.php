@extends('layouts.app')

@section('title', __('application.applications'))

@section('content')
<div class="mx-auto max-w-4xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ __('application.applications') }}</h1>

    <x-status :status="session('status')" />

    <table class="mt-6 w-full border-collapse text-[13px]">
        <thead>
            <tr class="border-b border-[var(--rule-strong)] bg-[var(--paper-sunk)] text-left">
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('application.application_no') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('application.post') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('application.state') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($applications as $application)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2 font-[var(--font-mono)]">
                        <a class="underline" href="{{ route('frontend.applications.show', $application) }}">
                            {{ $application->application_no }}
                        </a>
                    </td>
                    <td class="px-3 py-2">{{ $application->post->title }}</td>
                    <td class="px-3 py-2">{{ $application->lifecycle_state->value }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-3 py-6 text-[var(--ink-faint)]">{{ __('application.none_yet') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $applications->links() }}</div>
</div>
@endsection
