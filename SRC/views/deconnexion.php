<?php ob_start() ?>

<section>

    <h2>Etes vous sure de vouloir vous déconnecter ??</h2>

    <form action="" method="POST">
        <input type="hidden" name="deconnexion">
        <button type="submit">Se déconnecter</button>
    </form>

</section>

<?php
render('default', true, [
    'title' => 'Deconnexion',
    'style' => "assets/css/connexion-inscription.css",
    'content' => ob_get_clean(),
]) ?>