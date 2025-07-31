<?php

require_once 'models/HoneypotProtector.php';

$formTime = $_POST['form_timestamp'] ?? 0;
// Vérification du temps de soumission du formulaire (inf à 3 secondes)
if ((time() - $formTime) < 3) {
    // Trop rapide, suspect
    http_response_code(429);
    echo "Comportement suspect.";
    exit;
}

$error = [];
$protector = new HoneypotProtector();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($protector->isBotSubmission($_POST)) {
        // Optionnel : log de l'IP ici
        http_response_code(403);
        echo "Accès interdit.";
        exit;
    }


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
}


render('inscription', false, [
    'error' => $error,
]);
