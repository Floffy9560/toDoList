window.addEventListener("DOMContentLoaded", () => {
  const container = document.querySelector("#projects-container");
  console.log(container);

  // ===== Bouton "Nouveau projet/tâche" =====
  document
    .querySelector(".btn_create_project")
    ?.addEventListener("click", (e) => {
      e.preventDefault();
      window.location.href = "createProject";
    });

  // ===== Gestion des boutons "Check" et "Delete" via délégation =====
  container.addEventListener("click", (e) => {
    const btnCheck = e.target.closest(".btnCheck");
    const btnDelete = e.target.closest(".btnDelete");

    if (btnCheck) {
      e.preventDefault();
      const form = btnCheck.closest("form");
      const li = form.querySelector(".currentTask");

      const taskId = form.querySelector("[name=doneTask]").value; // récupérer la valeur
      const isCompleted = li.style.textDecoration === "line-through";

      // Construire manuellement le FormData
      const formData = new FormData();
      formData.append("taskId", taskId);
      formData.append("done", isCompleted ? "0" : "1"); // état à envoyer

      fetch("updateTaskStatus.php", { method: "POST", body: formData })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            li.style.textDecoration = isCompleted ? "none" : "line-through";
            btnCheck.style.color = isCompleted ? "" : "green";
            btnCheck.style.scale = isCompleted ? "1" : "1.2";

            // Met à jour le bouton "Terminé"
            const card = form.closest(".cardProject");
            const allDone = Array.from(
              card.querySelectorAll(".currentTask")
            ).every((item) => item.style.textDecoration === "line-through");
            const finishBtn = card.querySelector("#finish");
            if (finishBtn) finishBtn.disabled = !allDone;
          } else console.error(data.message);
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

    // ===== Gestion du menu burger =====
    document.getElementById("toggleMenu")?.addEventListener("click", () => {
      const ulNav = document.getElementById("ulNav");
      ulNav.style.display = ulNav.style.display === "flex" ? "none" : "flex";
    });

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

          const inputHidden = document.createElement("input");
          inputHidden.type = "hidden";
          inputHidden.name = "currentTask";
          inputHidden.value = task.task;
          form.appendChild(inputHidden);

          const li = document.createElement("li");
          li.className = "currentTask" + (task.done ? " done" : "");
          li.textContent = task.task;

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

          // Boutons
          const btnDiv = document.createElement("div");
          btnDiv.classList.add("formProject__btn_action");

          // Input et bouton Check
          const inputDone = document.createElement("input");
          inputDone.type = "hidden";
          inputDone.name = "doneTask";
          inputDone.value = task.Id_tasks;

          const btnCheck = document.createElement("button");
          btnCheck.type = "button";
          btnCheck.classList.add("btnCheck");
          btnCheck.innerHTML = '<i class="bi bi-check2-circle"></i>';

          // Input et bouton Delete
          const inputDelete = document.createElement("input");
          inputDelete.type = "hidden";
          inputDelete.name = "deleteTask";
          inputDelete.value = task.Id_tasks;

          const btnDelete = document.createElement("button");
          btnDelete.type = "button";
          btnDelete.classList.add("btnDelete");
          btnDelete.innerHTML = '<i class="bi bi-x"></i>';

          form.appendChild(inputDone);
          form.appendChild(inputDelete);
          btnDiv.append(btnCheck, btnDelete);
          form.appendChild(btnDiv);

          ul.appendChild(form);
        });
      } else {
        const li = document.createElement("li");
        li.textContent = "⚠️ Aucune tâche pour ce projet";
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
      finishForm.appendChild(finishBtn);
      finishBtn.disabled = !(projet.tasks && projet.tasks.every((t) => t.done));
      card.appendChild(finishForm);

      container.appendChild(card);
    });
  }

  // ===== Filtrage des projets =====
  //   document.querySelectorAll(".project_category__form").forEach((form) => {
  //     form.addEventListener("click", (e) => {
  //       e.preventDefault();
  //       const priority = form.querySelector("[name=category]").value;

  //       fetch("display_project_category.php", {
  //         method: "POST",
  //         headers: { "Content-Type": "application/json" },
  //         body: JSON.stringify({ priority }),
  //       })
  //         .then((res) => res.json())
  //         .then((data) => {
  //           if (data.success) renderProjects(data.projects);
  //           else console.error(data.message);
  //         })
  //         .catch((err) => console.error("Erreur réseau :", err));
  //     });
  //   });
  // });
  document.querySelectorAll(".project_category__form").forEach((form) => {
    const btn = form.querySelector(".btnCategory");
    btn.addEventListener("click", (e) => {
      e.preventDefault(); // pour éviter un éventuel submit classique
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
console.log(taks);
taks.forEach((task) => {
  const btn_finish = task.querySelector("#finish");
  const li = task.querySelector("ul li").textContent;
  console.log(li);

  if (li === "Aucune tâche pour ce projet" || li === "") {
    btn_finish.disabled = false;
  }
});
