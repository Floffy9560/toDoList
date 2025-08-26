<?php ob_start() ?>

<section>

    <?php

    $bloque = isset($error) && str_starts_with($error, 'Trop de tentatives');

    if (!empty($error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error) ?>
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

    <?php else : ?>
        <h2>Vous êtes déjà connecté avec le pseudo : <?= htmlspecialchars($_SESSION['pseudo']); ?> </h2>
        <a href="/">Retour à l'accueil !</a>
    <?php endif; ?>

</section>


<?php
render('default', true, [
    'title' => "Connexion",
    'style' => "assets/css/connexion-inscription.css",
    'js' => "assets/js/connexion.js",
    'content' => ob_get_clean(),
]); ?>