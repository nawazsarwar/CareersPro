@extends('layouts.app')

@section('title', __('auth.two_factor'))

@section('content')
<div class="mx-auto max-w-2xl p-6">
    <h1 class="font-[var(--font-display)] text-3xl">{{ __('auth.two_factor') }}</h1>

    <x-status :status="session('status')" />

    @if ($enforced)
        <p class="mt-2 rounded border-l-2 border-[var(--brass)] bg-[var(--brass-wash)] px-3 py-2 text-sm">
            {{ __('auth.two_factor_enforced') }}
        </p>
    @endif

    @if (session('recovery_codes'))
        {{-- Shown once. A code the user can re-display is a code an attacker
             with a live session can re-display. --}}
        <section class="mt-6 rounded border border-[var(--rule-strong)] p-4">
            <h2 class="text-sm font-semibold uppercase tracking-[0.08em]">{{ __('auth.recovery_codes') }}</h2>
            <p class="mt-1 text-xs text-[var(--ink-faint)]">{{ __('auth.recovery_codes_hint') }}</p>
            <ul class="mt-3 grid grid-cols-2 gap-1 font-[var(--font-mono)] text-sm">
                @foreach (session('recovery_codes') as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
        </section>
    @endif

    @if (session('totp_svg'))
        <section class="mt-6 rounded border border-[var(--rule-strong)] p-4">
            <h2 class="text-sm font-semibold uppercase tracking-[0.08em]">{{ __('auth.scan_qr') }}</h2>
            <div class="mt-3">{!! session('totp_svg') !!}</div>
            <p class="mt-2 text-xs text-[var(--ink-faint)]">
                {{ __('auth.totp_manual') }}
                <span class="font-[var(--font-mono)]">{{ session('totp_secret') }}</span>
            </p>

            {{-- Enrolment completes only on a code the authenticator produced,
                 proving it holds the secret. Trusting the displayed QR alone
                 locks out every user whose scan silently failed. --}}
            <form method="POST" action="{{ route('frontend.two-factor.confirm', 'totp') }}" class="mt-4 space-y-3">
                @csrf
                <x-code-input :label="__('auth.enter_code_from_app')" />
                <button type="submit" class="rounded bg-[var(--green)] px-4 py-2 text-white">{{ __('auth.confirm') }}</button>
            </form>
        </section>
    @endif

    <table class="mt-6 w-full border-collapse text-sm">
        <thead>
            <tr class="border-b border-[var(--rule-strong)] bg-[var(--paper-sunk)] text-left">
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('auth.method') }}</th>
                <th class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.08em]">{{ __('auth.state') }}</th>
                <th class="px-3 py-2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($methods as $method)
                <tr class="border-b border-[var(--rule)]">
                    <td class="px-3 py-2">{{ $method->type->label() }}</td>
                    <td class="px-3 py-2">
                        {{-- Never colour alone: a glyph and a word (WCAG 1.4.1). --}}
                        {{ $method->isConfirmed() ? '✓ '.__('auth.confirmed') : '◦ '.__('auth.pending') }}
                    </td>
                    <td class="px-3 py-2 text-right">
                        <form method="POST" action="{{ route('frontend.two-factor.destroy', $method->type->value) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm underline"
                                @disabled($enforced && count($methods) === 1)>
                                {{ __('auth.remove') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-3 py-4 text-[var(--ink-faint)]">{{ __('auth.no_methods') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4 flex gap-2">
        @foreach ($available as $factor)
            <form method="POST" action="{{ route('frontend.two-factor.store', $factor->value) }}">
                @csrf
                <button type="submit" class="rounded border border-[var(--rule-strong)] px-3 py-2 text-sm">
                    {{ __('auth.add_factor', ['factor' => $factor->label()]) }}
                </button>
            </form>
        @endforeach
    </div>
</div>
@endsection
