import './bootstrap';

import Alpine from 'alpinejs';

/**
 * The six-box code input (M03 §7).
 *
 * Progressive enhancement, not a replacement: the markup ships as one numeric
 * field and this splits it into boxes only once Alpine is running. With
 * JavaScript disabled the single field posts the same name, so the form works
 * unchanged (M03-R29).
 */
Alpine.data('codeInput', (length) => ({
    enhanced: false,
    length,

    split() {
        this.enhanced = true;
    },

    boxes() {
        return Array.from(this.$el.querySelectorAll('input[maxlength="1"]'));
    },

    sync() {
        this.$refs.single.value = this.boxes().map((box) => box.value).join('');
    },

    advance(event, index) {
        event.target.value = event.target.value.replace(/\D/g, '').slice(0, 1);
        this.sync();

        if (event.target.value && index < this.length - 1) {
            this.boxes()[index + 1].focus();
        }
    },

    retreat(event, index) {
        if (event.target.value === '' && index > 0) {
            this.boxes()[index - 1].focus();
        }

        this.sync();
    },

    /**
     * A code arriving from the SMS is pasted whole, into whichever box happens
     * to have focus. Distributing it is the difference between one paste and
     * six retypes.
     */
    paste(event) {
        const digits = (event.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, this.length);

        this.boxes().forEach((box, i) => {
            box.value = digits[i] ?? '';
        });

        this.sync();
        this.boxes()[Math.min(digits.length, this.length - 1)].focus();
    },
}));

/**
 * The scrutiny gate row (DR-021 §10.1).
 *
 * The only permitted interaction pattern: a JSON endpoint plus a plain form
 * fallback on the same route. This intercepts the submit and updates the row
 * in place; with JavaScript off the same form posts and redirects back, and
 * the officer loses nothing but the absence of a page reload.
 *
 * We pay two extra methods here rather than adopt a second framework for
 * three screens. That cost is deliberate.
 */
Alpine.data('gateRow', () => ({
    busy: false,
    label: '',

    async submit(event) {
        this.busy = true;

        const form = event.target;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
            });

            const payload = await response.json();

            // A refusal is shown, not swallowed. A gate that silently fails to
            // save is worse than one that cannot be reached at all.
            this.label = response.ok ? payload.label : payload.message;
        } catch {
            // The network failed, so fall back to the plain path rather than
            // leaving the officer looking at a button that did nothing.
            form.submit();

            return;
        }

        this.busy = false;
    },
}));

window.Alpine = Alpine;

Alpine.start();
