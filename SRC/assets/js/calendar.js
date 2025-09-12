document.addEventListener("DOMContentLoaded", () => {
  const calendarContainer = document.getElementById("calendar-container");
  const currentMonthLabel = document.getElementById("current-month");
  const prevBtn = document.getElementById("prev-month");
  const nextBtn = document.getElementById("next-month");

  const modal = document.getElementById("modalTask");
  const closeBtn = modal.querySelector(".close");
  const form = document.getElementById("formAddTask");
  const deadlineInput = document.getElementById("deadline");
  const projetIdInput = document.getElementById("project_id");
  const projetNameTitle = document.getElementById("modalProjectName");
  const newProjectContainer = document.getElementById("newProjectContainer");

  let currentDate = new Date();
  let tasks = [];

  //
  // Fetch les projets et tâches
  //============================
  fetch("/controller_calendar_json.php")
    .then((res) => res.json())
    .then((projects) => {
      projects.forEach((proj) => {
        if (proj.tasks && proj.tasks.length > 0) {
          proj.tasks.forEach((task) => {
            if (task.deadline) {
              tasks.push({
                ...task,
                project_name: proj.project_name,
              });
            }
          });
        }
      });

      renderCalendar(currentDate);
    })
    .catch((err) => console.error("Erreur fetch:", err));

  //
  // Fonction pour générer le calendrier
  //====================================
  function renderCalendar(date) {
    calendarContainer.innerHTML = "";

    const year = date.getFullYear();
    const month = date.getMonth();
    currentMonthLabel.textContent = `${date.toLocaleString("fr-FR", {
      month: "long",
    })} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    const weekdays = ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"];
    const table = document.createElement("table");
    table.classList.add("calendar-table");

    // ===== En-tête =====
    const thead = document.createElement("thead");
    const trHead = document.createElement("tr");
    weekdays.forEach((day) => {
      const th = document.createElement("th");
      th.textContent = day;
      trHead.appendChild(th);
    });
    thead.appendChild(trHead);
    table.appendChild(thead);

    // ===== Corps du calendrier =====
    const tbody = document.createElement("tbody");
    let dayCounter = 1;

    for (let i = 0; i < 6; i++) {
      const tr = document.createElement("tr");

      for (let j = 0; j < 7; j++) {
        const td = document.createElement("td");

        if ((i === 0 && j < firstDay) || dayCounter > lastDate) {
          td.textContent = "";
          td.className = "empty_day";
        } else {
          td.dataset.day = dayCounter;
          td.dataset.month = month + 1;
          td.dataset.year = year;
          td.innerHTML = `<div class="day-number">${dayCounter}</div>`;

          // ===== Drop events =====
          td.addEventListener("dragover", (e) => e.preventDefault());
          td.addEventListener("drop", (e) => {
            e.preventDefault();
            const taskId = e.dataTransfer.getData("text/plain");
            const taskDiv = calendarContainer.querySelector(
              `.calendar-task[data-task-id='${taskId}']`
            );
            if (!taskDiv) return;

            td.appendChild(taskDiv);

            // Mise à jour BDD
            const newDate = `${td.dataset.year}-${String(
              td.dataset.month
            ).padStart(2, "0")}-${String(td.dataset.day).padStart(2, "0")}`;
            const formData = new FormData();
            formData.append("taskId", taskId);
            formData.append("deadline", newDate);

            fetch("updateTaskDeadline.php", { method: "POST", body: formData })
              .then((res) => res.json())
              .then((data) => {
                if (!data.success) console.error(data.message);
              })
              .catch((err) => console.error("Erreur réseau :", err));
          });

          // ===== Tâches du jour =====
          const tasksOfTheDay = tasks.filter((task) => {
            const taskDate = new Date(task.deadline);
            return (
              taskDate.getFullYear() === year &&
              taskDate.getMonth() === month &&
              taskDate.getDate() === dayCounter
            );
          });

          tasksOfTheDay.forEach((task) => {
            const divTask = document.createElement("div");
            divTask.classList.add("calendar-task");
            divTask.setAttribute("draggable", "true");
            divTask.dataset.taskId = task.Id_tasks;

            // Texte de la tâche
            const spanText = document.createElement("span");
            spanText.textContent = task.task;
            divTask.appendChild(spanText);

            // Priorité et état
            if (task.priority_task === 1) divTask.classList.add("priority-red");
            if (task.priority_task === 2)
              divTask.classList.add("priority-orange");
            if (task.priority_task === 3)
              divTask.classList.add("priority-green");
            if (task.done) divTask.classList.add("done");

            // ===== Drag start =====
            divTask.addEventListener("dragstart", (e) => {
              e.dataTransfer.setData("text/plain", task.Id_tasks);
            });

            // ===== Bouton supprimer =====
            const btnDelete = document.createElement("button");
            btnDelete.innerHTML = "&times;";
            btnDelete.classList.add("btn-delete-task");
            btnDelete.title = "Supprimer la tâche";

            btnDelete.addEventListener("click", (e) => {
              e.stopPropagation(); // empêche le drag
              const taskId = task.Id_tasks;

              const formData = new FormData();
              formData.append("taskId", taskId);
              formData.append("deleteTask", 1);

              fetch("updateTaskStatus.php", {
                method: "POST",
                body: formData,
              })
                .then((res) => res.json())
                .then((data) => {
                  if (data.success) divTask.remove();
                  else console.error(data.message || "Erreur suppression");
                })
                .catch((err) => console.error("Erreur réseau :", err));
            });

            divTask.appendChild(btnDelete);
            td.appendChild(divTask);
          });

          dayCounter++;
        }

        tr.appendChild(td);
      }

      tbody.appendChild(tr);
    }

    table.appendChild(tbody);
    calendarContainer.appendChild(table);
  }

  // ===== Navigation des mois =====
  prevBtn.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
  });

  nextBtn.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
  });

  // ===== Modal pour ajouter une tâche (délégation) =====
  calendarContainer.addEventListener("click", (e) => {
    const td = e.target.closest("td[data-day]");
    if (!td) return;

    const day = td.dataset.day;
    const month = td.dataset.month;
    const year = td.dataset.year;

    // Préremplir la date
    const dateStr = `${year}-${month.toString().padStart(2, "0")}-${day
      .toString()
      .padStart(2, "0")}`;
    deadlineInput.value = dateStr;

    // Afficher le modal avec option de créer un nouveau projet
    projetIdInput.value = "";
    projetNameTitle.style.display = "none";
    newProjectContainer.style.display = "block";

    modal.style.display = "block";
  });

  // Fermer le modal
  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
  form.addEventListener("submit", (e) => {
    e.preventDefault(); // empêche le rechargement

    const formData = new FormData(form);

    fetch("create_task.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          modal.style.display = "none";

          // Formater la date au format YYYY-MM-DD pour la comparer correctement
          if (data.task.deadline) {
            const deadlineDate = new Date(data.task.deadline);
            const yyyy = deadlineDate.getFullYear();
            const mm = String(deadlineDate.getMonth() + 1).padStart(2, "0");
            const dd = String(deadlineDate.getDate()).padStart(2, "0");
            data.task.deadline = `${yyyy}-${mm}-${dd}`;
          }

          // Ajouter la tâche au tableau local
          tasks.push(data.task);

          // Identifier le td correspondant à la date de la tâche et injecter la div
          if (data.task.deadline) {
            const taskDate = new Date(data.task.deadline);
            const td = calendarContainer.querySelector(
              `td[data-day='${taskDate.getDate()}'][data-month='${
                taskDate.getMonth() + 1
              }'][data-year='${taskDate.getFullYear()}']`
            );

            if (td) {
              const divTask = document.createElement("div");
              divTask.classList.add("calendar-task");
              divTask.setAttribute("draggable", "true");
              divTask.dataset.taskId = data.task.Id_tasks;

              // Texte de la tâche
              const spanText = document.createElement("span");
              spanText.textContent = data.task.task;
              divTask.appendChild(spanText);

              // Priorité
              if (data.task.priority_task == 1)
                divTask.classList.add("priority-red");
              if (data.task.priority_task == 2)
                divTask.classList.add("priority-orange");
              if (data.task.priority_task == 3)
                divTask.classList.add("priority-green");

              // État done
              if (data.task.done) divTask.classList.add("done");

              // Bouton supprimer
              const btnDelete = document.createElement("button");
              btnDelete.innerHTML = "&times;";
              btnDelete.classList.add("btn-delete-task");
              btnDelete.title = "Supprimer la tâche";
              btnDelete.addEventListener("click", (e) => {
                e.stopPropagation();
                const taskId = data.task.Id_tasks;
                const formDataDelete = new FormData();
                formDataDelete.append("taskId", taskId);
                formDataDelete.append("deleteTask", 1);

                fetch("updateTaskStatus.php", {
                  method: "POST",
                  body: formDataDelete,
                })
                  .then((res) => res.json())
                  .then((resDelete) => {
                    if (resDelete.success) divTask.remove();
                    else
                      console.error(resDelete.message || "Erreur suppression");
                  })
                  .catch((err) => console.error("Erreur réseau :", err));
              });

              divTask.appendChild(btnDelete);
              td.appendChild(divTask);
            }
          }
        } else {
          alert(data.message || "Erreur lors de l’ajout de la tâche");
        }
      })
      .catch((err) => console.error("Erreur réseau :", err));
  });
});

