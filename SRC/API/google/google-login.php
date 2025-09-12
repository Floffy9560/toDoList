<?php

header('Content-Type: application/json');
require_once 'models/User.php';

// Récupération du corps JSON envoyé en AJAX
$postData = json_decode(file_get_contents('php://input'), true);

if (empty($postData['credential'])) {
      echo json_encode(['success' => false, 'message' => 'Token manquant']);
      exit;
}

$id_token = $postData['credential'];
$client_id = '82809045876-339cp44q6u2iqj0v7mgoddeaq15gop8f.apps.googleusercontent.com';

// Vérification du token auprès de Google
$url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($id_token);
$response = file_get_contents($url);

if ($response === false) {
      echo json_encode(['success' => false, 'message' => 'Impossible de vérifier le token']);
      exit;
}

$data = json_decode($response, true);

// Vérifie que le token est bien destiné à ton appli
if (!isset($data['aud']) || $data['aud'] !== $client_id) {
      echo json_encode(['success' => false, 'message' => 'Token invalide']);
      exit;
}

try {
      $userModel = new User();

      // Vérifier si l’utilisateur existe déjà via google_id
      $user = $userModel->getUserByGoogleId($data['sub']);

      if (!$user) {
            // S'il existe déjà un compte classique avec le même email → on le lie
            if ($userModel->emailExists($data['email'])) {
                  $existing = $userModel->getUserByEmail($data['email']);
                  $stmt = $userModel->pdo->prepare("UPDATE ppllmm_users SET google_id = :gid WHERE Id_users = :id");
                  $stmt->execute([
                        ':gid' => $data['sub'],
                        ':id'  => $existing['Id_users']
                  ]);
                  $user = $existing;
            } else {
                  // Sinon, on crée un nouveau compte Google
                  $newId = $userModel->registerWithGoogle(
                        $data['sub'],
                        $data['email'],
                        $data['name'] ?? 'User' . rand(1000, 9999)
                  );
                  $user = $userModel->getUserById($newId);
            }
      }

      // On initialise la session
      $_SESSION['idUser'] = $user['Id_users'];
      $_SESSION['pseudo'] = $user['pseudo'];
      $_SESSION['email']  = $user['email'];

      echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie',
            'user'    => [
                  'id'    => $user['Id_users'],
                  'email' => $user['email'],
                  'name'  => $user['pseudo']
            ]
      ]);
} catch (Exception $e) {
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
