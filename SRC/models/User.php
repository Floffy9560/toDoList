<?php
class User
{
    private $pdo;
    private $id;
    private $pseudo;
    private $email;
    private $password;

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

    // public function getIdUser($email)
    // {
    //     $sql = "SELECT Id_users FROM ppllmm_users WHERE email = :email";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    //     $stmt->execute();

    //     return $stmt->fetchColumn(); // retourne l'Id_users ou false
    // }
    public function getIdUser($pseudo)
    {
        $sql = "SELECT Id_users FROM ppllmm_users WHERE pseudo = :pseudo";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn(); // retourne l'Id_users ou false
    }

    public function verifyMailAndPassword($pseudo, $password)
    {
        $sql = 'SELECT pseudo ,password FROM ppllmm_users WHERE pseudo = :pseudo';

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return false;
        }
    }

    public function register()
    {
        $query = $this->pdo->prepare("
            INSERT INTO `ppllmm_users` ( `email`, `password`,`pseudo`) 
            VALUES (:email, :password, :pseudo)
        ");

        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':password', $this->password, PDO::PARAM_STR);
        $query->bindValue(':pseudo', $this->pseudo, PDO::PARAM_STR);

        return $query->execute();
    }
}
