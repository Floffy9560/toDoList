window.addEventListener("DOMContentLoaded", () => {
  // ===== Gestion du menu burger =====
  document.getElementById("toggleMenu")?.addEventListener("click", () => {
    const ulNav = document.getElementById("ulNav");
    ulNav.style.display = ulNav.style.display === "flex" ? "none" : "flex";
  });
});
