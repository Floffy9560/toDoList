<?php

require_once __DIR__ . '/models/Tasks.php';

header('Content-Type: application/json');

if (!isset($_POST['taskId'], $_POST['deadline'])) {
      echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
      exit;
}

$taskId = (int)$_POST['taskId'];
$deadline = $_POST['deadline'];

$taskModel = new Task();
$updated = $taskModel->updateDeadline($taskId, $deadline);

echo json_encode([
      'success' => $updated,
      'message' => $updated ? 'Deadline mise à jour' : 'Erreur lors de la mise à jour'
]);
