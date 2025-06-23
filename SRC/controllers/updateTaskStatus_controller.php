<?php

require_once 'models/Tasks.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $taskText = $_POST['currentTask'] ?? null;
      $done = isset($_POST['done']) ? (int)$_POST['done'] : null;

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
