@extends('layouts.app')

@section('title', __('recruitment.advertisements'))

@section('content')
<div class="mx-auto max-w-5xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ __('recruitment.advertisements') }}</h1>

    <x-status :status="session('status')" />

    <table class="mt-6 w-full border-collapse text-[13px]">
        <thead>
            <tr class="border-b border-[var(--rule-strong)] bg-[var(--paper-sunk)] text-left">
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">No.</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">Title</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.nature') }}</th>
                <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.posts') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('recruitment.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($advertisements as $advertisement)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2 font-[var(--font-mono)]">
                        <a class="underline" href="{{ route('admin.advertisements.show', $advertisement) }}">
                            {{ $advertisement->advertisement_no }}
                        </a>
                    </td>
                    <td class="px-3 py-2">{{ $advertisement->title }}</td>
                    <td class="px-3 py-2">{{ $advertisement->appointment_nature->label() }}</td>
                    <td class="px-3 py-2 text-right tabular">{{ $advertisement->posts_count }}</td>
                    <td class="px-3 py-2">{{ $advertisement->status->value }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-3 py-6 text-[var(--ink-faint)]">No advertisements yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $advertisements->links() }}</div>
</div>
@endsection
