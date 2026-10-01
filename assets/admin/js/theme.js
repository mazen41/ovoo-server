// Set the theme color on the document and manage icons
function setThemeColor(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    document.documentElement.setAttribute("data-bs-theme", theme);
    localStorage.setItem('theme', theme);
}

// Initialize theme based on localStorage or system preference
const savedTheme = localStorage.getItem('theme');
const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

if (savedTheme) {
    setThemeColor(savedTheme);
} else {
    setThemeColor(systemPrefersDark ? 'dark' : 'light');
}

document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('switch-theme')?.addEventListener('click', function (event) {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        setThemeColor(newTheme);
    });

    // Detect changes in system color scheme (light/dark mode)
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        const newColorScheme = event.matches ? 'dark' : 'light';
        setThemeColor(newColorScheme);
    });
});
