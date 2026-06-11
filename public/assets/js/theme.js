/**
 * Theme Management - Dark Mode Toggle
 */

(function() {
    // Check for saved theme preference or default to system preference
    const getPreferredTheme = () => {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            return savedTheme;
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    // Apply theme to document
    const setTheme = (theme) => {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.setAttribute('data-theme', 'light');
        }
        localStorage.setItem('theme', theme);
        
        // Dispatch event for other components (like charts) to react
        document.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    };

    // Initialize theme on page load
    const initTheme = () => {
        const theme = getPreferredTheme();
        setTheme(theme);
    };

    // Toggle theme function
    window.toggleTheme = () => {
        const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
        
        // Add animation class for smooth transition
        document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
        setTimeout(() => {
            document.body.style.transition = '';
        }, 300);
    };

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });

    // Initialize immediately to prevent flash of unstyled content (white flash)
    initTheme();
})();
