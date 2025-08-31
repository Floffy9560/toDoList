<?php ob_start() ?>

<div class="top">
    <button type="submit" class="btn_create_project">Nouveau projet/tâche </button>
</div>

<h2>🗂️ Mes projets en cours : </h2>

<div class="project_category">

    <form action="" class="project_category__form" method="POST">
        <input type="hidden" name="category" value="all">
        <button type="button" class="btnCategory" data-category="all">Tout</button>
    </form>

    <form action="" class="project_category__form">
        <input type="hidden" name="category" value="urgent">
        <button type="button" class="btnCategory" data-category="urgent">Urgent</button>
    </form>

    <form action="" class="project_category__form">
        <input type="hidden" name="category" value="medium">
        <button type="button" class="btnCategory" data-category="medium">Moyen</button>
    </form>

    <form action="" class="project_category__form">
        <input type="hidden" name="category" value="normal">
        <button type="button" class="btnCategory" data-category="normal">Normal</button>
    </form>

</div>

<section>

    <div id="projects-container" class="currentProject">
        <?php if (!empty($projets)) : ?>
            <?php foreach ($projets as $projet) : ?>

                <div class="cardProject">
                    <h3><?= htmlspecialchars($projet['project_name']) ?></h3>

                    <ul>
                        <?php if (!empty($projet['tasks'])) : ?>
                            <?php foreach ($projet['tasks'] as $task) : ?>

                                <?php
                                $priorityClass = match ($task['priority']) {
                                    1 => 'bg-priority-red',
                                    2 => 'bg-priority-orange',
                                    3 => 'bg-priority-green',
                                };
                                ?>

                                <form class="formProject task <?= $priorityClass ?>" method="POST">

                                    <input type="hidden" name="currentTask" value="<?= htmlspecialchars($task['task']) ?>">

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

                                    <div class="formProject__btn_action">

                                        <input type="hidden" name="doneTask" value="<?= $task['done'] ?>">
                                        <button type="submit" class="btnCheck"><i class="bi bi-check2-circle"></i></button>

                                        <input type="hidden" name="deleteTask" value="<?= $task['Id_tasks'] ?>">
                                        <button type="submit" class="btnDelete"><i class="bi bi-x"></i></button>

                                    </div>

                                </form>

                            <?php endforeach; ?>
                        <?php else : ?>
                            <li>Aucune tâche pour ce projet</li>
                        <?php endif; ?>

                    </ul>

                    <form action="" method="GET">
                        <input type="hidden" name="deleteProject" value="<?= $projet['Id_project'] ?>">
                        <button id="finish" disabled>Terminé !</button>
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
    // 'js' => 'assets/js/index.js',
    'content' => ob_get_clean()
]) ?>