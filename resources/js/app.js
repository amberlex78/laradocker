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
