<script data-theme-init>
    (() => {
        const savedTheme = localStorage.getItem('color-theme');
        const defaultTheme = document.documentElement.dataset.defaultTheme ?? 'light';
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const shouldUseDark = savedTheme === 'dark'
            || (savedTheme === null && (defaultTheme === 'dark' || systemPrefersDark));

        document.documentElement.classList.toggle('dark', shouldUseDark);
    })();
</script>
