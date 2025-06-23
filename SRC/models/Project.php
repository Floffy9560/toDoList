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
      public function getAllProject($Id_users)
      {
            $display = 'SELECT * FROM ppllmm_project WHERE id_users = :Id_users';

            try {
                  $stmt = $this->pdo->prepare($display);
                  $stmt->bindParam(':Id_users', $Id_users, PDO::PARAM_INT);  // <-- Bind ici
                  $stmt->execute();
                  $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  return $projects;
            } catch (PDOException $e) {
                  echo "Erreur lors de la récupération des projets : " . $e->getMessage();
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
