window.addEventListener("DOMContentLoaded", () => {
  const container = document.querySelector("#projects-container");
  const modal = document.getElementById("modalTask");
  const closeModal = modal.querySelector(".close");
  const form = document.getElementById("formAddTask");
  const messageContainer = document.getElementById("messageContainer");

  const priorityMap = {
    1: "bg-priority-red",
    2: "bg-priority-orange",
    3: "bg-priority-green",
  };

  //
  //  Fonction pour la mise à jour le bouton Terminé
  // ===============================================
  function updateFinishButtons() {
    document.querySelectorAll(".cardProject").forEach((card) => {
      const finishBtn = card.querySelector("#finish");
      if (!finishBtn) return;
      const tasks = card.querySelectorAll(".currentTask");
      const allDoneOrEmpty =
        tasks.length === 0 ||
        Array.from(tasks).every((t) => t.classList.contains("done"));
      finishBtn.disabled = !allDoneOrEmpty;
    });
  }

  //
  //  Fonctions pour la coloration des deadlines
  // ===========================================
  function colorDeadlines() {
    document.querySelectorAll(".deadline").forEach((el) => {
      el.classList.remove("late", "soon", "ok");
      const deadline = new Date(el.dataset.deadline);
      const today = new Date();
      const diffDays = Math.ceil((deadline - today) / (1000 * 60 * 60 * 24));

      if (diffDays < 0) el.classList.add("late");
      else if (diffDays <= 15) el.classList.add("soon");
      else el.classList.add("ok");
    });
  }

  //
  //  Fonctions pour mettre à jour le compteur de tâches
  // ===================================================
  function updateTaskCount(projectId, newCount) {
    const spanCount = document.querySelector(`#count_tasks_${projectId}`);
    if (spanCount) spanCount.textContent = newCount;
  }

  //
  //  Fonctions pour le rendu des projets
  // ====================================

  function renderProjects(projects) {
    const container = document.getElementById("projects-container");
    container.innerHTML = "";

    if (!projects || projects.length === 0) {
      container.innerHTML = "<p>Aucun projet pour le moment.</p>";
      return;
    }

    projects.forEach((projet) => {
      const card = document.createElement("div");
      card.classList.add("cardProject");

      // Titre + compteur
      const countDiv = document.createElement("div");
      countDiv.classList.add("cardProject__countTasks");
      countDiv.innerHTML = `
      <h3>${projet.project_name}</h3>
      <span id="count_tasks_${projet.Id_project}" title="Nombre de tâches">
        ${projet.tasks ? projet.tasks.length : 0}
      </span>
    `;
      card.appendChild(countDiv);

      // Liste des tâches
      const ul = document.createElement("ul");

      if (projet.tasks && projet.tasks.length > 0) {
        projet.tasks.forEach((task) => {
          const priorityClass =
            task.priority_task === 1
              ? "bg-priority-red"
              : task.priority_task === 2
              ? "bg-priority-orange"
              : task.priority_task === 3
              ? "bg-priority-green"
              : "";

          const doneClass = task.done ? "done" : "";

          const deadlineSpan = task.deadline
            ? `<span class="deadline" data-deadline="${task.deadline}">
               ⏳ ${new Date(task.deadline).toLocaleDateString("fr-FR")}
             </span>`
            : "";

          ul.innerHTML += `
          <form class="formProject task ${priorityClass}" method="POST">
            <input type="hidden" name="currentTask" value="${task.task}">
            <li class="currentTask ${doneClass}">
              <div class="currentTask__text">
                ${task.task} ${deadlineSpan}
              </div>
            </li>
            <div class="formProject__btn_action_popup">
              <span class="formProject__btn_action_popup__close"><i class="bi bi-x-lg"></i></span>
              <div class="priority-group">
                <label class="priority-red" style="margin-right:8px;">
                  <input type="radio" name="priority_${
                    task.Id_tasks
                  }" value="1" class="priority-radio" data-task-id="${
            task.Id_tasks
          }" ${task.priority_task == 1 ? "checked" : ""}>Urgent
                </label>
                <label class="priority-orange" style="margin-right:8px;">
                  <input type="radio" name="priority_${
                    task.Id_tasks
                  }" value="2" class="priority-radio" data-task-id="${
            task.Id_tasks
          }" ${task.priority_task == 2 ? "checked" : ""}>Important
                </label>
                <label class="priority-green" style="margin-right:8px;">
                  <input type="radio" name="priority_${
                    task.Id_tasks
                  }" value="3" class="priority-radio" data-task-id="${
            task.Id_tasks
          }" ${task.priority_task == 3 ? "checked" : ""}>Normal
                </label>
              </div>
              <div class="action-group">
                <input type="hidden" name="taskId" value="${task.Id_tasks}">
                <input type="hidden" name="doneTask" value="${task.done}">
                <button type="submit" class="btnCheck"><i class="bi bi-check2-circle"></i></button>
                <input type="hidden" name="deleteTask" value="${task.Id_tasks}">
                <button type="submit" class="btnDelete"><i class="bi bi-trash3"></i></button>
              </div>
            </div>
            <div class="formProject__btn_action"><i class="bi bi-three-dots"></i></div>
          </form>
        `;
        });
      } else {
        ul.innerHTML = `<li style="text-align:center;">Aucune tâche pour ce projet</li>`;
      }

      card.appendChild(ul);

      // Bouton terminer projet
      card.innerHTML += `
      <form action="" method="GET" class="cardProject__form_finish">
        <input type="hidden" name="deleteProject" value="${projet.Id_project}">
        <button id="finish" disabled>Terminé !</button>
      </form>
      <a href="#" class="main__btn_create_task" title="Créer une nouvelle tâche" data-projet-id="${projet.Id_project}" data-projet-name="${projet.project_name}">
        <i class="bi bi-clipboard-plus"></i>
      </a>
    `;

      container.appendChild(card);
    });

    // Réappliquer tes fonctions existantes
    updateFinishButtons();
    colorDeadlines();
  }

  //
  // Gestion clics sur container
  //============================
  container.addEventListener("click", (e) => {
    const btnCheck = e.target.closest(".btnCheck");
    const btnDelete = e.target.closest(".btnDelete");
    const btnAddTask = e.target.closest(".main__btn_create_task");
    const btnActionTask = e.target.closest(".formProject__btn_action");
    const closePopup = e.target.closest(
      ".formProject__btn_action_popup__close"
    );

    // Ouvrir le menu
    if (btnActionTask) {
      const taskCard = btnActionTask.closest(".task");
      const task_action_popup = taskCard.querySelector(
        ".formProject__btn_action_popup"
      );

      if (task_action_popup) {
        task_action_popup.classList.toggle("visible");
      }
    }

    // Fermer le menu
    if (closePopup) {
      const task_action_popup = closePopup.closest(
        ".formProject__btn_action_popup"
      );
      if (task_action_popup) {
        task_action_popup.classList.remove("visible");
      }
    }

    // Check
    if (btnCheck) {
      e.preventDefault();
      const formTask = btnCheck.closest("form");
      const li = formTask.querySelector(".currentTask");
      const taskId = formTask.querySelector("[name=taskId]").value;
      const isCompleted = li.classList.contains("done");

      const formData = new FormData();
      formData.append("taskId", taskId);
      formData.append("done", isCompleted ? "0" : "1");

      fetch("updateTaskStatus.php", { method: "POST", body: formData })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            li.classList.toggle("done", !isCompleted);
            formTask.classList.toggle("done", !isCompleted);
            // Met à jour la valeur pour le prochain clic
            formTask.querySelector("[name=doneTask]").value = !isCompleted
              ? "1"
              : "0";
            updateFinishButtons();
          } else {
            console.error(data.message);
          }
        })
        .catch((err) => console.error("Erreur réseau :", err));
    }

    // Delete
    if (btnDelete) {
      e.preventDefault();
      const formTask = btnDelete.closest("form");
      const taskId = formTask.querySelector("[name=deleteTask]").value;
      const card = btnDelete.closest(".cardProject");

      const formData = new FormData();
      formData.append("taskId", taskId);
      formData.append("deleteTask", "1");

      fetch("updateTaskStatus.php", { method: "POST", body: formData })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            // Supprime la tâche du DOM
            formTask.remove();
            updateFinishButtons();

            // Récupère l’ID du projet depuis le compteur
            const spanCount = card.querySelector("[id^='count_tasks_']");
            if (spanCount) {
              const projectId = spanCount.id.replace("count_tasks_", "");
              // Compte les tâches restantes dans ce projet
              const remainingTasks =
                card.querySelectorAll(".formProject.task").length;
              // Mets à jour le compteur avec la nouvelle valeur
              updateTaskCount(projectId, remainingTasks);
            }
          } else {
            console.error(data.message);
          }
        })
        .catch((err) => console.error("Erreur réseau :", err));
    }

    // Ajouter tâche
    if (btnAddTask) {
      e.preventDefault();
      const projetId = btnAddTask.dataset.projetId || "";
      const projetName = btnAddTask.dataset.projetName || "";
      const projetIdInput = document.getElementById("project_id");
      const projetNameTitle = document.getElementById("modalProjectName");
      const newProjectContainer = document.getElementById(
        "newProjectContainer"
      );

      if (!projetIdInput || !projetNameTitle || !newProjectContainer) return;

      if (projetId) {
        projetIdInput.value = projetId;
        projetNameTitle.textContent = projetName;
        projetNameTitle.style.display = "block";
        newProjectContainer.style.display = "none";
      } else {
        projetIdInput.value = "";
        projetNameTitle.style.display = "none";
        newProjectContainer.style.display = "block";
      }

      modal.style.display = "block";
    }
  });

  // Priorité
  container.addEventListener("change", (e) => {
    if (!e.target.classList.contains("priority-radio")) return;
    const taskId = e.target.dataset.taskId;
    const priority = parseInt(e.target.value, 10);
    const formTask = e.target.closest("form");
    if (!formTask) return;

    formTask.classList.remove(
      "bg-priority-red",
      "bg-priority-orange",
      "bg-priority-green"
    );
    if (priority === 1) formTask.classList.add("bg-priority-red");
    if (priority === 2) formTask.classList.add("bg-priority-orange");
    if (priority === 3) formTask.classList.add("bg-priority-green");

    fetch("update_priority.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ taskId, priority }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (!data.success) console.error(data.error || "Erreur inconnue");
      })
      .catch((err) => console.error("Erreur réseau :", err));
  });

  // Filtrage projets via fetch
  document.querySelectorAll(".btnCategory").forEach((btn) => {
    btn.addEventListener("click", () => {
      const category = btn.dataset.category;

      fetch("display_project_category.php", {
        method: "POST",
        body: new URLSearchParams({ category }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) renderProjects(data.projects);
        })
        .catch((err) => console.error("Erreur réseau :", err));
    });
  });

  // Fermeture modal
  closeModal.addEventListener("click", () => (modal.style.display = "none"));
  window.addEventListener("click", (e) => {
    if (e.target === modal) modal.style.display = "none";
  });

  // Soumission modal
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    fetch(form.action, { method: "POST", body: formData })
      .then(async (res) => {
        const text = await res.text();
        try {
          return JSON.parse(text);
        } catch {
          throw new Error("Réponse non-JSON : " + text);
        }
      })
      .then((data) => {
        modal.style.display = "none";

        if (data.success) {
          const projectId = data.task.Id_project;
          const spanCount = document.querySelector(`#count_tasks_${projectId}`);
          if (spanCount) {
            const currentCount = parseInt(spanCount.textContent, 10);
            updateTaskCount(projectId, currentCount + 1);
          }
        }

        const msg = document.createElement("div");
        msg.classList.add("message");
        msg.textContent = data.message || "Opération terminée.";
        if (!data.success) msg.classList.add("error");
        messageContainer.appendChild(msg);

        setTimeout(() => {
          msg.classList.add("hide");
          setTimeout(() => msg.remove(), 500);
        }, 5000);

        if (data.success) setTimeout(() => window.location.reload(), 1000);
      })
      .catch((err) => console.error("Erreur Fetch :", err));
  });
  // Applique la coloration dès le chargement initial
  colorDeadlines();
  updateFinishButtons();
});

const dashboard_btn = document.querySelector(".top__dashboard__btn");
const dashboard = document.querySelector(".dashboard");
const dashboard_close = document.getElementById("closeDashboard");
dashboard_btn.addEventListener("click", () => {
  dashboard.style.display =
    dashboard.style.display === "flex" ? "none" : "flex";
});
dashboard_close.addEventListener("click", () => {
  dashboard.style.display = "none";
});

//
// Gestion des cookies
// ===================
window.addEventListener("load", () => {
  if (!UserSession.getCookie("consent_cookies")) {
    const banner = document.createElement("div");
    banner.id = "consent-banner";
    banner.innerHTML = `
      <p>Nous utilisons des cookies pour améliorer votre expérience. En continuant, vous acceptez notre politique de confidentialité.</p>
      <button id="acceptCookies">Accepter</button>
    `;
    document.body.appendChild(banner);

    document.getElementById("acceptCookies").addEventListener("click", () => {
      UserSession.setCookie("consent_cookies", "yes", 365);
      banner.remove();
    });
  }
});