// Faire disparaître le message d'alerte après 3 secondes
document.addEventListener("DOMContentLoaded", () => {
  const successMsg = document.getElementById("successMessage");
  if (successMsg) {
    setTimeout(() => {
      successMsg.classList.add("hide");
    }, 3000); // 3000 ms = 3 secondes
  }
});

//
// ===== Événements drag =====
//
// ===== Variables pour drag & swipe =====
let dragStartX = 0;
let pointerStartX = 0;
let pointerStartY = 0;
const swipeThreshold = 100; // pixels minimum pour considérer un swipe
const verticalLimit = 50; // tolérance verticale pour ne pas confondre avec scroll

// ===== Événements drag =====
calendarContainer.addEventListener("dragstart", (e) => {
  const taskDiv = e.target.closest(".calendar-task");
  if (!taskDiv) return;

  dragStartX = e.clientX; // position initiale pour swipe
  e.dataTransfer.setData("text/plain", taskDiv.dataset.taskId);
  taskDiv.classList.add("dragging");
});

calendarContainer.addEventListener("dragend", (e) => {
  const taskDiv = e.target.closest(".calendar-task");
  if (taskDiv) taskDiv.classList.remove("dragging");

  const diffX = e.clientX - dragStartX;
  if (diffX > swipeThreshold) {
    prevMonth(); // drag vers la droite → mois précédent
  } else if (diffX < -swipeThreshold) {
    nextMonth(); // drag vers la gauche → mois suivant
  }
});

