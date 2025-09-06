<?php

require_once __DIR__ . '/models/Tasks.php';
require_once __DIR__ . '/models/Project.php';

header('Content-Type: application/json');

// Vérifie que les données sont présentes
if (!isset($_POST['taskId'])) {
      echo json_encode(['success' => false, 'message' => 'Paramètres manquants.']);
      exit;
}

$taskId = (int)$_POST['taskId'];
$taskModel = new Task();

if (isset($_POST['deleteTask'])) {
      // Suppression de la tâche
      $deleted = $taskModel->deleteTask($taskId);
      echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Tâche supprimée' : 'Erreur lors de la suppression'
      ]);
      exit;
}

if (isset($_POST['done'])) {
      $done = ($_POST['done'] === "1") ? 1 : 0;
      $updated = $taskModel->markTaskAsDone($taskId, $done);
      echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Statut mis à jour' : 'Erreur lors de la mise à jour'
      ]);
      exit;
}
