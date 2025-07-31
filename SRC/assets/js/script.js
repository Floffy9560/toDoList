// window.addEventListener("DOMContentLoaded", () => {
//   const buttonProject = document.querySelector(".project");

//   buttonProject?.addEventListener("click", (e) => {
//     e.preventDefault();
//     window.location.href = "createProject";
//   });

//   const taskGroups = document.querySelectorAll(".cardProject");

//   taskGroups.forEach((group) => {
//     const tasks = group.querySelectorAll(".bi-check2-circle");
//     const liItems = group.querySelectorAll(".currentTask");
//     const finishBtn = group.querySelector("#finish");

//     if (finishBtn) finishBtn.disabled = true;

//     tasks.forEach((task, index) => {
//       task.addEventListener("click", (e) => {
//         e.preventDefault();

//         const li = liItems[index];
//         const form = task.closest("form");
//         const formData = new FormData(form);

//         // Détermine le nouvel état à envoyer
//         const isCompleted = li.style.textDecoration === "line-through";
//         const newDoneValue = isCompleted ? "0" : "1";
//         formData.append("done", newDoneValue);

//         // Appel AJAX
//         fetch("updateTaskStatus", {
//           method: "POST",
//           body: formData,
//         })
//           .then((res) => res.text())
//           .then((text) => {
//             try {
//               const data = JSON.parse(text);

//               if (data.success) {
//                 // Applique les changements visuels
//                 if (newDoneValue === "1") {
//                   task.style.color = "green";
//                   task.style.scale = "1.2";
//                   li.style.textDecoration = "line-through";
//                 } else {
//                   task.style.color = "";
//                   task.style.scale = "1";
//                   li.style.textDecoration = "none";
//                 }

//                 // Active/désactive le bouton "finish"
//                 const allDone = Array.from(liItems).every(
//                   (item) => item.style.textDecoration === "line-through"
//                 );
//                 if (finishBtn) finishBtn.disabled = !allDone;
//               } else {
//                 console.error(
//                   "Erreur serveur :",
//                   data.message || "Réponse invalide"
//                 );
//               }
//             } catch (err) {
//               console.error("Réponse non JSON :", text);
//             }
//           })
//           .catch((err) => {
//             console.error("Erreur requête :", err);
//           });
//       });
//     });

//     document.querySelectorAll(".priority-radio").forEach((radio) => {
//       radio.addEventListener("change", (event) => {
//         const taskId = event.target.dataset.taskId;
//         const priority = event.target.value;

//         fetch("/update_priority.php", {
//           method: "POST",
//           headers: {
//             "Content-Type": "application/json",
//           },
//           body: JSON.stringify({
//             taskId,
//             priority,
//           }),
//         })
//           .then((res) => res.json())
//           .then((data) => {
//             if (data.success) {
//               console.log("Priorité mise à jour avec succès");
//             } else {
//               console.error("Erreur serveur :", data.error || "Inconnue");
//             }
//           })
//           .catch((err) => {
//             console.error("Erreur requête :", err);
//           });
//       });
//     });
//   });

//   const menuBurger = document.getElementById("toggleMenu");
//   const ulNav = document.getElementById("ulNav");

//   menuBurger.addEventListener("click", () => {
//     const visible = ulNav.style.display === "flex";
//     ulNav.style.display = visible ? "none" : "flex";
//   });
// });

window.addEventListener("DOMContentLoaded", () => {
  // Gestion du bouton "Nouveau projet/tâche"
  const buttonProject = document.querySelector(".project");
  buttonProject?.addEventListener("click", (e) => {
    e.preventDefault();
    window.location.href = "createProject";
  });

  // Gestion des tâches dans chaque projet
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

        // Appel AJAX pour updateTaskStatus
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

  // Gestion du menu burger
  const menuBurger = document.getElementById("toggleMenu");
  const ulNav = document.getElementById("ulNav");
  menuBurger?.addEventListener("click", () => {
    const visible = ulNav.style.display === "flex";
    ulNav.style.display = visible ? "none" : "flex";
  });

  // --- AJOUT : gestion de la mise à jour de la priorité ---

  //   document.querySelectorAll(".priority-radio").forEach((radio) => {
  //     radio.addEventListener("change", (event) => {
  //       const taskId = event.target.dataset.taskId;
  //       const priority = event.target.value;

  //       // Trouver l’élément <li> correspondant
  //       const taskElement = event.target
  //         .closest("form")
  //         .querySelector("li.currentTask");

  //       if (taskElement) {
  //         // Nettoyer les anciennes classes de fond
  //         taskElement.classList.remove(
  //           "bg-priority-red",
  //           "bg-priority-orange",
  //           "bg-priority-green"
  //         );

  //         // Ajouter la nouvelle classe selon la priorité
  //         switch (parseInt(priority)) {
  //           case 1:
  //             taskElement.classList.add("bg-priority-red");
  //             break;
  //           case 2:
  //             taskElement.classList.add("bg-priority-orange");
  //             break;
  //           case 3:
  //             taskElement.classList.add("bg-priority-green");
  //             break;
  //         }
  //       }

  //       // Envoyer la mise à jour au serveur
  //       fetch("update_priority.php", {
  //         method: "POST",
  //         headers: {
  //           "Content-Type": "application/json",
  //         },
  //         body: JSON.stringify({
  //           taskId,
  //           priority,
  //         }),
  //       })
  //         .then((res) => res.json())
  //         .then((data) => {
  //           if (data.success) {
  //             console.log("✅ Priorité mise à jour");
  //           } else {
  //             console.error("❌ Erreur serveur :", data.error || "Inconnue");
  //           }
  //         })
  //         .catch((err) => {
  //           console.error("❌ Erreur requête :", err);
  //         });
  //     });
  //   });
  // });
  document.querySelectorAll(".priority-radio").forEach((radio) => {
    radio.addEventListener("change", (event) => {
      const taskId = event.target.dataset.taskId;
      const priority = event.target.value;

      // Cible le <form> au lieu du <li>
      const form = event.target.closest("form");

      if (form) {
        // Nettoie les anciennes classes de fond
        form.classList.remove(
          "bg-priority-red",
          "bg-priority-orange",
          "bg-priority-green"
        );

        // Ajoute la bonne classe selon la priorité choisie
        switch (parseInt(priority)) {
          case 1:
            form.classList.add("bg-priority-red");
            break;
          case 2:
            form.classList.add("bg-priority-orange");
            break;
          case 3:
            form.classList.add("bg-priority-green");
            break;
        }
      }

      // Envoie la priorité en BDD
      fetch("update_priority.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ taskId, priority }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (!data.success) {
            console.error("Erreur côté serveur :", data.error || "inconnue");
          }
        })
        .catch((err) => {
          console.error("Erreur réseau :", err);
        });
    });
  });
});
