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

                                <?php
                                $priorityClass = match ($task['priority']) {
                                    1 => 'bg-priority-red',
                                    2 => 'bg-priority-orange',
                                    3 => 'bg-priority-green',
                                    default => '',
                                };
                                ?>

                                <form class="formProject task <?= $priorityClass ?>" method="POST" onsubmit="return false;">
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

                                    <button class="btnCheck" type="button"><i class="bi bi-check2-circle"></i></button>

                                    <input type="hidden" name="deleteTask" value="<?= $task['Id_tasks'] ?>">
                                    <button class="btnDelete"><i class="bi bi-x"></i></button>

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
    // 'js' => 'assets/js/index.js',
    'content' => ob_get_clean()
]) ?>