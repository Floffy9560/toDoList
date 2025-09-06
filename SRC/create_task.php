<?php

require_once __DIR__ . '/models/Tasks.php';
require_once __DIR__ . '/models/Project.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Requête invalide'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['task_name'], $_POST['current_project_id'], $_POST['priority_task'])) {
            try {
                  $description = trim($_POST['task_name']);
                  $projectId = (int) $_POST['current_project_id'];
                  $priority_task = (int) $_POST['priority_task'];
                  $Id_users = $_SESSION['idUser'];
                  $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;

                  $taskModel = new Task();
                  $addedTaskId = $taskModel->addTask($projectId, $description, $Id_users, $deadline, $priority_task);

                  if ($addedTaskId) {
                        $response = [
                              'success' => true,
                              'message' => 'Tâche ajoutée avec succès',
                              'task' => [
                                    'Id_tasks' => $addedTaskId,
                                    'task' => $description,
                                    'Id_project' => $projectId,
                                    'priority_task' => $priority_task,
                                    'done' => 0,
                                    'deadline' => $deadline,
                              ]
                        ];
                  } else {
                        $response = ['success' => false, 'message' => 'Impossible d’ajouter la tâche'];
                  }
            } catch (Exception $e) {
                  $response = ['success' => false, 'message' => 'Erreur serveur'];
            }
      } else {
            $response = ['success' => false, 'message' => 'Champs manquants'];
      }
}

echo json_encode($response);
