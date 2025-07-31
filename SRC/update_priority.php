<?php

require_once __DIR__ . '/models/Tasks.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);


if (!isset($data['taskId'], $data['priority'])) {
      http_response_code(400);
      echo json_encode(['error' => 'Paramètres manquants']);
      exit;
}

$taskId = (int)$data['taskId'];
$priority = (int)$data['priority'];

$task = new Task();
$success = $task->updatePriority($taskId, $priority);

if ($success) {
      echo json_encode(['success' => true]);
} else {
      http_response_code(500);
      echo json_encode(['error' => 'Échec de la mise à jour']);
}
