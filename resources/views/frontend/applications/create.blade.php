@extends('layouts.app')

@section('title', __('application.apply'))

@section('content')
<div class="mx-auto max-w-2xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ $post->title }}</h1>
    <p class="mt-1 text-sm text-[var(--ink-muted)]">{{ $post->advertisement->advertisement_no }}</p>

    <x-status :status="session('status')" />

    @if ($missing)
        <section class="mt-6 rounded border-l-2 border-[var(--rejected)] bg-[var(--paper-raised)] p-4">
            <h2 class="text-sm font-semibold">{{ __('application.missing_profile') }}</h2>
            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                @foreach ($missing as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </section>
    @endif

    {{-- Advisory, not blocking. A candidate who believes a check is wrong may
         still apply and have it examined at scrutiny; what must never happen
         is taking a fee from somebody the system already knows is ineligible
         without telling them first. --}}
    @if ($preflight['warnings'])
        <section class="mt-6 rounded border-l-2 border-[var(--brass)] bg-[var(--brass-wash)] p-4">
            <h2 class="text-sm font-semibold">{{ __('application.preflight_heading') }}</h2>
            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                @foreach ($preflight['warnings'] as $warning)
                    <li>{{ $warning }}</li>
                @endforeach
            </ul>
        </section>
    @endif

    @if (! $missing)
        <form method="POST" action="{{ route('frontend.applications.store', $post->slug) }}" class="mt-8 space-y-4">
            @csrf

            <div>
                <label for="applied_under_category" class="block text-xs font-semibold uppercase tracking-[0.08em]">
                    {{ __('application.category') }}
                </label>
                <select id="applied_under_category" name="applied_under_category"
                        class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2">
                    <option value="">{{ __('application.category_unreserved') }}</option>
                    @foreach (\App\Models\Category::query()->orderBy('id')->get() as $category)
                        <option value="{{ $category->code }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <label class="flex items-start gap-2 text-sm">
                <input type="checkbox" name="confirm" value="1" class="mt-1">
                <span>{{ __('application.submit_warning') }}</span>
            </label>

            <button type="submit" class="rounded bg-[var(--green)] px-4 py-2 text-white">
                {{ __('application.confirm_submit') }}
            </button>
        </form>
    @endif
</div>
@endsection
