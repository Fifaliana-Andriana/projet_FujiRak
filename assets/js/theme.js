document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('themeToggle');
    if (!toggleBtn) return;

    const icon = toggleBtn.querySelector('i');

    function applyTheme(isDark) {
        document.body.classList.toggle('theme-dark', isDark);
        if (icon) {
            icon.classList.toggle('bi-moon-fill', !isDark);
            icon.classList.toggle('bi-sun-fill', isDark);
        }
    }

    const saved = localStorage.getItem('finixiias-theme');
    applyTheme(saved === 'dark');

    toggleBtn.addEventListener('click', function () {
        const isDark = !document.body.classList.contains('theme-dark');
        applyTheme(isDark);
        localStorage.setItem('finixiias-theme', isDark ? 'dark' : 'light');
    });
});
