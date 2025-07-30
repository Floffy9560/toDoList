 <?php

      require 'models/User.php';
      $user = new User();

      $status = $_GET['status'] ?? 'invalid';

      $message = match ($status) {
            'success' => "Votre compte a été activé avec succès.",
            'invalid' => "Le lien de validation est invalide ou déjà utilisé.",
            default => "Une erreur est survenue.",
      };



      $token = $user->getToken();
      $to = $_GET['eMail'];
      $subject = "Validez votre inscription";
      $link = "https://f-l-o-x.alwaysdata.net/valider?token=$token";
      $messageMail = "Cliquez sur ce lien pour valider votre compte ";
      // Envoi du mail de confirmation
      // $test = mail($to, $subject, $message);

      render('confirmation', false, [
            'message' => $message,
            'subject' => $subject,
            'to' => $to,
            'link' => $link,
            'messageMail' => $messageMail,
            'status' => $status,
      ]);
