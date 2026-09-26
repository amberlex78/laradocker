import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('formValidation', () => ({
    submit() {
        const fields = [...this.$el.querySelectorAll('[data-validation-field="true"]')];
        let firstInvalidField = null;

        fields.forEach((field) => this.setFieldError(field, ''));

        for (const field of fields) {
            const message = this.messageFor(field);

            if (!message) {
                continue;
            }

            this.setFieldError(field, message);
            firstInvalidField ??= field;
        }

        if (firstInvalidField) {
            firstInvalidField.focus();

            return;
        }

        HTMLFormElement.prototype.submit.call(this.$el);
    },

    setFieldError(field, message) {
        const error = document.getElementById(`${field.id}-error`);

        field.dataset.invalid = message ? 'true' : 'false';
        field.setAttribute('aria-invalid', message ? 'true' : 'false');

        if (message) {
            field.setAttribute('aria-describedby', `${field.id}-error`);
        } else {
            field.removeAttribute('aria-describedby');
        }

        if (error) {
            error.hidden = !message;
            error.textContent = message;
        }
    },

    messageFor(field) {
        const validity = field.validity;
        const isEmpty = field.type === 'password'
            ? field.value === ''
            : field.value.trim() === '';

        if (field.required && isEmpty) {
            return 'This field is required.';
        }

        if (validity.typeMismatch) {
            return 'Please enter a valid email address.';
        }

        if (validity.tooShort) {
            return `Please enter at least ${field.minLength} characters.`;
        }

        if (validity.tooLong) {
            return `Please enter no more than ${field.maxLength} characters.`;
        }

        if (validity.patternMismatch) {
            return 'Please use the requested format.';
        }

        if (validity.badInput) {
            return 'Please enter a valid value.';
        }

        const confirmationTarget = field.dataset.validationConfirm;

        if (confirmationTarget) {
            const password = this.$el.elements.namedItem(confirmationTarget);

            if (password && field.value !== password.value) {
                return 'Passwords do not match.';
            }
        }

        return '';
    },
}));

Alpine.start();
