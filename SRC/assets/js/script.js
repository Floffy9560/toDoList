// window.addEventListener("DOMContentLoaded", () => {
//   const buttonProject = document.querySelector(".project");

//   buttonProject.addEventListener("click", (e) => {
//     e.preventDefault();
//     window.location.href = "createProject";
//   });

//   const taskGroups = document.querySelectorAll(".cardProject"); // nouveau : chaque projet est un .task-group

//   taskGroups.forEach((group) => {
//     const tasks = group.querySelectorAll(".bi-check2-circle");
//     const liItems = group.querySelectorAll(".currentTask");
//     const finishBtn = group.querySelector("#finish");

//     // Désactive le bouton "finish" par défaut
//     finishBtn.disabled = true;

//     tasks.forEach((task, index) => {
//       task.addEventListener("click", () => {
//         const li = liItems[index];

//         const isCompleted =
//           task.style.color === "green" &&
//           task.style.scale === "1.2" &&
//           li.style.textDecoration === "line-through";

//         if (!isCompleted) {
//           task.style.color = "green";
//           task.style.scale = "1.2";
//           li.style.textDecoration = "line-through";
//         } else {
//           task.style.color = "";
//           task.style.scale = "1";
//           li.style.textDecoration = "none";
//         }

//         // Vérifie si toutes les tâches du groupe sont complètes
//         const allDone = Array.from(tasks).every((t, i) => {
//           return (
//             t.style.color === "green" &&
//             t.style.scale === "1.2" &&
//             liItems[i].style.textDecoration === "line-through"
//           );
//         });

//         finishBtn.disabled = !allDone;
//       });
//     });
//   });
// });

window.addEventListener("DOMContentLoaded", () => {
  const buttonProject = document.querySelector(".project");

  buttonProject?.addEventListener("click", (e) => {
    e.preventDefault();
    window.location.href = "createProject";
  });

  const taskGroups = document.querySelectorAll(".cardProject");

  taskGroups.forEach((group) => {
    const tasks = group.querySelectorAll(".bi-check2-circle");
    const liItems = group.querySelectorAll(".currentTask");
    const finishBtn = group.querySelector("#finish");

    if (finishBtn) finishBtn.disabled = true;

    tasks.forEach((task, index) => {
      task.addEventListener("click", (e) => {
        e.preventDefault();

        const li = liItems[index];
        const form = task.closest("form");
        const formData = new FormData(form);

        // Détermine le nouvel état à envoyer
        const isCompleted = li.style.textDecoration === "line-through";
        const newDoneValue = isCompleted ? "0" : "1";
        formData.append("done", newDoneValue);

        // Appel AJAX
        fetch("updateTaskStatus", {
          method: "POST",
          body: formData,
        })
          .then((res) => res.text())
          .then((text) => {
            try {
              const data = JSON.parse(text);

              if (data.success) {
                // Applique les changements visuels
                if (newDoneValue === "1") {
                  task.style.color = "green";
                  task.style.scale = "1.2";
                  li.style.textDecoration = "line-through";
                } else {
                  task.style.color = "";
                  task.style.scale = "1";
                  li.style.textDecoration = "none";
                }

                // Active/désactive le bouton "finish"
                const allDone = Array.from(liItems).every(
                  (item) => item.style.textDecoration === "line-through"
                );
                if (finishBtn) finishBtn.disabled = !allDone;
              } else {
                console.error(
                  "Erreur serveur :",
                  data.message || "Réponse invalide"
                );
              }
            } catch (err) {
              console.error("Réponse non JSON :", text);
            }
          })
          .catch((err) => {
            console.error("Erreur requête :", err);
          });
      });
    });
  });

  const menuBurger = document.getElementById("toggleMenu");
  const ulNav = document.getElementById("ulNav");

  menuBurger.addEventListener("click", () => {
    const visible = ulNav.style.display === "flex";
    ulNav.style.display = visible ? "none" : "flex";
  });
});
