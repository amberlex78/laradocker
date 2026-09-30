import Alpine from 'alpinejs';
import { initFlowbite } from 'flowbite';

window.Alpine = Alpine;

const savedTheme = localStorage.getItem('color-theme') ?? localStorage.getItem('theme');
const defaultTheme = document.documentElement.dataset.defaultTheme ?? 'light';
const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
const initialTheme = savedTheme ?? (defaultTheme === 'dark' || systemPrefersDark ? 'dark' : 'light');

Alpine.store('theme', {
    dark: initialTheme === 'dark',

    toggle() {
        this.dark = ! this.dark;
        this.apply();
    },

    apply() {
        document.documentElement.classList.toggle('dark', this.dark);
        localStorage.setItem('color-theme', this.dark ? 'dark' : 'light');
        localStorage.setItem('theme', this.dark ? 'dark' : 'light');
    },
});

Alpine.start();
Alpine.store('theme').apply();
initFlowbite();

const isLikelyTablet = () => {
    const touchPoints = Number(navigator.maxTouchPoints ?? 0);
    const screenWidth = window.screen?.width ?? 0;
    const screenHeight = window.screen?.height ?? 0;

    return touchPoints > 0 && Math.min(screenWidth, screenHeight) >= 600;
};

const populateDeviceDetectionHints = (form) => {
    const deviceTypeInput = form.elements.namedItem('device_type_hint');
    const deviceModelInput = form.elements.namedItem('device_model_hint');

    if (!(deviceTypeInput instanceof HTMLInputElement)) {
        return;
    }

    if (isLikelyTablet()) {
        deviceTypeInput.value = 'tablet';
    }

    if (!(deviceModelInput instanceof HTMLInputElement) || !navigator.userAgentData?.getHighEntropyValues) {
        return;
    }

    navigator.userAgentData.getHighEntropyValues(['model', 'formFactors']).then((hints) => {
        if (hints.formFactors?.includes('Tablet')) {
            deviceTypeInput.value = 'tablet';
        }

        if (hints.model) {
            deviceModelInput.value = hints.model;
        }
    }).catch(() => {});
};

document.querySelectorAll('form[data-device-detection]').forEach((form) => {
    populateDeviceDetectionHints(form);
    form.addEventListener('submit', () => populateDeviceDetectionHints(form));
});
