<?php

require_once 'models/Project.php';
require_once 'models/Tasks.php';

$Id_users = $_SESSION['idUser'];

// 
// == Ajouter un projet / Add a project
//
if (isset($_POST['nom_projet']) && !empty($_POST['nom_projet'])) {
      $project_name = trim($_POST['nom_projet']);
      $Id_users = $_SESSION['idUser'];
      $project = new Project();
      $project->addProject($project_name, $Id_users);
}

$project = new Project();
$projets = $project->getAllProject($Id_users);

//
// == Ajouter une tâche / Add a task
//
if (isset($_POST['projet_id']) && !empty($_POST['projet_id']) || isset($_POST['description']) && !empty($_POST['description'])) {

      $projet_id = $_POST['projet_id'];
      $description = $_POST['description'];
      $priority = (int)$_POST['priority'];
      $task = new Task();
      // Ajouter une tâche
      $task->addTask($projet_id, $description, $Id_users, $priority);
}

render('createProject', false, [
      'projets' => $projets,
]);
