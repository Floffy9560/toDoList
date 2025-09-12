<!-- Modal pour ajouter une tâche -->
<div id="modalTask" class="modal">
      <div class="modal-content">

            <span class="close">&times;</span>

            <form id="formAddTask" method="post" action="create_task.php">

                  <h2 id="modalProjectName"></h2>

                  <!-- Champ caché pour un projet existant -->
                  <input type="hidden" name="project_id" id="project_id">

                  <!-- Champ pour un nouveau projet (masqué par défaut) -->
                  <div id="newProjectContainer" style="display:none;">

                        <strong>Utilise un projet existant : </strong>
                        <select name="project_id_select" id="project_id_select">
                              <option value="">Choisis un projet :</option>
                              <?php foreach ($projects as $projet): ?>
                                    <option value="<?= $projet['Id_project'] ?>">
                                          <?= htmlspecialchars($projet['project_name']) ?>
                                    </option>
                              <?php endforeach ?>
                        </select>

                        <strong>Ou crée un autre : </strong>
                        <input type="text" name="new_project" id="new_project" placeholder="Nom du projet :">

                        <label for="priority_project">Priorité du projet :</label>
                        <select name="priority_project" id="priority_project" required>
                              <option value="urgent">Urgent</option>
                              <option value="medium">Moyen</option>
                              <option value="normal" selected>Normal</option>
                        </select>

                  </div>

                  <label for="task_name">Nom de la tâche :</label>
                  <input type="text" name="task_name" id="task_name" required>

                  <label for="priority">Priorité :</label>
                  <select name="priority_task" id="priority" required>
                        <option value="1">Urgent</option>
                        <option value="2">Moyen</option>
                        <option value="3" selected>Normal</option>
                  </select>

                  <label for="deadline">Date butoir :</label>
                  <input type="date" id="deadline" name="deadline">

                  <button type="submit">Ajouter</button>

            </form>
      </div>
</div>