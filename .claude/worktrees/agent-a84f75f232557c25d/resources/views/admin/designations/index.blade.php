@extends('layouts.app')

@section('title', __('establishment.designations'))

@section('content')
<div class="mx-auto max-w-5xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ __('establishment.designations') }}</h1>

    <x-status :status="session('status')" />

    <table class="mt-6 w-full border-collapse text-[13px]">
        <thead>
            <tr class="border-b border-[var(--rule-strong)] bg-[var(--paper-sunk)] text-left">
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">Code</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">Name</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('establishment.cadre') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('establishment.group') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('establishment.pay_level') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($designations as $designation)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2 font-[var(--font-mono)]">
                        <a class="underline" href="{{ route('admin.designations.show', $designation) }}">{{ $designation->code }}</a>
                    </td>
                    <td class="px-3 py-2">{{ $designation->name }}</td>
                    <td class="px-3 py-2">{{ $designation->cadre->label() }}</td>
                    <td class="px-3 py-2">{{ $designation->group ?? '—' }}</td>
                    <td class="px-3 py-2 font-[var(--font-mono)]">{{ $designation->pay_level }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $designations->links() }}</div>
</div>
@endsection
