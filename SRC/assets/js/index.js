window.addEventListener("DOMContentLoaded", () => {
  const container = document.querySelector("#projects-container");

  // ===== Bouton "Nouveau projet/tâche" =====
  const btn_projects = document.querySelectorAll(".btn_create_project");
  btn_projects.forEach((btn) =>
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      window.location.href = "createProject";
    })
  );

  // ===== Gestion des boutons "Check" et "Delete" via délégation =====
  container.addEventListener("click", (e) => {
    const btnCheck = e.target.closest(".btnCheck");
    const btnDelete = e.target.closest(".btnDelete");

    if (btnCheck) {
      e.preventDefault();
      const form = btnCheck.closest("form");
      const li = form.querySelector(".currentTask");

      const taskId = form.querySelector("[name=doneTask]").value;
      const isCompleted = li.classList.contains("done");

      const formData = new FormData();
      formData.append("taskId", taskId);
      formData.append("done", isCompleted ? "0" : "1");

      fetch("updateTaskStatus.php", { method: "POST", body: formData })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            // Ajouter ou retirer la classe "done"
            li.classList.toggle("done", !isCompleted);
            form.classList.toggle("done", !isCompleted);

            // Bouton check coloré
            btnCheck.style.color = isCompleted ? "" : "green";
            btnCheck.style.scale = isCompleted ? "1" : "1.2";

            // Vérifier si toutes les tâches du projet sont terminées
            const card = form.closest(".cardProject");
            const allDone = Array.from(
              card.querySelectorAll(".currentTask")
            ).every((item) => item.classList.contains("done"));

            const finishBtn = card.querySelector("#finish");
            if (finishBtn) finishBtn.disabled = !allDone;
          } else {
            console.error(data.message);
          }
        })
        .catch((err) => console.error("Erreur réseau :", err));
    }
    if (btnDelete) {
      e.preventDefault();
      const form = btnDelete.closest("form");
      const taskId = form.querySelector("[name=deleteTask]").value;

      const formData = new FormData();
      formData.append("taskId", taskId);
      formData.append("deleteTask", "1");

      fetch("updateTaskStatus.php", { method: "POST", body: formData })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            // ⚠️ trouver la carte avant de supprimer le form
            const card = form.closest(".cardProject");

            // supprimer la tâche
            form.remove();

            // mettre à jour le bouton "Terminé"
            if (card) {
              const tasks = card.querySelectorAll(".currentTask");
              const finishBtn = card.querySelector("#finish");

              const allDoneOrEmpty =
                tasks.length === 0 ||
                Array.from(tasks).every(
                  (item) => item.style.textDecoration === "line-through"
                );

              if (finishBtn) finishBtn.disabled = !allDoneOrEmpty;
            }
          } else {
            console.error(data.message);
          }
        })
        .catch((err) => console.error("Erreur réseau :", err));
    }

    // ===== Gestion de la priorité via délégation =====
    container.addEventListener("change", (e) => {
      if (!e.target.classList.contains("priority-radio")) return;
      const taskId = e.target.dataset.taskId;
      const priority = parseInt(e.target.value, 10);
      const form = e.target.closest("form");
      if (!form) return;

      form.classList.remove(
        "bg-priority-red",
        "bg-priority-orange",
        "bg-priority-green"
      );
      if (priority === 1) form.classList.add("bg-priority-red");
      if (priority === 2) form.classList.add("bg-priority-orange");
      if (priority === 3) form.classList.add("bg-priority-green");

      fetch("update_priority.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ taskId, priority }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (!data.success) console.error(data.error || "inconnue");
        })
        .catch((err) => console.error("Erreur réseau :", err));
    });
  });

  // ===== Affichage des projets =====
  function renderProjects(projects) {
    container.innerHTML = "";
    if (!projects || projects.length === 0) {
      container.innerHTML = "<p>Aucun projet pour le moment.</p>";
      return;
    }

    const priorityMap = {
      1: "bg-priority-red",
      2: "bg-priority-orange",
      3: "bg-priority-green",
    };

    projects.forEach((projet) => {
      const card = document.createElement("div");
      card.classList.add("cardProject");

      // Titre
      const h3 = document.createElement("h3");
      h3.textContent = projet.project_name;
      card.appendChild(h3);

      // Liste des tâches
      const ul = document.createElement("ul");

      if (projet.tasks && projet.tasks.length > 0) {
        projet.tasks.forEach((task) => {
          const form = document.createElement("form");
          form.classList.add("formProject", "task");
          const cls = priorityMap[task.priority];
          if (cls) form.classList.add(cls);
          form.method = "POST";
          form.onsubmit = () => false;

          // Input caché pour la tâche
          const inputHidden = document.createElement("input");
          inputHidden.type = "hidden";
          inputHidden.name = "currentTask";
          inputHidden.value = task.task;
          form.appendChild(inputHidden);

          // <li class="currentTask ...">
          const li = document.createElement("li");
          li.className = "currentTask" + (task.done ? " done" : "");

          // <div class="currentTask__text"> avec la tâche et la deadline
          const liTextDiv = document.createElement("div");
          liTextDiv.classList.add("currentTask__text");
          liTextDiv.textContent = task.task;

          if (task.deadline) {
            const spanDeadline = document.createElement("span");
            spanDeadline.classList.add("deadline");
            spanDeadline.dataset.deadline = task.deadline;

            const dateObj = new Date(task.deadline);
            const day = String(dateObj.getDate()).padStart(2, "0");
            const month = String(dateObj.getMonth() + 1).padStart(2, "0");
            const year = dateObj.getFullYear();

            spanDeadline.textContent = ` ⏳ ${day}/${month}/${year}`;
            liTextDiv.appendChild(spanDeadline);
          }

          li.appendChild(liTextDiv);

          // Groupe des priorités
          const priorityDiv = document.createElement("div");
          priorityDiv.classList.add("priority-group");
          for (let i = 1; i <= 3; i++) {
            const label = document.createElement("label");
            label.className =
              i === 1
                ? "priority-red"
                : i === 2
                ? "priority-orange"
                : "priority-green";
            label.style.marginRight = "8px";

            const radio = document.createElement("input");
            radio.type = "radio";
            radio.name = `priority_${task.Id_tasks}`;
            radio.value = i;
            radio.classList.add("priority-radio");
            radio.dataset.taskId = task.Id_tasks;
            if (task.priority == i) radio.checked = true;

            label.appendChild(radio);
            label.appendChild(
              document.createTextNode(
                i === 1 ? "Urgent" : i === 2 ? "Important" : "Normal"
              )
            );

            priorityDiv.appendChild(label);
          }

          li.appendChild(priorityDiv);
          form.appendChild(li);

          // Boutons Check / Delete
          const btnDiv = document.createElement("div");
          btnDiv.classList.add("formProject__btn_action");

          const inputDone = document.createElement("input");
          inputDone.type = "hidden";
          inputDone.name = "doneTask";
          inputDone.value = task.done;
          const btnCheck = document.createElement("button");
          btnCheck.type = "submit";
          btnCheck.classList.add("btnCheck");
          btnCheck.innerHTML = '<i class="bi bi-check2-circle"></i>';

          const inputDelete = document.createElement("input");
          inputDelete.type = "hidden";
          inputDelete.name = "deleteTask";
          inputDelete.value = task.Id_tasks;
          const btnDelete = document.createElement("button");
          btnDelete.type = "submit";
          btnDelete.classList.add("btnDelete");
          btnDelete.innerHTML = '<i class="bi bi-x"></i>';

          btnDiv.append(inputDone, btnCheck, inputDelete, btnDelete);
          form.appendChild(btnDiv);

          ul.appendChild(form);
        });
      } else {
        const li = document.createElement("li");
        li.textContent = "Aucune tâche pour ce projet";
        li.style.textAlign = "center";
        ul.appendChild(li);
      }

      card.appendChild(ul);

      // Formulaire pour terminer le projet
      const finishForm = document.createElement("form");
      finishForm.method = "GET";

      const finishInput = document.createElement("input");
      finishInput.type = "hidden";
      finishInput.name = "deleteProject";
      finishInput.value = projet.Id_project;
      finishForm.appendChild(finishInput);

      const finishBtn = document.createElement("button");
      finishBtn.id = "finish";
      finishBtn.textContent = "Terminé !";
      finishBtn.disabled = !(projet.tasks && projet.tasks.every((t) => t.done));
      finishForm.appendChild(finishBtn);
      card.appendChild(finishForm);

      // Bouton ajouter une tâche
      const addTaskLink = document.createElement("a");
      addTaskLink.href = "#";
      addTaskLink.classList.add("btn_create_task");
      addTaskLink.title = "Créer une nouvelle tâche";
      addTaskLink.style.cssText = "position: absolute; bottom:5px; left: 10px;";
      addTaskLink.dataset.projetId = projet.Id_project;
      addTaskLink.innerHTML = '<i class="bi bi-clipboard-plus"></i>';

      addTaskLink.addEventListener("click", (e) => {
        e.preventDefault();
        document.getElementById("projet_id").value = projet.Id_project;
        document.getElementById("modalTask").style.display = "block";
      });

      card.appendChild(addTaskLink);

      container.appendChild(card);

      colorDeadlines();
    });
  }

  // ===== Filtrage des projets =====
  document.querySelectorAll(".project_category__form").forEach((form) => {
    const btn = form.querySelector(".btnCategory");
    console.log(btn);

    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      fetch("display_project_category.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) renderProjects(data.projects);
          else console.error(data.message);
        })
        .catch((err) => console.error("Erreur réseau :", err));
    });
  });
});

