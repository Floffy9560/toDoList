<?php
function getConnexion()
{
      static $pdo = null; // Stock la connexion pour qu’elle soit réutilisée
      if ($pdo === null) {
            try {
                  $dsn = "mysql:host=mysql-container;dbname=ToDoList;charset=utf8";
                  // $dsn = "mysql:host=mysql-f-l-o-x.alwaysdata.net;dbname=f-l-o-x_tododb;charset=utf8";
                  // $user = "f-l-o-x";
                  // $pass = "NC#iXCNcDXe7!2Y";
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
