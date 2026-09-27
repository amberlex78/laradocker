import Alpine from 'alpinejs';

window.Alpine = Alpine;

const savedTheme = localStorage.getItem('color-theme');
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
    },
});

Alpine.start();
Alpine.store('theme').apply();
