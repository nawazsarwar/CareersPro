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

window.Alpine = Alpine;

Alpine.start();
