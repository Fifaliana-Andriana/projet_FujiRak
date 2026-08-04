document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");
  const main = document.querySelector(".main");

  if (!sidebar || !main) return;

  // Le mode réduit (icônes seules) a exactement le même comportement en
  // desktop qu'en mobile/tablette : seul le clic sur le bouton toggle
  // change l'état, jamais un clic sur un lien de navigation. L'état est
  // persisté car le site n'est pas une SPA (chaque lien recharge la page).
  const SIDEBAR_KEY = "sidebarCollapsed";
  let saved = localStorage.getItem(SIDEBAR_KEY);

  // Première visite (aucune préférence enregistrée) : réduit par défaut
  // sur petit écran pour ne pas manger tout l'espace disponible.
  if (saved === null && window.innerWidth <= 768) {
    saved = "1";
  }

  if (saved === "1") {
    sidebar.classList.add("collapsed");
    main.classList.add("expanded");
  }
  document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");
    const main = document.querySelector(".main");

    if (!sidebar || !main) return;

    const SIDEBAR_KEY = "sidebarCollapsed";
    let saved = localStorage.getItem(SIDEBAR_KEY);

    if (saved === null && window.innerWidth <= 768) {
      saved = "1";
    }

    if (saved === "1") {
      sidebar.classList.add("collapsed");
      main.classList.add("expanded");
    }

    // La classe anti-flash du <head> a fait son travail, on la retire
    // pour laisser les vraies classes .collapsed/.expanded prendre le relais
    document.documentElement.classList.remove("sidebar-collapsed-init");

    if (!toggleBtn) return;

    toggleBtn.addEventListener("click", function () {
      const collapsed = sidebar.classList.toggle("collapsed");
      main.classList.toggle("expanded");
      localStorage.setItem(SIDEBAR_KEY, collapsed ? "1" : "0");
    });
  });

  if (!toggleBtn) return;

  toggleBtn.addEventListener("click", function () {
    const collapsed = sidebar.classList.toggle("collapsed");
    main.classList.toggle("expanded");
    localStorage.setItem(SIDEBAR_KEY, collapsed ? "1" : "0");
  });
});
