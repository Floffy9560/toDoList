<?php ob_start() ?>

<section>

    <?php

    $bloque = isset($error) && str_starts_with($error, 'Trop de tentatives');

    if (!empty($error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($message_mail_error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($message_mail_error) ?>
        </div>
    <?php endif; ?>


    <?php
    if (!isset($_SESSION['pseudo'])) : ?>
        <form action="" method="POST">

            <div style="display: none;">
                <label for="honeypot">Saississez votre code</label>
                <input type="text" name='fake_email' id="honeypot">
            </div>

            <div class="infos">
                <input type="text" name="pseudo" placeholder="Pseudo" autocomplete="username" <?= $bloque ? 'disabled' : '' ?> required>
                <?php if (!empty($data['error']['pseudo'])) : ?>
                    <small style="color: red;">
                        <?= htmlspecialchars($data['error']['pseudo']) ?>
                    </small>
                <?php endif; ?>
            </div>

            <div class="infos">
                <div class="inputPwd">
                    <input type="password" name="password" placeholder="Password" autocomplete="current-password" id="password" <?= $bloque ? 'disabled' : '' ?> required>
                    <i class="bi bi-eye" id="openEye"></i>
                    <i class="bi bi-eye-slash" id="closeEye"></i>
                </div>
                <?php if (!empty($data['error']['password'])): ?>
                    <?php foreach ($data['error']['password'] as $err): ?>
                        <small style="color: red; display: block;">
                            <?= htmlspecialchars($err) ?>
                        </small>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="submit" <?= $bloque ? 'disabled' : '' ?>>Se connecter</button>
            <a href="inscription" id="inscription">S'inscrire</a>

        </form>
        <small id="forget_password" style="cursor: pointer;">Mot de passe oublié</small>

    <?php else : ?>
        <h2>Vous êtes déjà connecté avec le pseudo : <?= htmlspecialchars($_SESSION['pseudo']); ?> </h2>
        <a href="/">Retour à l'accueil !</a>
    <?php endif; ?>


    <div class="form_forget_password">

        <?php if (!empty($message_mail_succes)): ?>
            <div class="succes-message error-message">
                <?= htmlspecialchars($message_mail_succes) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="forget">
            <label for="email">Adresse e-mail :</label>
            <input type="email" name="email_confirmation" id="email" placeholder="Votre email" required>
            <button type="submit">Réinitialiser le mot de passe</button>
            <button type="button" id="close_forget">Annuler</button>
        </form>
        <small>Vous recevrez un email pour créer un nouveau mot de passe.</small>

    </div>

</section>


<?php
render('default', true, [
    'title' => "Connexion",
    'style' => "assets/css/connexion-inscription.css",
    'js' => "assets/js/connexion.js",
    'content' => ob_get_clean(),
]); ?>