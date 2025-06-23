<?php ob_start() ?>

<section>

    <?php
    if (!isset($_SESSION['pseudo'])) : ?>
        <form action="" method="POST">

            <div class="infos">
                <input type="text" name="pseudo" placeholder="Pseudo" autocomplete="username" required>
                <?php if (!empty($data['error']['pseudo'])) : ?>
                    <small style="color: red;">
                        <?= htmlspecialchars($data['error']['pseudo']) ?>
                    </small>
                <?php endif; ?>
            </div>

            <div class="infos">
                <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
                <?php if (!empty($data['error']['password'])): ?>
                    <?php foreach ($data['error']['password'] as $err): ?>
                        <small style="color: red; display: block;">
                            <?= htmlspecialchars($err) ?>
                        </small>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="submit">Se connecter</button>
            <a href="inscription">S'inscrire</a>
            <?php if (!empty($error)) {
                echo "<span class='error'>$error</span>";
            } ?>
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
    'content' => ob_get_clean(),
]); ?>