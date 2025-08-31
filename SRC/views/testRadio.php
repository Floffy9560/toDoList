<li class="currentTask <?= $task['done'] ? 'done' : '' ?> ">
      <?= htmlspecialchars($task['task']) ?>

      <div class="priority-group">
            <?php for ($i = 1; $i <= 3; $i++) :
                  $labelClass = match ($i) {
                        1 => 'priority-red',
                        2 => 'priority-orange',
                        3 => 'priority-green',
                  };
            ?>
                  <label class="<?= $labelClass ?>" style="margin-right: 8px;">
                        <input
                              type="radio"
                              name="priority_<?= (int) $task['Id_tasks'] ?>"
                              value="<?= $i ?>"
                              class="priority-radio"
                              data-task-id="<?= (int) $task['Id_tasks'] ?>"
                              <?= ($task['priority'] == $i) ? 'checked' : '' ?>>
                        <? if ($i === 1) {
                              echo 'Urgent';
                        } elseif ($i === 2) {
                              echo 'Important';
                        } else {
                              echo 'Normal';
                        } ?>
                  </label>

            <?php endfor; ?>
      </div>
</li>


{# // script js pour changer la priorité d'une tâche
      
  document.querySelectorAll(".project_category__form").forEach((form) => {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const priority = form.querySelector("[name=category]").value;

      fetch("display_project_category.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ priority }),
      })
        .then((res) => res.text()) // ⚠️ récupère le texte brut
        .then((text) => {
          console.log("Réponse brute du serveur :", text); // ici tu vois exactement ce que PHP renvoie
          try {
            const data = JSON.parse(text); // on essaie de parser en JSON
            console.log("JSON parsé :", data);
          } catch (err) {
            console.error("Erreur parsing JSON :", err);
          }
        })
        .catch((err) => console.error("Erreur réseau :", err));
    });
  }); #}

{# // Fonction pour générer les projets à partir du JSON
  // function renderProjects(projects) {
  //   console.log("Rendu des projets :", projects);

  //   container.innerHTML = "";

  //   projects.forEach((projet) => {
  //     const projectDiv = document.createElement("div");
  //     projectDiv.classList.add("cardProject");

  //     const h3 = document.createElement("h3");
  //     h3.textContent = projet.project_name;
  //     projectDiv.appendChild(h3);

  //     const ul = document.createElement("ul");

  //     if (projet.tasks && projet.tasks.length > 0) {
  //       projet.tasks.forEach((task) => {
  //         const form = document.createElement("form");
  //         form.classList.add("formProject", "task");
  //         form.setAttribute("onsubmit", "return false;");

  //         // Classe priorité
  //         let priorityClass = "";
  //         switch (task.priority) {
  //           case 1:
  //             priorityClass = "bg-priority-red";
  //             break;
  //           case 2:
  //             priorityClass = "bg-priority-orange";
  //             break;
  //           case 3:
  //             priorityClass = "bg-priority-green";
  //             break;
  //         }
  //         form.classList.add(priorityClass);

  //         // Input caché pour la tâche
  //         const hiddenInput = document.createElement("input");
  //         hiddenInput.type = "hidden";
  //         hiddenInput.name = "currentTask";
  //         hiddenInput.value = task.task;
  //         form.appendChild(hiddenInput);

  //         // Li tâche
  //         const li = document.createElement("li");
  //         li.classList.add("currentTask");
  //         if (task.done) li.classList.add("done");
  //         li.textContent = task.task;

  //         // Groupe de priorité
  //         const divPriority = document.createElement("div");
  //         divPriority.classList.add("priority-group");

  //         for (let i = 1; i <= 3; i++) {
  //           const label = document.createElement("label");
  //           label.classList.add(
  //             i === 1
  //               ? "priority-red"
  //               : i === 2
  //               ? "priority-orange"
  //               : "priority-green"
  //           );
  //           label.style.marginRight = "8px";

  //           const radio = document.createElement("input");
  //           radio.type = "radio";
  //           radio.name = `priority_${task.Id_tasks}`;
  //           radio.value = i;
  //           radio.classList.add("priority-radio");
  //           radio.dataset.taskId = task.Id_tasks;
  //           if (task.priority == i) radio.checked = true;

  //           label.appendChild(radio);
  //           label.append(
  //             ` ${i === 1 ? "Urgent" : i === 2 ? "Important" : "Normal"}`
  //           );
  //           divPriority.appendChild(label);
  //         }

  //         li.appendChild(divPriority);
  //         form.appendChild(li);

  //         // Boutons d’action
  //         const btnDiv = document.createElement("div");
  //         btnDiv.classList.add("formProject__btn_action");

  //         const btnCheck = document.createElement("button");
  //         btnCheck.type = "button";
  //         btnCheck.classList.add("btnCheck");
  //         btnCheck.innerHTML = '<i class="bi bi-check2-circle"></i>';
  //         btnDiv.appendChild(btnCheck);

  //         const deleteInput = document.createElement("input");
  //         deleteInput.type = "hidden";
  //         deleteInput.name = "deleteTask";
  //         deleteInput.value = task.Id_tasks;
  //         btnDiv.appendChild(deleteInput);

  //         const btnDelete = document.createElement("button");
  //         btnDelete.classList.add("btnDelete");
  //         btnDelete.innerHTML = '<i class="bi bi-x"></i>';
  //         btnDiv.appendChild(btnDelete);

  //         form.appendChild(btnDiv);
  //         ul.appendChild(form);
  //       });
  //     } else {
  //       const li = document.createElement("li");
  //       li.textContent = "⚠️ Aucune tâche pour ce projet";
  //       ul.appendChild(li);
  //     }

  //     projectDiv.appendChild(ul);
  //     container.appendChild(projectDiv);
  //   });
  // }
  // } #}

{# // const category_form = document.querySelectorAll(".project_category__form");

  // category_form.forEach((form) => {
  //   form.addEventListener("submit", (e) => {
  //     e.preventDefault();
  //     const priority = form.querySelector("[name=category]").value;

  //     fetch("display_project_category.php", {
  //       method: "POST",
  //       headers: { "Content-Type": "application/json" },
  //       body: JSON.stringify({ priority }),
  //     })
  //       .then((res) => res.json())
  //       .then((data) => {
  //         if (data.success) {
  //           console.log("Catégorie mise à jour avec succès", data.projects);

  //           // Exemple : vider la liste et recréer les projets
  //           const container = document.querySelector(".currentProject");
  //           container.innerHTML = "";
  //           data.projects.forEach((p) => {
  //             const div = document.createElement("div");
  //             div.innerHTML =

  //             container.appendChild(div);
  //           });
  //         } else {
  //           console.error(
  //             "Erreur serveur :",
  //             data.message || "Réponse invalide"
  //           );
  //         }
  //       })
  //       .catch((err) => {
  //         console.error("Erreur requête :", err);
  //       });
  //   });
  // }); #}