// ===== Autoriser le drop sur les cases =====
calendarContainer.addEventListener("dragover", (e) => {
  const td = e.target.closest("td[data-day]");
  if (td) e.preventDefault();
});

// ===== Drop =====
calendarContainer.addEventListener("drop", (e) => {
  e.preventDefault();
  const td = e.target.closest("td[data-day]");
  if (!td) return;

  const taskId = e.dataTransfer.getData("text/plain");
  const newDate = `${td.dataset.year}-${String(td.dataset.month).padStart(
    2,
    "0"
  )}-${String(td.dataset.day).padStart(2, "0")}`;

  // Déplacer visuellement
  const taskDiv = calendarContainer.querySelector(
    `.calendar-task[data-task-id='${taskId}']`
  );
  if (taskDiv) td.appendChild(taskDiv);

  // Envoyer la mise à jour à la BDD
  const formData = new FormData();
  formData.append("taskId", taskId);
  formData.append("deadline", newDate);

  fetch("updateTaskDeadline.php", { method: "POST", body: formData })
    .then((res) => res.json())
    .then((data) => {
      if (!data.success) console.error(data.message);
    })
    .catch((err) => console.error("Erreur réseau :", err));
});

// ===== Swipe / pointer horizontal =====
calendarContainer.addEventListener("pointerdown", (e) => {
  if (e.button !== 0) return; // gauche uniquement
  pointerStartX = e.clientX;
  pointerStartY = e.clientY;
});

calendarContainer.addEventListener("pointerup", (e) => {
  const diffX = e.clientX - pointerStartX;
  const diffY = e.clientY - pointerStartY;

  // Vérifier que le swipe est horizontal et suffisamment large
  if (Math.abs(diffX) > swipeThreshold && Math.abs(diffY) < verticalLimit) {
    if (diffX > 0) {
      prevMonth(); // swipe vers la droite
    } else {
      nextMonth(); // swipe vers la gauche
    }
  }
});
