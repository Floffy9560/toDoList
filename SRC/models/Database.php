<?php
function getConnexion()
{
      static $pdo = null; // Stock la connexion pour qu’elle soit réutilisée
      if ($pdo === null) {
            try {

                  $dsn = "mysql:host=mysql-container;dbname=ToDoList;charset=utf8";
                  $user = "Floffy";
                  $pass = "Floffy9560";

                  $pdo = new PDO($dsn, $user, $pass);
                  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                  echo "Erreur de connexion : " . $e->getMessage();
                  die();
            }
      }
      return $pdo;
}
