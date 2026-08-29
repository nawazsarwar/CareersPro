@props(['name' => 'code', 'length' => 6, 'label' => null, 'autofocus' => false])

{{--
    Six boxes with JavaScript, one field without it (M03 §7, M03-R29).

    Alpine adds digit auto-advance and paste handling; with JavaScript off the
    same markup is a single numeric input that posts the same field name. The
    fallback is not a second form -- it is this form, unenhanced.

    Two details that are load-bearing rather than stylistic:

      - `:required` removes the constraint from the single field once the boxes
        take over. A `required` control that `x-show` has hidden cannot be
        focused, and the browser then refuses the submit outright -- "An invalid
        form control with name='code' is not focusable" -- with no message the
        user can see or act on.
      - the handlers are bound on the container, not inside the x-for. One
        listener sees every box, keeps working if a box is re-keyed, and takes
        its index from the box's position rather than from the loop variable.
--}}
<div x-data="codeInput({{ $length }}, {{ $autofocus ? 'true' : 'false' }})" x-init="split()">
    <label for="{{ $name }}" class="block text-xs font-semibold uppercase tracking-[0.08em]">
        {{ $label ?? __('auth.enter_code') }}
    </label>

    <input id="{{ $name }}" name="{{ $name }}" x-ref="single" x-show="!enhanced"
           :required="! enhanced"
           type="text" inputmode="numeric" autocomplete="one-time-code" required
           maxlength="{{ $length }}"
           class="mt-1 w-full rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] px-3 py-2 font-[var(--font-mono)] tracking-[0.4em]">

    <div x-show="enhanced" x-cloak x-ref="boxes"
         @input="advance($event)" @keydown="navigate($event)" @paste.prevent="paste($event)"
         class="mt-1 flex gap-2">
        <template x-for="i in {{ $length }}" :key="i">
            <input type="text" inputmode="numeric" maxlength="1"
                   :autocomplete="i === 1 ? 'one-time-code' : 'off'"
                   :aria-label="`{{ __('auth.digit') }} ${i}`"
                   class="h-11 w-10 rounded border border-[var(--rule-strong)] bg-[var(--paper-raised)] text-center font-[var(--font-mono)]">
        </template>
    </div>
</div>
