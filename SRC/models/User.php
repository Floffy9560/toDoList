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

    // public function verifyPseudoAndPassword($pseudo, $password)
    // {
    //     $sql = 'SELECT pseudo ,password FROM ppllmm_users WHERE pseudo = :pseudo';

    //     try {
    //         $stmt = $this->pdo->prepare($sql);
    //         $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    //         $stmt->execute();
    //         $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //         if ($user && password_verify($password, $user['password'])) {
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     } catch (PDOException $e) {
    //         error_log("Erreur SQL : " . $e->getMessage());
    //         return false;
    //     }
    // }


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
        $this->token = $this->generateToken(); // Garde une trace du token pour un futur usage (ex. mail)

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
}
