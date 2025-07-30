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
