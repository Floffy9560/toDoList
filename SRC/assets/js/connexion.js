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
