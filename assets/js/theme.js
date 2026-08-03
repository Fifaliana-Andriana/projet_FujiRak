document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("themeToggle");
  const logoTogglelight = document.getElementById("logoTogglelight");
  const logoToggledark = document.getElementById("logoToggledark");
  const sidebarToggle = document.getElementById("sidebarToggle");
  if (!toggleBtn) return;

  const icon = toggleBtn.querySelector("i");

  function applyTheme(isDark) {
    document.body.classList.toggle("theme-dark", isDark);

    if (icon) {
      icon.classList.toggle("bi-moon-fill", !isDark);
      icon.classList.toggle("bi-sun-fill", isDark);
    }

    if (logoToggledark) {
      logoToggledark.classList.toggle("d-none", !isDark);
    }
    if (logoTogglelight) {
      logoTogglelight.classList.toggle("d-none", isDark);
    }
    if (sidebarToggle) {
      sidebarToggle.classList.toggle("light", isDark);
      sidebarToggle.classList.toggle("dark", isDark);
    }
  }

  const saved = localStorage.getItem("finixiias-theme");
  applyTheme(saved === "dark");

  toggleBtn.addEventListener("click", function () {
    const isDark = !document.body.classList.contains("theme-dark");
    applyTheme(isDark);
    localStorage.setItem("finixiias-theme", isDark ? "dark" : "light");
  });
});
