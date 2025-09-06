<?php ob_start() ?>

<section>

    <!-- Création d'un nouveau projet -->
    <div class="container_new_project">
        <h2>Créer un nouveau projet</h2>

        <form method="POST" action="">
            <input type="text" name="nom_projet" placeholder="Nom du projet" required>

            <div class="container_new_project__priority_radio">

                <div class="container_new_project__priority_radio__items">
                    <input type="radio" id="priority_normal" name="priority_project" value="normal" checked>
                    <label for="priority_normal">Normal</label>
                </div>

                <div class="container_new_project__priority_radio__items">

                    <input type="radio" id="priority_medium" name="priority_project" value="medium">
                    <label for="priority_medium">Moyen</label>
                </div>

                <div class="container_new_project__priority_radio__items">
                    <input type="radio" id="priority_urgent" name="priority_project" value="urgent">
                    <label for="priority_urgent">Urgent</label>
                </div>

            </div>

            <button type="submit" name="create_project">Créer le projet</button>
        </form>

        <?php if (isset($message_project)): ?>
            <div class="popup-message"><?= htmlspecialchars($message_project) ?></div>
        <?php endif; ?>

    </div>

    <!-- Ajouter une tâche si des projets existent -->
    <?php if (isset($projets) && count($projets) > 0): ?>
        <div class="container_choice">
            <h2>Ajouter une tâche</h2>

            <form method="POST" action="" class="task-form">
                <select name="projet_id" required>
                    <option value="">-- Choisir un projet --</option>
                    <?php foreach ($projets as $projet): ?>
                        <option value="<?= htmlspecialchars($projet['Id_project']) ?>">
                            <?= htmlspecialchars($projet['project_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="description"></label>
                <input type="text" id="description" name="description" placeholder="Description de la tâche" required>

                <div class="task_form__priority">

                    <div class="task_form__priority__items">
                        <input type="radio" id="task_low" name="priority_task" value="3" checked>
                        <label for="task_low">Faible</label>
                    </div>

                    <div class="task_form__priority__items">
                        <input type="radio" id="task_medium" name="priority_task" value="2">
                        <label for="task_medium">Moyenne</label>
                    </div>

                    <div class="task_form__priority__items">
                        <input type="radio" id="task_high" name="priority_task" value="1">
                        <label for="task_high">Haute</label>
                    </div>

                    <label for="deadline">Date butoire :</label>
                    <input type="date" id="deadline" name="deadline">

                </div>

                <button type="submit" name="add_task">Ajouter la tâche</button>
                <a href="/" class="btn-finish">Terminé</a>
            </form>

            <?php if (isset($message_task)): ?>
                <div class="popup-message"><?= htmlspecialchars($message_task) ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</section>

<?php
render('default', true, [
    'title' => 'Nouveau projet',
    'js' => 'assets/js/createProjet.js',
    'style' => 'assets/css/createProjet.css',
    'content' => ob_get_clean(),
]);
?>