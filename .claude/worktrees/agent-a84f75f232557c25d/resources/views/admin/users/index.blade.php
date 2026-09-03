@extends('layouts.app')

@section('title', __('access.users'))

@section('content')
<div class="mx-auto max-w-5xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ __('access.users') }}</h1>

    <x-status :status="session('status')" />

    <table class="mt-6 w-full border-collapse text-sm">
        <thead>
            <tr class="border-b border-[var(--rule-strong)] bg-[var(--paper-sunk)] text-left">
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">Name</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('access.roles') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2">
                        {{ $user->name }}
                        <span class="font-[var(--font-mono)] text-xs text-[var(--ink-faint)]">{{ $user->username ?? $user->email }}</span>
                    </td>
                    <td class="px-3 py-2">
                        @forelse ($user->roles as $role)
                            <span class="mr-2">{{ $role->name }}</span>
                        @empty
                            <span class="text-[var(--ink-faint)]">—</span>
                        @endforelse
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection
