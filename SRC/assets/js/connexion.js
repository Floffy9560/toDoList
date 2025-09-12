const openEye = document.getElementById("openEye");
const closeEye = document.getElementById("closeEye");
const passwordInput = document.getElementById("password");

openEye.addEventListener("click", () => {
  passwordInput.type = "password";
  openEye.style.display = "none";
  closeEye.style.display = "block";
});
closeEye.addEventListener("click", () => {
  passwordInput.type = "text";
  openEye.style.display = "block";
  closeEye.style.display = "none";
});

const forget_password = document.getElementById("forget_password");
const form_forget_password = document.querySelector(".form_forget_password");
const close_forget = document.getElementById("close_forget");

forget_password.addEventListener("click", function () {
  form_forget_password.classList.add("active");
});
close_forget.addEventListener("click", function () {
  form_forget_password.classList.remove("active");
});

//
// ** authentification Google
// ==========================

function handleCredentialResponse(response) {
  // Envoi du token à ton backend via AJAX
  fetch("API/google/google-login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ credential: response.credential }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        Swal.fire({
          title: "Connexion réussie",
          text: "Bienvenue " + data.user.name + " 🎉",
          icon: "success",
          confirmButtonText: "Allons-y !",
        }).then(() => {
          window.location.href = "/";
        });
      } else {
        Swal.fire("Erreur", data.message, "error");
      }
    });
}