const taks = document.querySelectorAll(".cardProject");
taks.forEach((task) => {
  const btn_finish = task.querySelector("#finish");
  const li = task.querySelector("ul li").textContent;

  if (li === "Aucune tâche pour ce projet" || li === "") {
    btn_finish.disabled = false;
  }
});

//
// ** Gestion de la modale pour ajouter une tâche **
// ================================================= //
const modal = document.getElementById("modalTask");
const closeModal = modal.querySelector(".close");
const form = document.getElementById("formAddTask");
const messageContainer = document.getElementById("messageContainer"); // div pour les messages

// Ouverture du modal
document.querySelectorAll(".btn_create_task").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();

    const projetId = btn.dataset.projetId;
    const projetName = btn.dataset.projetName;

    const projetIdInput = document.getElementById("current_project_id");
    const projetNameTitle = document.getElementById("modalProjectName");
    const newProjectContainer = document.getElementById("newProjectContainer");

    if (projetName && projetId) {
      // Projet existant
      projetIdInput.value = projetId;
      projetNameTitle.textContent = projetName;
      projetNameTitle.style.display = "block";
      newProjectContainer.style.display = "none";
    } else {
      // Nouveau projet
      projetIdInput.value = ""; // pas d'id
      projetNameTitle.textContent = "";
      projetNameTitle.style.display = "none";
      newProjectContainer.style.display = "block";
    }

    modal.style.display = "block";
  });
});

