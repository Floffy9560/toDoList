<?php

require_once 'models/Project.php';
require_once 'models/Tasks.php';

$projectModel = new Project();
$projects = $projectModel->getAllProject($_SESSION['idUser']);

// var_dump($_POST);
// =  'project' => string 'Jardin moyen' (length=12)
//   'new_project' => string '' (length=0)
//   'project_id' => string '' (length=0)
//   'task_name' => string 'test 1' (length=6)
//   'priority_task' => string '3' (length=1)
//   'deadline' => string '2025-09-01' (length=10)

//public function addProject($project_name, $Id_users, $priority = 'normal')
//public function addTask($projet_id, $description, $Id_users, $deadline, $priority_task = 1)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $new_project = $_POST['new_project'];
      $id_project = $_POST['project_id'];
      $priority_project = $_POST['priority_project'];
      $description = $_POST['task_name'];
      $priority_task = $_POST['priority_task'];
      $deadline = $_POST['deadline'];
      $Id_users = $_SESSION['idUser'];

      if (!empty($new_project)) {
            // Création d’un nouveau projet
            $newProject = new Project();
            $newProjectId = $newProject->addProject($new_project, $Id_users, $priority_project);

            // Création de la tâche liée à ce nouveau projet
            $newTask = new Task();
            $newTask->addTask($newProjectId, $description, $Id_users, $deadline, $priority_task);

            $message = 'Projet créé avec une tâche.';
      } else {
            // Projet existant
            $newTask = new Task();
            $newTask->addTask($id_project, $description, $Id_users, $deadline, $priority_task);

            $message = 'Tâche ajoutée avec succès au projet existant.';
      }
}

render('calendar', false, [
      'projects' => $projects,
      'message' => $message
]);
