@extends('layouts.app')

@section('content')
    {{-- The split pane from screens.md §1: the University's mark on one side,
         the form on the other. It collapses to a single column below `md`. --}}
    <div class="grid min-h-screen md:grid-cols-2">
        <aside class="hidden flex-col justify-between bg-[var(--green)] p-10 text-white md:flex">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.08em] opacity-80">
                    {{ __('global.university') }}
                </p>
                <h1 class="mt-2 font-[var(--font-display)] text-3xl leading-tight">
                    {{ __('global.recruitment_portal') }}
                </h1>
            </div>
            <p class="text-sm opacity-80">{{ __('global.office_of_coe') }}</p>
        </aside>

        <div class="flex items-center justify-center p-6">
            <div class="w-full max-w-sm">
                @yield('form')
            </div>
        </div>
    </div>
@endsection
