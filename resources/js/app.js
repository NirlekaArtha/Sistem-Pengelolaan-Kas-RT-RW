const THEME_KEY = "theme-preference";
const themeQuery = window.matchMedia("(prefers-color-scheme: dark)");

function getStoredTheme() {
    return localStorage.getItem(THEME_KEY) || "system";
}

function resolveTheme(theme) {
    if (theme === "system") {
        return themeQuery.matches ? "dark" : "light";
    }

    return theme === "dark" ? "dark" : "light";
}

function applyTheme(theme) {
    const resolvedTheme = resolveTheme(theme);

    document.documentElement.classList.toggle("dark", resolvedTheme === "dark");
    document.documentElement.dataset.themePreference = theme;

    document.querySelectorAll("[data-theme-option]").forEach((button) => {
        const isActive = button.dataset.themeOption === theme;

        button.setAttribute("aria-pressed", isActive ? "true" : "false");
        button.dataset.active = isActive ? "true" : "false";
    });
}

function handleThemeSelection(theme) {
    localStorage.setItem(THEME_KEY, theme);
    applyTheme(theme);
}

document.addEventListener("DOMContentLoaded", () => {
    applyTheme(getStoredTheme());

    document.querySelectorAll("[data-theme-option]").forEach((button) => {
        button.addEventListener("click", () => {
            handleThemeSelection(button.dataset.themeOption || "system");
        });
    });

    const togglePasswordBtn = document.getElementById("toggle-password");
    const passwordInput = document.getElementById("password");
    const eyeIcon = document.getElementById("eye-icon");
    const eyeSlashIcon = document.getElementById("eye-slash-icon");

    if (togglePasswordBtn && passwordInput && eyeIcon && eyeSlashIcon) {
        togglePasswordBtn.addEventListener("click", () => {
            const type =
                passwordInput.getAttribute("type") === "password"
                    ? "text"
                    : "password";

            passwordInput.setAttribute("type", type);
            eyeIcon.classList.toggle("hidden");
            eyeSlashIcon.classList.toggle("hidden");
        });
    }
});

themeQuery.addEventListener("change", () => {
    if (getStoredTheme() === "system") {
        applyTheme("system");
    }
});
