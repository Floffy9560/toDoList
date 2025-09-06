document.addEventListener("DOMContentLoaded", () => {
  const calendarContainer = document.getElementById("calendar-container");
  const currentMonthLabel = document.getElementById("current-month");
  const prevBtn = document.getElementById("prev-month");
  const nextBtn = document.getElementById("next-month");

  const modal = document.getElementById("modalTask");
  const closeBtn = modal.querySelector(".close");
  const form = document.getElementById("formAddTask");
  const deadlineInput = document.getElementById("deadline");
  const projetIdInput = document.getElementById("projet_id");
  const projetNameTitle = document.getElementById("modalProjectName");
  const newProjectContainer = document.getElementById("newProjectContainer");

  let currentDate = new Date();
  let tasks = [];

  // ===== Fetch les projets et tâches =====
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

  // ===== Fonction pour générer le calendrier =====
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

    // Entête jours
    const thead = document.createElement("thead");
    const trHead = document.createElement("tr");
    weekdays.forEach((day) => {
      const th = document.createElement("th");
      th.textContent = day;
      trHead.appendChild(th);
    });
    thead.appendChild(trHead);
    table.appendChild(thead);

    // Corps du calendrier
    const tbody = document.createElement("tbody");
    let dayCounter = 1;

    for (let i = 0; i < 6; i++) {
      const tr = document.createElement("tr");
      for (let j = 0; j < 7; j++) {
        const td = document.createElement("td");

        if (i === 0 && j < firstDay) {
          td.textContent = "";
          td.className = "empty_day";
        } else if (dayCounter > lastDate) {
          td.textContent = "";
          td.className = "empty_day";
        } else {
          td.dataset.day = dayCounter;
          td.dataset.month = month + 1;
          td.dataset.year = year;
          td.innerHTML = `<div class="day-number">${dayCounter}</div>`;

          // Ajouter les tâches de ce jour
          tasks
            .filter((task) => {
              const taskDate = new Date(task.deadline);
              return (
                taskDate.getFullYear() === year &&
                taskDate.getMonth() === month &&
                taskDate.getDate() === dayCounter
              );
            })
            .forEach((task) => {
              const divTask = document.createElement("div");
              divTask.textContent = task.task;
              divTask.classList.add("calendar-task");

              if (task.priority === 1) divTask.classList.add("priority-red");
              if (task.priority === 2) divTask.classList.add("priority-orange");
              if (task.priority === 3) divTask.classList.add("priority-green");

              if (task.done) divTask.classList.add("done");

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
