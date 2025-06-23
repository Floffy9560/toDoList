<?php

$error = [];

if (!empty($_POST)) {
      require 'models/User.php';
      $user = new User();


      try {
            $user->setPseudo($_POST['pseudo']);
      } catch (\Exception $e) {
            $error['pseudo'] = $e->getMessage();
      }
      try {
            $mail = $user->setEmail($_POST['email']);
      } catch (\Exception $e) {
            $error['email'] = $e->getMessage();
      }
      try {
            $user->setPassword($_POST['password']);
      } catch (\Exception $e) {
            $decoded = json_decode($e->getMessage(), true);
            $error['password'] = is_array($decoded) ? $decoded : [$e->getMessage()];
      }

      if (empty($error)) {
            if ($user->register()) {
                  $_SESSION['idUser'] = $user->getIdUser($user->getPseudo());
                  $_SESSION['pseudo'] = $user->getPseudo();
                  header('location: index');
                  exit();
            } else {
                  $error['global'] = 'Echec de l\'enregistrement';
            }
      }
}

render('inscription', false, [
      'error' => $error,
]);
