<?php
require_once 'models/User.php';
require_once 'models/Security.php';

$user = new User();
$error = null;

if (!empty($_POST['pseudo']) && !empty($_POST['password'])) {
      $pseudo = Security::cleanInput($_POST['pseudo']);
      $password = trim($_POST['password']);

      try {
            if (!Security::validatePseudo($pseudo)) {
                  throw new Exception("Pseudo invalide.");
            }

            // On récupère les données utilisateur
            $userData = $user->getUserByPseudo($pseudo);

            if ($userData) {
                  $now = new DateTime();
                  $lastAttempt = new DateTime($userData['last_attempt'] ?? '2000-01-01 00:00:00');
                  $diff = $now->getTimestamp() - $lastAttempt->getTimestamp();

                  // Vérifie le blocage
                  if ($userData['failed_attempts'] >= 5 && $diff < 600) {
                        $error = "Trop de tentatives. Réessayez dans 10 minutes.";
                        render('connexion', false, ['error' => $error]);
                        return;
                  }

                  // Vérifie le mot de passe
                  if (password_verify($password, $userData['password'])) {
                        $user->resetLoginAttempts($pseudo); // reset compteur
                        $_SESSION['idUser'] = $user->getIdUser($pseudo);
                        $_SESSION['pseudo'] = $pseudo;
                        header('Location: index');
                        exit;
                  } else {
                        $user->recordFailedAttempt($pseudo);
                        throw new Exception("Identifiants incorrects.");
                  }
            } else {
                  throw new Exception("Utilisateur introuvable.");
            }
      } catch (Exception $e) {
            $error = $e->getMessage();
            render('connexion', false, ['error' => $error]);
            return;
      }
}

render('connexion', false, ['error' => $error]);
