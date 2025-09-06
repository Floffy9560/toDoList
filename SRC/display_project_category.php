<?php


require_once __DIR__ . '/models/Project.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_POST['category'])) {
      http_response_code(400);
      echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
      exit;
}

$projets = [];


if (isset($_POST['category'])) {

      $priority = (string)$_POST['category'];
      $project_priority_object = new Project();

      if ($priority === 'all') {
            $projets = $project_priority_object->getAllProject($_SESSION['idUser']);
      } else {
            $projets = $project_priority_object->getProjectByPriority($priority);
      }
}



// renvoyer directement la liste des projets
echo json_encode([
      'success' => true,
      'priority' => $priority,
      'projects' => $projets
]);
