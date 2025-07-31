<?php ob_start() ?>

<section>

    <?php

    if (!isset($_SESSION['pseudo'])) : ?>
        <form action="" method="POST">

            <div style="display: none;">
                <label for="time-limit">Saississez votre code</label>
                <input type="hidden" name="form_timestamp" id="time-limite" value="<?= time(); ?>">
            </div>

            <div style="display: none;">
                <label for="honeypot">Saississez votre code</label>
                <input type="text" name='fake_email' id="honeypot">
            </div>

            <div class="infos">
                <input type="text" name="pseudo" placeholder="Pseudo" autocomplete="username" require>
                <?php if (!empty($data['error']['pseudo'])) { ?>
                    <small style="color: red;">
                        <?= $data['error']['pseudo'] ?>
                    </small>
                <?php } ?>
            </div>

            <div class="infos">
                <input type="text" name="email" placeholder="E-Mail" autocomplete="email" require>
                <?php if (!empty($data['error']['email'])) { ?>
                    <small style="color: red;">
                        <?= $data['error']['email'] ?>
                    </small>
                <?php } ?>
            </div>

            <div class="infos">
                <input type="password" name="password" id="password" placeholder="Password" autocomplete="current-password" required>
                <i class="bi bi-eye" id="openEye"></i>
                <i class="bi bi-eye-slash" id="closeEye"></i>
            </div>

            <?php if (!empty($data['error']['password'])): ?>
                <small style="color: red; display: block;">
                    <?= 'Mot de passe incorrect, veuillez suivre les consignes.' ?>
                </small>
            <?php endif; ?>
            <div class="passwordVerify">
                <p class="small" data-condition="length">au moins 8 caractères</p>
                <p class="small" data-condition="uppercase">au moins une majuscule</p>
                <p class="small" data-condition="lowercase">au moins une minuscule</p>
                <p class="small" data-condition="digit">au moins un chiffre</p>
                <p class="small" data-condition="special">au moins un caractère spécial</p>
            </div>
            <div id="password-feedback" style="color: crimson; margin-top: 5px;"></div>
            <button type="submit">S'inscrire</button>

        </form>

    <?php else : ?>
        <h2>Vous êtes déjà connecté avec le pseudo : <?= htmlspecialchars($_SESSION['pseudo']); ?> </h2>
        <a href="/">Retour à l'accueil !</a>
    <?php endif; ?>

</section>

<?php
render('default', true, [
    'title' => "Inscription",
    'style' => "assets/css/connexion-inscription.css",
    'js' => "assets/js/inscription.js",
    'content' => ob_get_clean(),
]); ?>