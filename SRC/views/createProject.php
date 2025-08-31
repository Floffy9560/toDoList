<?php ob_start() ?>

<section>

    <div class="container_choice">
        <h2>Créer un nouveau projet</h2>

        <form method="POST" action="">
            <input type="text" name="nom_projet" placeholder="Nom du projet" required>
            <button type="submit" name="create_project">Créer le projet</button>
        </form>

    </div>
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

                <input type="text" name="description" placeholder="Description de la tâche" required>
                <div class="priority">
                    <label for="priority">Priorité :</label>
                    <input type="radio" name="priority" value="3" checked> Faible
                    <input type="radio" name="priority" value="2"> Moyenne
                    <input type="radio" name="priority" value="1"> Haute
                </div>

                <button type="submit" name="add_task">Ajouter la tâche</button>
                <button><a href="/">Terminé</a></button>

            </form>

        <?php endif; ?>
        </div>
</section>

<?php
render('default', true, [
    'title' => 'Nouveau projet',
    'js' => 'assets/js/createProjet.js',
    'style' => 'assets/css/createProjet.css',
    'content' => ob_get_clean(),
]) ?>