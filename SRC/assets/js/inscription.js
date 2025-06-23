const openEye = document.getElementById("openEye");
const closeEye = document.getElementById("closeEye");

closeEye.addEventListener("click", () => {
  passwordInput.type = "text";
  openEye.style.display = "block";
  closeEye.style.display = "none";
});
openEye.addEventListener("click", () => {
  passwordInput.type = "password";
  openEye.style.display = "none";
  closeEye.style.display = "block";
});

const passwordInput = document.getElementById("password");
const conditions = {
  length: (pwd) => pwd.length >= 8,
  uppercase: (pwd) => /[A-Z]/.test(pwd),
  lowercase: (pwd) => /[a-z]/.test(pwd),
  digit: (pwd) => /[0-9]/.test(pwd),
  special: (pwd) => /[\W_]/.test(pwd),
};

passwordInput.addEventListener("input", () => {
  const pwd = passwordInput.value;
  const messages = document.querySelectorAll(".passwordVerify .small");

  messages.forEach((p) => {
    const cond = p.getAttribute("data-condition");
    if (conditions[cond](pwd)) {
      p.classList.add("valid");
    } else {
      p.classList.remove("valid");
    }
  });
});
