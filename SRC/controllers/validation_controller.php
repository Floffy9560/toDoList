<?php

$pdo = getConnexion();

try {
      require 'models/User.php';
      $user = new User();

      $token  = $_GET['token']  ?? null;
      $pseudo = $_GET['pseudo'] ?? null;
      $eMail = $_GET['eMail'] ?? null;

      if (!empty($token) && !empty($pseudo)) {
            $id = $user->getIdUser($pseudo);

            // Insert the token into the database
            if ($id) {
                  $stmt = $pdo->prepare("
                SELECT id_users 
                FROM ppllmm_users 
                WHERE validation_token = :token AND is_verified = 0
            ");
                  $stmt->bindValue(':token', $token, PDO::PARAM_STR);
                  $stmt->execute();

                  $result = $stmt->fetch(PDO::FETCH_ASSOC);

                  // If the token is valid and not already verified / Si le token est valide passé le statu is_verified à 1 et le token à NULL pui rediriger vers la page de confirmation avec le statut de succès
                  if ($result) {
                        $update = $pdo->prepare("
                    UPDATE ppllmm_users 
                    SET validation_token = NULL, is_verified = 1 
                    WHERE id_users = :id
                ");
                        $update->bindValue(':id', $id, PDO::PARAM_INT);
                        $update->execute();

                        header("Location: /confirmation?status=success&eMail=" . urlencode($eMail));
                        exit;
                  }
            }
      }

      header("Location: /confirmation?status=invalid");
      exit;
} catch (PDOException $e) {
      // Logue discrètement, n'affiche pas les erreurs en prod
      error_log("Erreur BDD : " . $e->getMessage());
      header("Location: /confirmation?status=error");
      exit;
}
