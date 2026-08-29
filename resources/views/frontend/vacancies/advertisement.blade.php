@extends('layouts.app')

@section('title', $advertisement->title)

@section('content')
<div class="mx-auto max-w-4xl p-6">
    <p class="text-xs font-semibold uppercase tracking-[0.08em] text-[var(--ink-muted)]">
        {{ $advertisement->advertisement_no }}
    </p>
    <h1 class="mt-1 font-[var(--font-display)] text-3xl">{{ $advertisement->title }}</h1>

    @if ($advertisement->description)
        <p class="mt-4 text-sm leading-relaxed">{{ $advertisement->description }}</p>
    @endif

    <table class="mt-8 w-full border-collapse text-[13px]">
        <thead>
            <tr class="border-b border-[var(--rule-strong)] bg-[var(--paper-sunk)] text-left">
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.post') }}</th>
                <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.vacancies_count') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.closing_date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($advertisement->posts as $post)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2">
                        <a class="underline" href="{{ route('frontend.vacancies.show', $post->slug) }}">{{ $post->title }}</a>
                    </td>
                    <td class="px-3 py-2 text-right tabular">{{ $post->vacancies }}</td>
                    <td class="px-3 py-2 tabular">{{ $post->closing_date?->format('d-m-Y') ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
