<?php



if (empty($_SESSION)) {
      header('Location: connexion');
      exit();
}

require_once 'models/Project.php';
require_once 'models/Tasks.php';

// Changer la priorité de la tâche  / change priority of task
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);

      $taskId = (int) $data['Id_tasks'];
      $priority = (int) $data['priority'];

      $task = new Task();
      $task->updatePriority($taskId, $priority);
}

// afficher les tâche des projets en cour / display task of current project   
if (isset($_SESSION['idUser'])) {
      $Id_users = $_SESSION['idUser'];
      $project = new Project();
      $taskModel = new Task(); // Instancie ici

      $projets = $project->getAllProject($Id_users);

      if ($projets) {
            foreach ($projets as &$projet) {
                  $projet['tasks'] = $taskModel->getTasksByProject($projet['Id_project']);
            }
      }
}

// indiquer si une tache est réalisée / indicate if a task is performed
if (isset($_GET['currentTask']) && isset($_GET['markDone'])) {
      $taskText = $_GET['currentTask'];
      $taskModel = new Task();
      $taskModel->markTaskAsDone($taskText);
}

// Supprimer un projet / delette project
if (!empty($_GET['deleteProject'])) {
      $projectId = $_GET['deleteProject'];
      $project->deleteProjectById($projectId);
      header('location: index');
      exit();
}

render('index', false, [
      'projets' => $projets ?? '',

]);
