@extends('layouts.app')

@section('title', __('access.roles'))

@section('content')
<div class="mx-auto max-w-4xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ __('access.roles') }}</h1>

    <table class="mt-6 w-full border-collapse text-sm">
        <thead>
            <tr class="border-b border-[var(--rule-strong)] bg-[var(--paper-sunk)] text-left">
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('access.roles') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('access.scope') }}</th>
                <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-[0.08em]">{{ __('access.permissions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2">
                        <a class="underline" href="{{ route('admin.roles.show', $role) }}">{{ $role->name }}</a>
                        <span class="font-[var(--font-mono)] text-xs text-[var(--ink-faint)]">{{ $role->slug }}</span>
                    </td>
                    <td class="px-3 py-2">
                        {{ $role->requires_organisational_unit ? __('access.scope') : __('access.university_wide') }}
                    </td>
                    <td class="px-3 py-2 text-right tabular">{{ $role->permissions->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
