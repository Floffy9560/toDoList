<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php
    if (!empty($data['style'])) {
        echo '<link rel="stylesheet" href="' . $style . '" />';
    } ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>To do list</title>
</head>

<body>

    <header>

        <div class="menuLaptop">
            <h1>
                <?php if (isset($_SESSION['pseudo']) && $_SESSION['pseudo'] !== null): ?>
                    <?= 'Bonjour ' . htmlspecialchars($_SESSION['pseudo']) . '!😉' ?>
                <?php endif; ?>
                Voici ta liste de choses à faire!!</h1>
        </div>

        <ul id="ulNav">
            <li><a href="/">Accueil</a></li>
            <li><a href="about">À propos</a></li>
            <li><a href="inscription">Inscription</a></li>
            <li><a href="connexion">Connexion</a></li>
            <?php if (!empty($_SESSION['idUser'])) {
                echo '<li><a href="deconnexion">Se deconnecter</a></li>';
            }; ?>
        </ul>

        <div class="burgerMenu">
            <span><i class="bi bi-list" id="toggleMenu"></i></span>
        </div>

    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>Copyright 2025</p>
    </footer>

    <script src="assets/js/script.js"></script>
    <script src="<?= $js ?> "></script>

</body>

</html>