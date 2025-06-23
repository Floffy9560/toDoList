<?php



if (isset($_POST['deconnexion'])) {
      $_SESSION = [];
      session_destroy();
      header('Location: index'); // ou la page d’accueil
} else {
      render('deconnexion', false);
}
