<?php

// Démarrage de la session
session_start();


// Test pour savoir comment récupérer les infos sur la session et comment elle fonctionne  !! A RETIRERE A LA FIN !!
// if (isset($_SESSION)) {
//       echo "<div style='background-color:gainsboro';>";
//       echo "<pre>";
//       print_r($_SESSION); // Affiche les infos de l'utilisateur
//       echo "</pre>";
//       echo "</div>";
// } else {
//       echo "Aucune session active.";
// }

// Activation des erreurs et affiche des messages d'erreur
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Routes manuelles pour endpoints AJAX/API
if ($_SERVER['REQUEST_URI'] === '/update_priority.php') {
      require_once __DIR__ . '/update_priority.php';
      exit;
}

require_once 'models/Database.php';
require 'assets/util.php';


//Récupération de l'adresse de la page 
$path = $_SERVER['REDIRECT_URL'];


if ($path == '/') {
      require 'controllers/index_controller.php';
} elseif ($path == '/robots.txt') {
      require_once 'robots.txt';
} else {
      $path = explode('/', $path)[1];

      $controlleur = 'controllers/' . $path . '_controller.php';

      if (file_exists($controlleur)) {
            require $controlleur;
      } elseif (!file_exists($controlleur)) {
            include 'views/' . $path . '.php';
      } else {
            require 'views/404.php';
      }
}
