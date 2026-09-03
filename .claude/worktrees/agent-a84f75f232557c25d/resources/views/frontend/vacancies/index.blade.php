@extends('layouts.app')

@section('title', __('recruitment.vacancies'))

@section('content')
<div class="mx-auto max-w-5xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ __('recruitment.vacancies') }}</h1>

    {{-- A plain GET form. Filtering a public list through script would hide it
         from search engines and from anybody on a degraded connection. --}}
    <form method="GET" action="{{ route('frontend.vacancies.index') }}" class="mt-6 flex flex-wrap gap-3">
        <label class="sr-only" for="q">{{ __('recruitment.search') }}</label>
        <input id="q" name="q" type="search" value="{{ request('q') }}"
               placeholder="{{ __('recruitment.search') }}"
               class="min-w-56 flex-1 rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">

        <label class="sr-only" for="nature">{{ __('recruitment.nature') }}</label>
        <select id="nature" name="nature" class="rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
            <option value="">{{ __('recruitment.any_nature') }}</option>
            @foreach (\App\Enums\AppointmentNature::cases() as $nature)
                <option value="{{ $nature->value }}" @selected(request('nature') === $nature->value)>
                    {{ $nature->label() }}
                </option>
            @endforeach
        </select>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="open_only" value="1" @checked(request()->boolean('open_only'))>
            {{ __('recruitment.open_only') }}
        </label>

        <button type="submit" class="rounded bg-[var(--green)] px-4 py-2 text-white">{{ __('recruitment.filter') }}</button>
    </form>

    <table class="mt-6 w-full border-collapse text-[13px]">
        <thead>
            <tr class="border-b border-[var(--rule-strong)] bg-[var(--paper-sunk)] text-left">
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.post') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.unit') }}</th>
                <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.vacancies_count') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.closing_date') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.state') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $post)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2">
                        <a class="underline" href="{{ route('frontend.vacancies.show', $post->slug) }}">{{ $post->title }}</a>
                        <span class="block text-xs text-[var(--ink-faint)]">{{ $post->advertisement->advertisement_no }}</span>
                    </td>
                    <td class="px-3 py-2">{{ $post->ou_title_snapshot ?? '—' }}</td>
                    <td class="px-3 py-2 text-right tabular">{{ $post->vacancies }}</td>
                    <td class="px-3 py-2 tabular">{{ $post->closing_date?->format('d-m-Y') ?? '—' }}</td>
                    <td class="px-3 py-2">
                        {{-- A glyph and a word, never colour alone (WCAG 1.4.1). --}}
                        {{ $post->isOpen() ? '✓ '.__('recruitment.open') : '◦ '.__('recruitment.closed') }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-3 py-6 text-[var(--ink-faint)]">{{ __('recruitment.none_found') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $posts->links() }}</div>
</div>
@endsection
