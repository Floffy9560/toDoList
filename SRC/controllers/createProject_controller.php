<?php

require_once 'models/Project.php';
require_once 'models/Tasks.php';

$Id_users = $_SESSION['idUser'];

var_dump($_POST);


// 
// == Ajouter un projet / Add a project
//
if (isset($_POST['nom_projet']) && !empty($_POST['nom_projet'])) {
      $project_name = trim($_POST['nom_projet']);
      $Id_users = $_SESSION['idUser'];
      $priority_project = $_POST['priority_project'] ?? 'normal';
      $project = new Project();
      $project->addProject($project_name, $Id_users, $priority_project);
      if ($project) {
            $message_project = "Le projet a été créé avec succès.";
      } else {
            $message_project = "Erreur lors de la création du projet.";
      }
}

$project = new Project();
$projets = $project->getAllProject($Id_users);

//
// == Ajouter une tâche / Add a task
//
if (!empty($_POST['projet_id']) && !empty($_POST['description'])) {

      $projet_id = $_POST['projet_id'];
      $description = $_POST['description'];
      $priority_task = (int)$_POST['priority_task'];
      $deadline = $_POST['deadline'];

      $task = new Task();
      $success = $task->addTask($projet_id, $description, $Id_users, $deadline, $priority_task);

      $message_task = $success
            ? "La tâche a été ajoutée avec succès."
            : "Erreur lors de l'ajout de la tâche.";
} elseif (!empty($description)) {
      $message_task = "Erreur : aucune projet valide sélectionné pour la tâche.";
}

render('createProject', false, [
      'projets' => $projets,
      'message_project' => $message_project ?? null,
      'message_task' => $message_task ?? null,
]);
