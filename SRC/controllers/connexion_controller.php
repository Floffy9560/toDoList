<?php
// require_once 'models/User.php';


// if (!empty($_POST['pseudo']) && !empty($_POST['password'])) {

//       $pseudo = $_POST['pseudo'];
//       $password = $_POST['password'];
//       $user = new User();
//       $user->verifyMailAndPassword($pseudo, $password);
//       $_SESSION['idUser'] = $user->getIdUser($pseudo);
//       $_SESSION['pseudo'] = $user->getPseudo();
//       render('index', false);
// }

// render('connexion', false);
require_once 'models/User.php';
require_once 'models/Security.php';

$error = null; // Défini par défaut

if (!empty($_POST['pseudo']) && !empty($_POST['password'])) {

      $pseudo = Security::cleanInput($_POST['pseudo']);
      $password = trim($_POST['password']);

      if (!Security::validatePseudo($pseudo)) {
            $error = "Pseudo invalide.";
            // afficher erreur
      }

      $user = new User();

      if ($user->verifyMailAndPassword($pseudo, $password)) {
            $_SESSION['idUser'] = $user->getIdUser($pseudo);
            $_SESSION['pseudo'] = $pseudo; // Ou récupéré via une méthode si besoin
            header('location: index');
            exit; // Pour éviter que la suite du script s'exécute
      } else {
            $error = "Identifiants incorrects.";
      }
}
render('connexion', false, [
      'error' => $error,
]);
