<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php'; // au lieu de User.php direct
require_once __DIR__ . '/../src/models/User.php';        // toujours utile si pas autoloadé

class UserTest extends TestCase
{
      private PDO $pdo;

      protected function setUp(): void
      {
            $this->pdo = new PDO('sqlite::memory:');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec("
            CREATE TABLE ppllmm_users (
                Id_users INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT,
                password TEXT,
                pseudo TEXT
            )
        ");
      }

      public function testRegisterUserInsertsData()
      {
            $user = new User($this->pdo);
            $user->setEmail("test@example.com");
            $user->setPassword(password_hash("mdp123", PASSWORD_DEFAULT));
            $user->setPseudo("testeur");

            $this->assertTrue($user->register());

            $stmt = $this->pdo->query("SELECT COUNT(*) FROM ppllmm_users WHERE pseudo = 'testeur'");
            $this->assertEquals(1, $stmt->fetchColumn());
      }

      public function testGetIdUserReturnsCorrectId()
      {
            $this->pdo->exec("
            INSERT INTO ppllmm_users (email, password, pseudo)
            VALUES ('a@a.com', 'hashedpass', 'alpha')
        ");

            $user = new User($this->pdo);
            $id = $user->getIdUser('alpha');

            $this->assertIsNumeric($id);
      }

      public function testVerifyMailAndPasswordReturnsTrueOnSuccess()
      {
            $hash = password_hash("motdepasse", PASSWORD_DEFAULT);
            $this->pdo->exec("
            INSERT INTO ppllmm_users (email, password, pseudo)
            VALUES ('b@b.com', '$hash', 'bravo')
        ");

            $user = new User($this->pdo);
            $this->assertTrue($user->verifyMailAndPassword("bravo", "motdepasse"));
      }
}