// Fermeture du modal
closeModal.addEventListener("click", () => {
  modal.style.display = "none";
});

window.addEventListener("click", (e) => {
  if (e.target === modal) {
    modal.style.display = "none";
  }
});

//
// ** Envoyer le formulaire via AJAX pour ne pas recharger la page **
// ================================================================== //
form.addEventListener("submit", (e) => {
  e.preventDefault();
  const formData = new FormData(form);

  fetch(form.action, {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      // Fermer le modal
      modal.style.display = "none";

      // Créer un message
      const msg = document.createElement("div");
      msg.classList.add("message");
      msg.textContent = data.message || "Opération terminée.";

      // Si c’est une erreur
      if (!data.success) {
        msg.classList.add("error");
      }

      messageContainer.appendChild(msg);

      // disparition automatique
      setTimeout(() => {
        msg.classList.add("hide");
        setTimeout(() => msg.remove(), 500);
      }, 5000);

      // 🔄 recharger la page si succès
      if (data.success) {
        setTimeout(() => window.location.reload(), 1000);
      }
    })
    .catch((err) => {
      console.error("Erreur Fetch :", err);
    });
});

//
// ** Ajout d'une deadline qui change de couleur celon la date du jour (si eloignée ou non ) **
// ============================================================================================ //
function colorDeadlines() {
  document.querySelectorAll(".deadline").forEach((el) => {
    el.classList.remove("late", "soon", "ok"); // réinitialiser
    const deadline = new Date(el.dataset.deadline);
    const today = new Date();
    const diffTime = deadline - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays < 0) el.classList.add("late");
    else if (diffDays <= 2) el.classList.add("soon");
    else el.classList.add("ok");
  });
}
colorDeadlines();
