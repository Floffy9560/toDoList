<?php
require_once 'models/Database.php';
require_once 'models/Tasks.php';
require_once 'models/User.php';

$taskModel = new Task();
$userModel = new User();

// Récupère toutes les tâches avec deadline future
$tasks = $taskModel->getAllTasksWithDeadline();

$today = new DateTime();

foreach ($tasks as $task) {
      $deadline = new DateTime($task['deadline']);
      $interval = $today->diff($deadline);
      $days = (int)$interval->format('%r%a'); // jours restants (peut être négatif si passé)

      // On ne s’intéresse qu’aux rappels 7, 3 et 2 jours avant
      $reminderDays = [7, 3, 2];

      // Liste des rappels déjà envoyés
      $sentReminders = $task['reminderSent'] ? json_decode($task['reminderSent'], true) : [];

      if (in_array($days, $reminderDays) && empty($sentReminders[$days])) {

            // Récupère l’email de l’utilisateur
            $user = $userModel->getUserById($task['idUser']);
            if (!$user) continue;

            $subject = "Rappel : tâche '{$task['task']}' à échéance dans $days jours";

            $message = "
                        <html>
                        <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <title>Rappel de tâche</title>
                        <style>
                              body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin:0;padding:0; }
                              .container { max-width: 600px; margin: 20px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1);}
                              h2 { color:#2a7b9b; margin-top:0; }
                              p, li { font-size:16px; color:#333; line-height:1.5; }
                              .button { display:inline-block; padding:12px 20px; margin-top:15px; background:#2a7b9b; color:#fff; text-decoration:none; border-radius:6px; font-weight:bold; }
                              .button:hover { background:#1f5d7a; }
                              .footer { margin-top:20px; font-size:14px; color:#777; }
                              @media screen and (max-width:480px){ .container{padding:15px;} h2{font-size:20px;} p,li{font-size:14px;} .button{padding:10px 15px; font-size:14px;} }
                        </style>
                        </head>
                        <body>
                        <div class='container'>
                              <h2>Bonjour {$user['pseudo']},</h2>
                              <p>Vous avez une tâche à échéance prochaine :</p>
                              <ul>
                              <li><strong>Tâche :</strong> {$task['task']}</li>
                              <li><strong>Échéance :</strong> {$task['deadline']}</li>
                              <li><strong>Délai restant :</strong> $days jours</li>
                              </ul>
                              <a href='https://tonsite.com/task.php?id={$task['Id_tasks']}' class='button'>Voir la tâche</a>
                              <p class='footer'>Cordialement,<br>Gestionnaire de Tâches</p>
                        </div>
                        </body>
                        </html>
                        ";

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: no-reply@tonsite.com\r\n";

            // Envoi du mail
            if (mail($user['email'], $subject, $message, $headers)) {
                  // Marquer le rappel comme envoyé pour ce nombre de jours
                  $sentReminders[$days] = true;
                  $taskModel->updateReminderSent($task['Id_tasks'], json_encode($sentReminders));
                  echo "Rappel envoyé à {$user['email']} pour la tâche {$task['task']} ($days jours)\n";
            } else {
                  echo "Erreur envoi mail à {$user['email']} pour la tâche {$task['task']}\n";
            }
      }
}
