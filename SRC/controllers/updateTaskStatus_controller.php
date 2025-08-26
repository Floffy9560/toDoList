<?php

require_once 'models/Tasks.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $taskText = $_POST['currentTask'] ?? null;
      $done = isset($_POST['done']) ? (int)$_POST['done'] : null;

      // Cas suppression
      if (isset($_POST['deleteTask'])) {
            $taskId = (int)$_POST['deleteTask'];

            if ($taskId > 0) {
                  $task = new Task();
                  $result = $task->deleteTask($taskId);

                  echo json_encode(['success' => $result]);
                  exit;
            } else {
                  echo json_encode(['success' => false, 'message' => 'ID de tâche invalide']);
                  exit;
            }
      }

      // Cas mise à jour du statut "done"
      if ($taskText !== null && $done !== null) {
            $task = new Task();
            $result = $task->updateTaskDoneStatus($taskText, $done);

            echo json_encode(['success' => $result]);
            exit;
      } else {
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
            exit;
      }
}

echo json_encode(['success' => false, 'message' => 'Méthode invalide']);
