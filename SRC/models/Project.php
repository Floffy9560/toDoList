<?php

class Project
{
      private $pdo;

      public function __construct()
      {
            $this->pdo = getConnexion();
      }

      public function addProject($project_name, $Id_users)
      {
            $insert = 'INSERT INTO `ppllmm_project`( `project_name`, `Id_users`) VALUES (:project_name, :Id_users)';

            try {
                  $stmt = $this->pdo->prepare($insert);
                  $stmt->bindParam(':project_name', $project_name, PDO::PARAM_STR);
                  $stmt->bindParam(':Id_users', $Id_users, PDO::PARAM_INT);
                  $stmt->execute();
                  return true;
            } catch (PDOException $e) {
                  echo "Erreur lors de l'ajout du projet : " . $e->getMessage();
                  return false;
            }
      }
      // public function getAllProject($Id_users)
      // {
      //       $display = 'SELECT * FROM ppllmm_project WHERE id_users = :Id_users';

      //       try {
      //             $stmt = $this->pdo->prepare($display);
      //             $stmt->bindParam(':Id_users', $Id_users, PDO::PARAM_INT);  // <-- Bind ici
      //             $stmt->execute();
      //             $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
      //             return $projects;
      //       } catch (PDOException $e) {
      //             echo "Erreur lors de la récupération des projets : " . $e->getMessage();
      //             return false;
      //       }
      // }
      public function getAllProject($Id_users)
      {
            $sql = '
        SELECT 
            p.Id_project,
            p.project_name,
            p.Id_users,
            p.priority AS project_priority,
            t.Id_tasks,
            t.task,
            t.priority AS task_priority,
            t.done
        FROM ppllmm_project p
        LEFT JOIN ppllmm_tasks t ON p.Id_project = t.Id_project
        WHERE p.Id_users = :Id_users
        ORDER BY p.Id_project, t.Id_tasks
    ';

            try {
                  $stmt = $this->pdo->prepare($sql);
                  $stmt->bindParam(':Id_users', $Id_users, PDO::PARAM_INT);
                  $stmt->execute();
                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  $projects = [];

                  foreach ($rows as $row) {
                        $projId = $row['Id_project'];

                        if (!isset($projects[$projId])) {
                              $projects[$projId] = [
                                    'Id_project' => $row['Id_project'],
                                    'project_name' => $row['project_name'],
                                    'Id_users' => $row['Id_users'],
                                    'priority' => $row['project_priority'],
                                    'tasks' => []
                              ];
                        }

                        // Si une tâche existe, on l’ajoute
                        if (!empty($row['Id_tasks'])) {
                              $projects[$projId]['tasks'][] = [
                                    'Id_tasks' => $row['Id_tasks'],
                                    'task' => $row['task'],
                                    'priority' => $row['task_priority'],
                                    'done' => (bool)$row['done']
                              ];
                        }
                  }

                  // Réindexer les projets
                  return array_values($projects);
            } catch (PDOException $e) {
                  echo "Erreur lors de la récupération des projets : " . $e->getMessage();
                  return false;
            }
      }


      public function getProjectByPriority($priority)
      {
            $select = '
        SELECT 
            p.Id_project,
            p.project_name,
            p.Id_users,
            p.priority AS project_priority,
            t.Id_tasks,
            t.task,
            t.priority AS task_priority,
            t.done
        FROM ppllmm_project p
        LEFT JOIN ppllmm_tasks t ON t.Id_project = p.Id_project
        WHERE p.priority = :priority
        ORDER BY p.Id_project, t.Id_tasks
    ';

            try {
                  $stmt = $this->pdo->prepare($select);
                  $stmt->bindParam(':priority', $priority, PDO::PARAM_STR);
                  $stmt->execute();
                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  $projects = [];
                  foreach ($rows as $row) {
                        $id = $row['Id_project'];
                        if (!isset($projects[$id])) {
                              $projects[$id] = [
                                    'Id_project' => $id,
                                    'project_name' => $row['project_name'],
                                    'Id_users' => $row['Id_users'],
                                    'priority' => $row['project_priority'],
                                    'tasks' => []
                              ];
                        }
                        if ($row['Id_tasks']) {
                              $projects[$id]['tasks'][] = [
                                    'Id_tasks' => $row['Id_tasks'],
                                    'task' => $row['task'],
                                    'priority' => $row['task_priority'],
                                    'done' => $row['done']
                              ];
                        }
                  }

                  // Réindexer les projets pour obtenir un tableau normal
                  return array_values($projects);
            } catch (PDOException $e) {
                  echo "Erreur lors de la récupération des projets par priorité : " . $e->getMessage();
                  return false;
            }
      }


      public function deleteProjectById($projectId)
      {
            $delete = 'DELETE FROM `ppllmm_project` WHERE `Id_project` = :projectId';

            try {
                  $stmt = $this->pdo->prepare($delete);
                  $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
                  $stmt->execute();
                  return true; // retourne true si tout s'est bien passé
            } catch (PDOException $e) {
                  error_log("Erreur lors de la suppression du projet : " . $e->getMessage());
                  return false;
            }
      }
}
