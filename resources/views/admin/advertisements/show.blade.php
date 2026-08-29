@extends('layouts.app')

@section('title', $advertisement->advertisement_no)

@section('content')
<div class="mx-auto max-w-4xl p-6">
    <p class="font-[var(--font-mono)] text-xs text-[var(--ink-faint)]">{{ $advertisement->advertisement_no }}</p>
    <h1 class="mt-1 font-[var(--font-display)] text-3xl">{{ $advertisement->title }}</h1>

    <x-status :status="session('status')" />

    <dl class="mt-6 grid grid-cols-[12rem_1fr] gap-y-2 text-sm">
        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.status') }}</dt>
        <dd>{{ $advertisement->status->value }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.nature') }}</dt>
        <dd>{{ $advertisement->appointment_nature->label() }}</dd>

        <dt class="text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.closing_date') }}</dt>
        <dd class="tabular">{{ $advertisement->default_closing_date?->format('d-m-Y') ?? '—' }}</dd>
    </dl>

    @unless ($advertisement->isPublished())
        <form method="POST" action="{{ route('admin.advertisements.publish', $advertisement) }}" class="mt-6">
            @csrf
            <button type="submit" class="rounded bg-[var(--green)] px-4 py-2 text-white">{{ __('recruitment.publish') }}</button>
        </form>
    @else
        {{-- A published advertisement cannot be edited. The change is a
             corrigendum: published, dated and auditable. --}}
        <form method="POST" action="{{ route('admin.advertisements.corrigenda', $advertisement) }}" class="mt-8 space-y-3">
            @csrf
            <h2 class="text-sm font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.issue_corrigendum') }}</h2>

            <label class="block text-xs font-semibold uppercase tracking-[0.08em]" for="description">
                {{ __('recruitment.description') }}
            </label>
            <textarea id="description" name="description" rows="3" required
                      class="w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2"></textarea>

            <label class="block text-xs font-semibold uppercase tracking-[0.08em]" for="default_closing_date">
                {{ __('recruitment.closing_date') }}
            </label>
            <input id="default_closing_date" name="default_closing_date" type="date"
                   class="rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">

            <button type="submit" class="rounded bg-[var(--green)] px-4 py-2 text-white">{{ __('recruitment.issue_corrigendum') }}</button>
        </form>
    @endunless

    <h2 class="mt-8 text-sm font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.posts') }}</h2>
    <table class="mt-2 w-full border-collapse text-[13px]">
        <tbody>
            @foreach ($advertisement->posts as $post)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2">{{ $post->title }}</td>
                    <td class="px-3 py-2 text-right tabular">{{ $post->vacancies }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
