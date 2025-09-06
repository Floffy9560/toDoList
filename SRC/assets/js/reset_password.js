document.querySelectorAll(".form__input_password").forEach((wrapper) => {
  const input = wrapper.querySelector("input");
  const openEye = wrapper.querySelector(".bi-eye");
  const closeEye = wrapper.querySelector(".bi-eye-slash");

  openEye.addEventListener("click", () => {
    input.type = "text";
    openEye.style.display = "none";
    closeEye.style.display = "inline";
  });

  closeEye.addEventListener("click", () => {
    input.type = "password";
    openEye.style.display = "inline";
    closeEye.style.display = "none";
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const countdown = document.getElementById("countdown");
  if (!countdown) return;

  let count = parseInt(countdown.textContent, 10);
  const interval = setInterval(() => {
    count--;
    if (count < 0) {
      clearInterval(interval);
      window.location.href = "index"; // page de redirection
    } else {
      countdown.textContent = count;
    }
  }, 1000);
});
