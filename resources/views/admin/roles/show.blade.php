@extends('layouts.app')

@section('title', $role->name)

@section('content')
<div class="mx-auto max-w-4xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ $role->name }}</h1>
    <p class="font-[var(--font-mono)] text-sm text-[var(--ink-faint)]">{{ $role->slug }}</p>

    <ul class="mt-6 grid grid-cols-2 gap-1 text-sm">
        @foreach ($role->permissions->sortBy('slug') as $permission)
            <li class="font-[var(--font-mono)]">{{ $permission->slug }}</li>
        @endforeach
    </ul>
</div>
@endsection
