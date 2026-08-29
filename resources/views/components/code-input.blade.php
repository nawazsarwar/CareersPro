@props(['name' => 'code', 'length' => 6, 'label' => null])

{{--
    Six boxes with JavaScript, one field without it (M03 §7, M03-R29).

    Alpine adds digit auto-advance and paste handling; with JavaScript off the
    same markup is a single numeric input that posts the same field name. The
    fallback is not a second form -- it is this form, unenhanced.
--}}
<div x-data="codeInput({{ $length }})" x-init="split()">
    <label for="{{ $name }}" class="block text-xs font-semibold uppercase tracking-[0.08em]">
        {{ $label ?? __('auth.enter_code') }}
    </label>

    <input id="{{ $name }}" name="{{ $name }}" x-ref="single" x-show="!enhanced"
           type="text" inputmode="numeric" autocomplete="one-time-code" required
           maxlength="{{ $length }}"
           class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2 font-[var(--font-mono)] tracking-[0.4em]">

    <div x-show="enhanced" x-cloak class="mt-1 flex gap-2">
        <template x-for="i in {{ $length }}" :key="i">
            <input type="text" inputmode="numeric" maxlength="1"
                   :aria-label="`{{ __('auth.digit') }} ${i}`"
                   @input="advance($event, i - 1)" @keydown.backspace="retreat($event, i - 1)"
                   @paste.prevent="paste($event)"
                   class="h-11 w-10 rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] text-center font-[var(--font-mono)]">
        </template>
    </div>
</div>
