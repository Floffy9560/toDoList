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

            $userData = $user->getUserByPseudo($pseudo);

            if (!$userData) {
                  throw new Exception("Utilisateur introuvable.");
            }

            // Gestion des délais de blocage progressifs
            $failedAttempts = (int) $userData['failed_attempts'];
            $lastAttemptTimestamp = $userData['last_attempt'] ? (new DateTime($userData['last_attempt']))->getTimestamp() : 0;
            $now = time();

            $delays = [
                  3  => 120,    // 2 minutes
                  5  => 600,    // 10 minutes
                  10 => 1800,   // 30 minutes
                  15 => 5400,   // 1h30
            ];

            $blockTime = 0;
            foreach ($delays as $attemptThreshold => $seconds) {
                  if ($failedAttempts >= $attemptThreshold) {
                        $blockTime = $seconds;
                  }
            }

            if ($blockTime > 0 && ($now - $lastAttemptTimestamp) < $blockTime) {
                  $remaining = $blockTime - ($now - $lastAttemptTimestamp);
                  throw new Exception("Trop de tentatives. Réessayez dans " . ceil($remaining / 60) . " minute(s).");
            }

            if (password_verify($password, $userData['password'])) {
                  $user->resetLoginAttempts($pseudo);
                  $_SESSION['idUser'] = $userData['Id_users'];
                  $_SESSION['pseudo'] = $pseudo;
                  header('Location: index');
                  exit;
            } else {
                  $user->recordFailedAttempt($pseudo);
                  throw new Exception("Identifiants incorrects.");
            }
      } catch (Exception $e) {
            $error = $e->getMessage();
      }
}

render('connexion', false, ['error' => $error]);
