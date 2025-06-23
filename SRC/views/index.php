<?php ob_start() ?>

<div class="top">
    <button type="submit" class="project">Nouveau projet/tâche </button>
</div>

<h2>🗂️ Mes projets en cours </h2>

<section>

    <div class="currentProject">
        <?php if (!empty($projets)) : ?>
            <?php foreach ($projets as $projet) : ?>
                <div class="cardProject">
                    <h3><?= htmlspecialchars($projet['project_name']) ?></h3>
                    <ul>
                        <?php if (!empty($projet['tasks'])) : ?>
                            <?php foreach ($projet['tasks'] as $task) : ?>
                                <form class="formProject task" method="post" onsubmit="return false;">
                                    <input type="hidden" name="currentTask" value="<?= htmlspecialchars($task['task']) ?>">
                                    <li class="currentTask <?= $task['done'] ? 'done' : '' ?>">
                                        <?= htmlspecialchars($task['task']) ?>
                                    </li>
                                    <button class="btnCheck" type="button"><i class="bi bi-check2-circle"></i></button>
                                </form>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li>⚠️ Aucune tâche pour ce projet</li>
                        <?php endif; ?>
                    </ul>
                    <form action="" method="GET">
                        <input type="hidden" name="deleteProject" value="<?= $projet['Id_project'] ?>">
                        <button id="finish">Terminé !</button>
                    </form>
                </div>
            <?php endforeach; ?>

        <?php else : ?>
            <p>Aucun projet pour le moment.</p>
        <?php endif; ?>
    </div>

</section>

<?php
render('default', true, [
    'title' => "Ma liste de chauses à faire",
    'content' => ob_get_clean()
]) ?>