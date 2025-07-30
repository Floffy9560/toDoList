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
        $user->setEmail($_POST['email']);
    } catch (\Exception $e) {
        $error['email'] = $e->getMessage();
    }

    if (!isset($error['email']) && $user->emailExists(trim($_POST['email']))) {
        $error['email'] = "Le mail est déjà utilisé veuillez en choisir un autre.";
    }
    try {
        $user->setPassword($_POST['password']);
    } catch (\Exception $e) {
        $decoded = json_decode($e->getMessage(), true);
        $error['password'] = is_array($decoded) ? $decoded : [$e->getMessage()];
    }

    if (empty($error)) {
        try {
            if ($user->register()) {
                $idUser = $user->getIdUser($user->getPseudo());
                $pseudo = $user->getPseudo();
                $token = $user->getToken();
                $eMail = $user->getEmail();

                header("Location: /validation?token=" . urlencode($token) . "&pseudo=" . urlencode($pseudo) . "&eMail=" . urlencode($eMail));
                exit();
            } else {
                $error['global'] = 'Echec de l\'enregistrement';
            }
        } catch (\Exception $e) {
            $error['global'] = $e->getMessage();
        }
    }
}

render('inscription', false, [
    'error' => $error,
]);
