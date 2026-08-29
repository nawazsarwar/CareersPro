@extends('layouts.app')

@section('title', __('establishment.establishment'))

@section('content')
<div class="mx-auto max-w-5xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ __('establishment.establishment') }}</h1>
    <p class="mt-1 text-sm text-[var(--ink-muted)]">CRR Rules 8 and 9.1</p>

    <x-status :status="session('status')" />

    <table class="mt-6 w-full border-collapse text-[13px]">
        <thead>
            <tr class="border-b border-[var(--rule-strong)] bg-[var(--paper-sunk)] text-left">
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('establishment.unit') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">Designation</th>
                <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-[0.08em]">{{ __('establishment.sanctioned') }}</th>
                <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-[0.08em]">{{ __('establishment.filled') }}</th>
                <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-[0.08em]">{{ __('establishment.vacant') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('establishment.order_ref') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2">{{ $row->unit }}</td>
                    <td class="px-3 py-2">{{ $row->designation }}</td>
                    <td class="px-3 py-2 text-right tabular">{{ $row->sanctioned_count }}</td>
                    <td class="px-3 py-2 text-right tabular">{{ $row->filled_count }}</td>
                    <td class="px-3 py-2 text-right tabular">{{ $row->sanctioned_count - $row->filled_count }}</td>
                    <td class="px-3 py-2 font-[var(--font-mono)] text-xs">{{ $row->sanction_order_ref }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-3 py-4 text-[var(--ink-faint)]">No sanctioned strength recorded yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $rows->links() }}</div>
</div>
@endsection
