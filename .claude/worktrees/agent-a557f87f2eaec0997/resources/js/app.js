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
Alpine.data('codeInput', (length, autofocus = false) => ({
    enhanced: false,
    length,
    autofocus,

    split() {
        this.enhanced = true;

        if (this.autofocus) {
            this.$nextTick(() => this.boxes()[0]?.focus());
        }
    },

    /**
     * Read from the container ref rather than by scanning the component.
     *
     * The boxes are produced by an x-for, so they are not the elements that
     * were parsed with the component: a query that misses them leaves the
     * single field empty, and an empty hidden `required` field is a submit the
     * browser refuses without a visible message.
     */
    boxes() {
        const container = this.$refs.boxes;

        return container ? Array.from(container.querySelectorAll('input')) : [];
    },

    sync() {
        const single = this.$refs.single;

        if (single) {
            single.value = this.boxes().map((box) => box.value).join('');
        }
    },

    /**
     * The index comes from the box's position among its siblings, not from the
     * loop variable, so one listener on the container serves every box.
     */
    advance(event) {
        const boxes = this.boxes();
        const index = boxes.indexOf(event.target);

        if (index === -1) {
            return;
        }

        event.target.value = event.target.value.replace(/\D/g, '').slice(0, 1);

        // Focus first: a failure to sync must not also cost the user the
        // auto-advance, which is the whole point of the enhancement.
        if (event.target.value !== '' && index < boxes.length - 1) {
            boxes[index + 1].focus();
            boxes[index + 1].select();
        }

        this.sync();
    },

    /**
     * Backspace on an empty box steps back and clears the box it lands on,
     * which is what a user correcting a digit expects. The arrows move without
     * changing anything.
     */
    navigate(event) {
        const boxes = this.boxes();
        const index = boxes.indexOf(event.target);

        if (index === -1) {
            return;
        }

        if (event.key === 'Backspace' && event.target.value === '' && index > 0) {
            event.preventDefault();
            boxes[index - 1].value = '';
            boxes[index - 1].focus();
            this.sync();

            return;
        }

        if (event.key === 'ArrowLeft' && index > 0) {
            event.preventDefault();
            boxes[index - 1].focus();
        }

        if (event.key === 'ArrowRight' && index < boxes.length - 1) {
            event.preventDefault();
            boxes[index + 1].focus();
        }
    },

    /**
     * A code arriving from the SMS is pasted whole, into whichever box happens
     * to have focus. Distributing it is the difference between one paste and
     * six retypes.
     */
    paste(event) {
        const boxes = this.boxes();

        if (boxes.length === 0) {
            return;
        }

        const digits = (event.clipboardData?.getData('text') || '').replace(/\D/g, '').slice(0, this.length);

        boxes.forEach((box, i) => {
            box.value = digits[i] ?? '';
        });

        this.sync();
        boxes[Math.min(digits.length, boxes.length - 1)].focus();
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
