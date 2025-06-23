<?php

require_once 'Database.php';

class Task
{
      private $pdo;

      public function __construct()
      {
            $this->pdo = getConnexion();
      }

      // 🔧 Ajouter une tâche à un projet
      public function addTask($projet_id, $description, $Id_users)
      {
            $sql = 'INSERT INTO `ppllmm_tasks`(`task`, `Id_project`, `Id_users`) VALUES (:description, :projet_id, :Id_users)';

            try {
                  $stmt = $this->pdo->prepare($sql);
                  $stmt->bindParam(':description', $description, PDO::PARAM_STR);
                  $stmt->bindParam(':projet_id', $projet_id, PDO::PARAM_INT);
                  $stmt->bindParam(':Id_users', $Id_users, PDO::PARAM_INT);
                  $stmt->execute();
                  return true;
            } catch (PDOException $e) {
                  echo "Erreur lors de l'ajout de la tâche : " . $e->getMessage();
                  return false;
            }
      }

      // 📋 Récupérer les tâches d’un projet
      public function getTasksByProject($projet_id)
      {
            $sql = 'SELECT * FROM ppllmm_tasks WHERE id_project = :projet_id';

            try {
                  $stmt = $this->pdo->prepare($sql);
                  $stmt->bindParam(':projet_id', $projet_id, PDO::PARAM_INT);
                  $stmt->execute();
                  return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                  echo "Erreur lors de la récupération des tâches : " . $e->getMessage();
                  return [];
            }
      }

      public function markTaskAsDone($taskText)
      {
            $query = "UPDATE ppllmm_tasks SET done = 1 WHERE task = :task";

            try {
                  $stmt = $this->pdo->prepare($query);
                  $stmt->bindParam(':task', $taskText, PDO::PARAM_STR);
                  return $stmt->execute();
            } catch (PDOException $e) {
                  echo "Erreur lors de la mise à jour de la tâche : " . $e->getMessage();
                  return false;
            }
      }

      public function updateTaskDoneStatus($taskText, $done)
      {
            $query = "UPDATE ppllmm_tasks SET done = :done WHERE task = :task";
            try {
                  $stmt = $this->pdo->prepare($query);
                  $stmt->bindParam(':done', $done, PDO::PARAM_INT);
                  $stmt->bindParam(':task', $taskText, PDO::PARAM_STR);
                  return $stmt->execute();
            } catch (PDOException $e) {
                  error_log("Erreur SQL : " . $e->getMessage());
                  return false;
            }
      }

      public function setTaskStatus($taskText, $done)
      {
            $query = "UPDATE ppllmm_tasks SET done = :done WHERE task = :task";

            try {
                  $stmt = $this->pdo->prepare($query);
                  $stmt->bindParam(':done', $done, PDO::PARAM_INT);
                  $stmt->bindParam(':task', $taskText, PDO::PARAM_STR);
                  return $stmt->execute();
            } catch (PDOException $e) {
                  echo "Erreur lors de la mise à jour de la tâche : " . $e->getMessage();
                  return false;
            }
      }
}
