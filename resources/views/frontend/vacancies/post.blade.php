@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="mx-auto max-w-3xl p-6">
    <p class="text-xs font-semibold uppercase tracking-[0.08em] text-[var(--ink-muted)]">
        {{ $post->advertisement->advertisement_no }}
    </p>
    <h1 class="mt-1 font-[var(--font-display)] text-3xl">{{ $post->title }}</h1>

    <dl class="mt-6 grid grid-cols-[12rem_1fr] gap-y-2 text-sm">
        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.unit') }}</dt>
        <dd>{{ $post->ou_title_snapshot ?? '—' }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.vacancies_count') }}</dt>
        <dd class="tabular">{{ $post->vacancies }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.pay_level') }}</dt>
        <dd class="font-[var(--font-mono)]">{{ $post->pay_level ?? '—' }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.nature') }}</dt>
        <dd>
            {{ $post->appointment_nature->label() }}
            @if ($post->tenure_months)
                <span class="text-[var(--ink-muted)]">({{ __('recruitment.tenure_months', ['months' => $post->tenure_months]) }})</span>
            @endif
        </dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.age_limit') }}</dt>
        <dd class="tabular">{{ $post->age_limit ?? '—' }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.closing_date') }}</dt>
        <dd class="tabular">{{ $post->closing_date?->format('d-m-Y') ?? '—' }}</dd>
    </dl>

    {{-- The venue differs by post type. A candidate who posts a dossier to the
         wrong section has effectively not applied, so it is rendered from the
         post type rather than typed into a description. --}}
    @if ($instructions['venue'])
        <section class="mt-8 rounded border-l-2 border-[var(--rule-strong)] bg-[var(--paper-sunk)] p-4">
            <h2 class="text-sm font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.hardcopy') }}</h2>
            <p class="mt-2 text-sm">{{ $instructions['venue'] }}</p>
        </section>
    @endif

    @if ($post->advertisement->corrigenda->isNotEmpty())
        <section class="mt-8">
            <h2 class="text-sm font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.corrigenda') }}</h2>
            <ul class="mt-2 space-y-2 text-sm">
                @foreach ($post->advertisement->corrigenda->sortBy('corrigendum_no') as $corrigendum)
                    <li class="border-l-2 border-[var(--brass)] pl-3">
                        <span class="font-semibold">{{ __('recruitment.corrigendum_no', ['no' => $corrigendum->corrigendum_no]) }}</span>
                        <span class="text-[var(--ink-faint)] tabular">{{ $corrigendum->issued_on->format('d-m-Y') }}</span>
                        <p>{{ $corrigendum->description }}</p>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    <p class="mt-8">
        @if ($post->isOpen())
            <a class="rounded bg-[var(--green)] px-4 py-2 text-white" href="{{ route('frontend.login') }}">
                {{ __('recruitment.apply') }}
            </a>
        @else
            <span class="text-[var(--ink-muted)]">◦ {{ __('recruitment.closed_notice') }}</span>
        @endif
    </p>
</div>
@endsection
