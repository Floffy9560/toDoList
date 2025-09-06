document.addEventListener("DOMContentLoaded", () => {
  const popup = document.querySelector(".popup-message");
  if (popup) {
    popup.classList.add("show");

    // Après 2 secondes, faire disparaître le message
    setTimeout(() => {
      popup.classList.remove("show");
      // Retirer complètement du DOM après transition
      setTimeout(() => popup.remove(), 500);
    }, 2000);
  }
});
