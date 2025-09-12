<?php

header('Content-Type: application/json');

require_once __DIR__ . '/models/Project.php';
require_once __DIR__ . '/models/Tasks.php';

$response = ['success' => false, 'message' => 'Requête invalide'];

try {
      // Vérifie méthode POST et utilisateur connecté
      if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['idUser'])) {
            throw new Exception("Utilisateur non connecté ou requête invalide.");
      }

      // Récupération sécurisée des données
      $new_project        = trim($_POST['new_project'] ?? '');
      $project_id_select  = trim($_POST['project_id_select'] ?? '');
      $project_id_hidden  = trim($_POST['project_id'] ?? '');
      $priority_project   = $_POST['priority_project'] ?? 'normal';
      $description        = trim($_POST['task_name'] ?? '');
      $priority_task      = (int) ($_POST['priority_task'] ?? 3);
      $deadline           = !empty($_POST['deadline']) ? $_POST['deadline'] : null;
      $Id_users           = (int) $_SESSION['idUser'];

      // Vérification du nom de tâche
      if (empty($description)) {
            throw new Exception("Le nom de la tâche est requis.");
      }

      // Détermination du projet à utiliser
      if (!empty($new_project)) {
            $id_project = null; // sera créé
      } elseif (!empty($project_id_select)) {
            $id_project = (int) $project_id_select;
      } elseif (!empty($project_id_hidden)) {
            $id_project = (int) $project_id_hidden;
      } else {
            throw new Exception("Aucun projet sélectionné ou créé.");
      }

      $taskModel = new Task();

      // Cas 1 : création d’un nouveau projet + tâche
      if (!empty($new_project)) {
            $projectModel = new Project();
            $newProjectId = (int) $projectModel->addProject($new_project, $Id_users, $priority_project);

            $taskId = $taskModel->addTask($newProjectId, $description, $Id_users, $deadline, $priority_task);

            $response = [
                  'success' => true,
                  'message' => 'Projet créé avec une tâche.',
                  'task' => [
                        'Id_tasks'      => $taskId,
                        'task'          => $description,
                        'Id_project'    => $newProjectId,
                        'priority_task' => $priority_task,
                        'done'          => 0,
                        'deadline'      => $deadline,
                  ]
            ];
      }
      // Cas 2 : tâche ajoutée à projet existant
      else {
            $taskId = $taskModel->addTask($id_project, $description, $Id_users, $deadline, $priority_task);

            $response = [
                  'success' => true,
                  'message' => 'Tâche ajoutée au projet existant.',
                  'task' => [
                        'Id_tasks'      => $taskId,
                        'task'          => $description,
                        'Id_project'    => $id_project,
                        'priority_task' => $priority_task,
                        'done'          => 0,
                        'deadline'      => $deadline,
                  ]
            ];
      }
} catch (Exception $e) {
      $response = ['success' => false, 'message' => $e->getMessage()];
}

echo json_encode($response);
exit;
