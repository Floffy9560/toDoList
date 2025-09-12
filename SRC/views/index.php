<?php ob_start() ?>

<!-- Dashboard -->
<aside class="dashboard">

    <button id="closeDashboard"><i class="bi bi-x-lg"></i></button>

    <h2>Dashboard</h2>

    <h3>🗂️ Projets</h3>

    <div class="dashboard__type_project">
        <?php if (isset($projets) && count($projets) > 0): ?>
            <ul class="dashboard__type_project__list">
                <?php foreach ($projets as $projet): ?>

                    <li class="dashboard__type_project__list__item">

                        <?= "# " .  htmlspecialchars($projet['project_name']) ?>
                        <a href="#"
                            class="aside__btn_create_task"
                            title="Créer une nouvelle tâche"
                            data-projet-id="<?= (int) $projet['Id_project'] ?>">
                            <i class="bi bi-clipboard-plus"></i>
                        </a>

                    </li>

                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun projet pour le moment</p>
        <?php endif; ?>
    </div>

    <a href="createProject" class="btn_create_project" title="Créer un nouveau projet"><i class="bi bi-folder-plus"></i></a>


</aside>

<div class="display">

    <div class="top">

        <div class="top__dashboard">
            <button class="top__dashboard__btn">Dashboard</button>
            <a href="createProject" class="btn_create_project" title="Créer un nouveau projet"> <i class="bi bi-folder-plus"></i></a>
        </div>

        <div class="top__project_category">

            <button type="button" class="btnCategory" data-category="all">Tout</button>
            <button type="button" class="btnCategory" data-category="urgent">Urgent</button>
            <button type="button" class="btnCategory" data-category="medium">Moyen</button>
            <button type="button" class="btnCategory" data-category="normal">Normal</button>

        </div>

    </div>



    <section>

        <div id="projects-container" class="currentProject">
            <?php if (!empty($projets)) : ?>
                <?php foreach ($projets as $projet) : ?>

                    <div class="cardProject">

                        <div class="cardProject__countTasks">
                            <h3><?= htmlspecialchars($projet['project_name']) ?></h3>
                            <span id="count_tasks_<?= $projet['Id_project'] ?>" title="Nombre de tâches">
                                <?= count($projet['tasks']) ?>
                            </span>
                        </div>

                        <ul>
                            <?php if (!empty($projet['tasks'])) : ?>
                                <?php foreach ($projet['tasks'] as $task) : ?>

                                    <?php
                                    $priorityClass = match ($task['priority_task']) {
                                        1 => 'bg-priority-red',
                                        2 => 'bg-priority-orange',
                                        3 => 'bg-priority-green',
                                        default => "Autre",
                                    };
                                    ?>

                                    <form class="formProject task <?= $priorityClass ?>" method="POST">

                                        <input type="hidden" name="currentTask" value="<?= htmlspecialchars($task['task']) ?>">

                                        <li class="currentTask <?= $task['done'] ? 'done' : '' ?> ">

                                            <div class="currentTask__text">
                                                <?= htmlspecialchars($task['task']) ?>
                                                <?php if (!empty($task['deadline'])): ?>

                                                    <span
                                                        class="deadline"
                                                        data-deadline="<?= htmlspecialchars($task['deadline']) ?>">
                                                        ⏳ <?= date("d/m/Y", strtotime($task['deadline'])) ?>
                                                    </span>

                                                <?php endif; ?>
                                            </div>

                                        </li>

                                        <div class="formProject__btn_action_popup">

                                            <span class="formProject__btn_action_popup__close"><i class="bi bi-x-lg"></i></span>

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
                                                            <?= ($task['priority_task'] == $i) ? 'checked' : '' ?>>
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

                                            <div class="action-group">

                                                <input type="hidden" name="taskId" value="<?= $task['Id_tasks'] ?>">
                                                <input type="hidden" name="doneTask" value="<?= $task['done'] ?>">
                                                <button type="submit" class="btnCheck"><i class="bi bi-check2-circle"></i></button>


                                                <input type="hidden" name="deleteTask" value="<?= $task['Id_tasks'] ?>">
                                                <button type="submit" class="btnDelete"><i class="bi bi-trash3"></i></button>

                                            </div>

                                        </div>

                                        <div class="formProject__btn_action"><i class="bi bi-three-dots"></i></div>

                                    </form>

                                <?php endforeach; ?>
                            <?php else : ?>
                                <li style="text-align: center;">Aucune tâche pour ce projet</li>
                            <?php endif; ?>


                        </ul>

                        <form action="" method="GET" class="cardProject__form_finish">
                            <input type="hidden" name="deleteProject" value="<?= $projet['Id_project'] ?>">
                            <button id="finish" disabled>Terminé !</button>
                        </form>

                        <a href=""
                            class="main__btn_create_task"
                            title="Créer une nouvelle tâche"
                            data-projet-id="<?= (int) $projet['Id_project'] ?>"
                            data-projet-name="<?= htmlspecialchars($projet['project_name']) ?>">
                            <i class="bi bi-clipboard-plus"></i>
                        </a>

                    </div>

                <?php endforeach; ?>

            <?php else : ?>
                <p>Aucun projet pour le moment.</p>
            <?php endif; ?>
        </div>

    </section>

</div>

<div id="messageContainer"></div>

<?php include 'modals/addTask.php' ?>


<?php
render('default', true, [
    'title' => "Ma liste de chauses à faire",
    'js' => 'assets/js/index.js',
    'content' => ob_get_clean()
]) ?>