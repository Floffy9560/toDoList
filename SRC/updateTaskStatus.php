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



// try {
//       // Connexion PDO
//       $pdo = new PDO("mysql:host=localhost;dbname=ton_db;charset=utf8", "user", "pass");
//       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//       // Met à jour uniquement le champ done
//       $stmt = $pdo->prepare("UPDATE ppllmm_task SET done = :done WHERE Id_tasks = :taskId");
//       $stmt->bindParam(':done', $done, PDO::PARAM_INT);
//       $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);
//       $stmt->execute();

//       echo json_encode(['success' => true]);
// } catch (PDOException $e) {
//       echo json_encode(['success' => false, 'message' => $e->getMessage()]);
// }