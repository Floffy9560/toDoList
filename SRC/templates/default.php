<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/logo/logo_svd_noir.svg" type="image/svg+xml" style="background-color: 'white';">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php
    if (!empty($data['style'])) {
        echo '<link rel="stylesheet" href="' . $style . '" />';
    } ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>To do list
        <?php
        (!empty($data['title'])) ? ' - ' . $title : ''; ?>
    </title>
</head>

<body>

    <header>

        <a href="/" style="height: 100%;" aria-label="ToDoList – Page d’accueil">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="-20 -20 202 202"
                style="height: 100%; width: auto;">
                <g>
                    <g data-cell-id="0">
                        <g data-cell-id="1">
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-2">
                                <path d="M 0 160 L 0 0" fill="none" stroke="#000000" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-3">
                                <path d="M 160 0 L 0 0" fill="none" stroke="#000000" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-4">
                                <path d="M 80 80 L 0 80" fill="none" stroke="#000000" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-5">
                                <path d="M 0 160 L 160 160" fill="none" stroke="#000000" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-6">
                                <path d="M 108.28 51.72 L 160 0" fill="none" stroke="#000000" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-7">
                                <path d="M 0 0 L 160 160" fill="none" stroke="#000000" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-8">
                                <path d="M 0 160 L 51.72 108.28" fill="none" stroke="#000000" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-1">
                                <ellipse cx="80" cy="80" rx="40" ry="40" fill="none" stroke="#000000" stroke-width="4" />
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
        </a>

        <div class="titleAndNav">

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

        </div>

        <div class="burgerMenu">
            <span><i class="bi bi-list" id="toggleMenu"></i></span>
        </div>

    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>Copyright 2025</p>
        <p>Made with ❤️ by FLOX</p>
    </footer>

    <script src="assets/js/script.js"></script>
    <!-- <script src="<?= $js ?> "></script> -->
    <?php
    if (!empty($js)) {
        echo '<script src="' . htmlspecialchars($js) . '"></script>';
    }
    ?>

</body>

</html>