<?php ob_start() ?>

<section>

    <?php
    if (!isset($_SESSION['pseudo'])) : ?>
        <form action="" method="POST">

            <div class="infos">
                <input type="text" name="pseudo" placeholder="Pseudo" require>
                <?php if (!empty($data['error']['pseudo'])) { ?>
                    <small style="color: red;">
                        <?= $data['error']['pseudo'] ?>
                    </small>
                <?php } ?>
            </div>

            <div class="infos">
                <input type="text" name="email" placeholder="E-Mail" require>
                <?php if (!empty($data['error']['email'])) { ?>
                    <small style="color: red;">
                        <?= $data['error']['email'] ?>
                    </small>
                <?php } ?>
            </div>

            <div class="infos">
                <input type="password" name="password" placeholder="Password" required>
                <?php if (!empty($data['error']['password'])): ?>
                    <?php foreach ($data['error']['password'] as $err): ?>
                        <small style="color: red; display: block;">
                            <?= htmlspecialchars($err) ?>
                        </small>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
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