<?php ob_start() ?>

<section>

    <div class="message">
        <?php if ($status === 'success'): ?>
            <h2><?= htmlspecialchars($message) ?></h2>
            <p class="small">Un email de confirmation a été envoyé à votre adresse.</p>
            <p class="small">Vérifiez votre boîte de réception pour valider votre compte.</p>
            <p class="small">Si vous ne le voyez pas, vérifiez votre dossier spam.</p>
            <button><a href="connexion">Se connecter</a></button>
        <?php else: ?>
            <h2><?= htmlspecialchars($message) ?></h2>
            <button><a href="inscription">S'inscrire à nouveau</a></button>
        <?php endif; ?>
    </div>

    <div class="testMail">
        <h2>exemple pour l'envoi d'un mail de confirmation</h2>

        <div class="leMail">
            <h3>le mail :</h3>
            <p>À : <?= htmlspecialchars($to) ?></p>
            <p>Sujet : <?= htmlspecialchars($subject) ?></p>
            <p>Message : <?= htmlspecialchars($messageMail) ?></p>
            <!-- <p>Le lien de validation : <a href="<?= htmlspecialchars($link) ?>"><?= htmlspecialchars($link) ?></a></p> -->
            <p>Le lien de validation : <a href="#"><?= htmlspecialchars($link)  ?></a>"Lien non valide"</p>

        </div>
    </div>

</section>

<?php
render('default', true, [
    'title' => "Validation du compte",
    'style' => "assets/css/confirmation.css",
    'js' => "assets/js/connexion.js",
    'content' => ob_get_clean(),
]); ?>