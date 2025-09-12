<?php

require_once 'models/Database.php';
require_once 'models/User.php';
require_once 'models/Security.php';

$user = new User();

$error = '';
$success = '';
$error_token = '';

//  Vérifier le token et afficher le formulaire

$token = $_GET['token'] ?? '';

$reset = $user->verifyResetToken($token);

if (!$reset) {
    $error_token = "Token invalide ou expiré. Veuillez demander une nouvelle réinitialisation.";
}
$token_id = $reset['id'] ?? '';
$user_id = $reset['user_id'] ?? '';

// reinitialiser le mdp

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($newPassword) || empty($confirmPassword)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {

        // Hasher le mot de passe
        $user->setPassword($newPassword);
        $newPasswordHashed = $user->getPassword();

        $user->changePassword($newPasswordHashed, $user_id, $token_id);

        $success = "Mot de passe réinitialisé avec succès.";

        $success = '
                    <p style="color: green; font-size: 24px; text-align: center; font-weight: bold;">
                        Mot de passe réinitialisé avec succès.
                    </p>
                    <p style="font-size: 20px; text-align: center;">
                        Redirection dans <span id="countdown">3</span> secondes...
                    </p>
                    ';
    }
}

render('reset_password', false, [
    'error' => $error,
    'success' => $success,
    'error_token' => $error_token,
    'reset' => $reset,
]);
