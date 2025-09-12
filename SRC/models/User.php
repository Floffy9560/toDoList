<?php
class User
{
    private $pdo;
    private $id;
    private $pseudo;
    private $email;
    private $password;
    private $token;

    public function __construct()
    {
        $this->pdo = getConnexion();
    }

    public function getPseudo()
    {
        return $this->pseudo;
    }

    public function setPseudo($value)
    {
        if (empty($value)) {
            throw new Exception('Pseudo requis');
        }

        if (strlen($value) < 3 || strlen($value) > 10) {
            throw new Exception('Le pseudo doit contenir entre 3 et 10 caractères');
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $value)) {
            throw new Exception('Le pseudo doit être alphanumérique sans caractères spéciaux');
        }

        $this->pseudo = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        if (empty($value)) {
            throw new Exception('Email requis');
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('E-mail invalide');
        }

        $this->email = $value;
    }

    public function setPassword($value)
    {
        $errors = [];

        if (empty($value)) {
            $errors[] = 'Le mot de passe est requis';
        }

        if (strlen($value) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères';
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $errors[] = 'Le mot de passe doit contenir au moins une majuscule';
        }

        if (!preg_match('/[a-z]/', $value)) {
            $errors[] = 'Le mot de passe doit contenir au moins une minuscule';
        }

        if (!preg_match('/[0-9]/', $value)) {
            $errors[] = 'Le mot de passe doit contenir au moins un chiffre';
        }

        if (!preg_match('/[^a-zA-Z0-9]/', $value)) {
            $errors[] = 'Le mot de passe doit contenir au moins un caractère spécial';
        }

        if (!empty($errors)) {
            throw new Exception(json_encode($errors));
        }

        $this->password = password_hash($value, PASSWORD_DEFAULT);
    }


    public function getPassword()
    {
        return $this->password;
    }



    public function getIdUser($pseudo)
    {
        $sql = "SELECT Id_users FROM ppllmm_users WHERE pseudo = :pseudo";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn(); // retourne l'Id_users ou false
    }

    public function recordFailedAttempt($pseudo)
    {
        $sql = "UPDATE ppllmm_users
                SET failed_attempts = failed_attempts + 1,
                last_attempt = NOW()
                WHERE pseudo = :pseudo";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':pseudo' => $pseudo]);
    }


    public function resetLoginAttempts($pseudo)
    {
        $sql = "UPDATE ppllmm_users
                SET failed_attempts = 0,
                last_attempt = NULL
                WHERE pseudo = :pseudo";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':pseudo' => $pseudo]);
    }

    public function verifyPseudoAndPassword($pseudo, $password)
    {
        $userData = $this->getUserByPseudo($pseudo);

        if ($userData) {
            $now = new DateTime();
            $lastAttempt = new DateTime($userData['last_attempt'] ?? '2000-01-01 00:00:00');
            $diff = $now->getTimestamp() - $lastAttempt->getTimestamp();

            if ($userData['failed_attempts'] >= 5 && $diff < 600) {
                throw new \Exception("Trop de tentatives. Réessayez dans 10 minutes.");
            }

            if (password_verify($password, $userData['password'])) {
                $this->resetLoginAttempts($pseudo);
                return true;
            } else {
                $this->recordFailedAttempt($pseudo);
                return false;
            }
        }

        return false;
    }


    // *
    // ** infos utilisateur : utile pour la protection contre les brute force
    // =======================================================================

    public function getUserByPseudo($pseudo)
    {
        $sql = "SELECT * FROM ppllmm_users WHERE pseudo = :pseudo LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':pseudo' => $pseudo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM ppllmm_users WHERE Id_users = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // *
    // ** vérification mail
    // =====================

    public function emailExists($email)
    {
        $sql = "SELECT id_users FROM ppllmm_users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() !== false;
    }

    // *
    // ** token de sécurité
    // ===================== 

    function generateToken($length = 64)
    {
        return bin2hex(random_bytes($length / 2)); // 64 caractères hex = 32 octets
    }

    public function getToken()
    {
        return $this->token;
    }

    // *
    // ** insertion en base de données
    // ================================ 

    public function register()
    {
        $this->token = $this->generateToken();

        $query = $this->pdo->prepare("
        INSERT INTO `ppllmm_users` (`email`, `password`, `pseudo`, `validation_token`) 
        VALUES (:email, :password, :pseudo, :validation_token)
    ");

        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':password', $this->password, PDO::PARAM_STR);
        $query->bindValue(':pseudo', $this->pseudo, PDO::PARAM_STR);
        $query->bindValue(':validation_token', $this->token, PDO::PARAM_STR);

        return $query->execute();
    }

    // *
    // ** Envoi d'un mail avec token daté pour validation changement de mdp 
    // ===================================================================== 

    public function sendPasswordResetEmail($userEmail)
    {
        // Vérifier que l'email existe
        $stmt = $this->pdo->prepare("SELECT Id_users FROM ppllmm_users WHERE email = ?");
        $stmt->execute([$userEmail]);
        $user = $stmt->fetch();
        if (!$user) {
            // Retourner un message d'erreur au lieu de false
            return "Aucun compte n'est associé à cette adresse e-mail.";
        }

        // Générer un token sécurisé
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Insérer le token en base
        $stmt = $this->pdo->prepare(
            "INSERT INTO ppllmm_password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)"
        );

        // lier les paramètres
        $stmt->bindValue(':user_id', $user['Id_users'], PDO::PARAM_INT);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', $expires, PDO::PARAM_STR);

        // exécuter la requête
        $stmt->execute();

        // Envoyer email (exemple simplifié)
        $resetLink = "http://f-l-o-x.alwaysdata.net/reset_password?token=$token";
        // a utiliser sur un vrai serveur 
        mail(
            $userEmail,
            "Réinitialisation mot de passe",
            "Cliquez sur ce lien pour réinitialiser votre mot de passe : $resetLink\nAttention : ce lien expire dans 1 heure."
        );

        // test en lien pour le debug 
        // echo "Lien de réinitialisation (debug) : $resetLink";

        return true;
    }

    public function changePassword($newPasswordHashed, $user_id, $token_id)
    {
        // Mettre à jour le mot de passe de l'utilisateur
        $stmt = $this->pdo->prepare("UPDATE ppllmm_users SET password = :password WHERE Id_users = :Id_users");
        $stmt->bindValue(':password', $newPasswordHashed, PDO::PARAM_STR);
        $stmt->bindValue(':Id_users', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Marquer le token comme utilisé
        $stmt = $this->pdo->prepare("UPDATE ppllmm_password_resets SET used = 1 WHERE id = :id");
        $stmt->bindValue(':id', $token_id, PDO::PARAM_INT); // ici utiliser l'ID du token
        $stmt->execute();
    }
    public function verifyResetToken($token)
    {
        $stmt = $this->pdo->prepare("
        SELECT * FROM ppllmm_password_resets 
        WHERE token = :token AND used = 0 AND expires_at > NOW()
    ");

        // Lier le token à la requête
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);

        // Exécuter la requête
        $stmt->execute();

        $reset = $stmt->fetch(PDO::FETCH_ASSOC);

        return $reset;
    }

    //
    // ** Pour Google
    // ==============
    public function getUserByGoogleId($googleId)
    {
        $sql = "SELECT * FROM ppllmm_users WHERE google_id = :google_id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':google_id' => $googleId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registerWithGoogle($googleId, $email, $pseudo)
    {
        $query = $this->pdo->prepare("
        INSERT INTO ppllmm_users (google_id, email, pseudo, is_verified)
        VALUES (:google_id, :email, :pseudo, 1)
    ");

        $query->bindValue(':google_id', $googleId, PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);

        $query->execute();

        return $this->pdo->lastInsertId();
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM ppllmm_users WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
