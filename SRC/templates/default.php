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

        <a href="/" style="height: 100%;" aria-label="ToDoList – Page d’accueil" class="header_logo">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="-20 -20 202 202"
                style="height: 100%; width: auto; color: white;">
                <g>
                    <g data-cell-id="0">
                        <g data-cell-id="1">
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-2">
                                <path d="M 0 160 L 0 0" fill="none" stroke="currentColor" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-3">
                                <path d="M 160 0 L 0 0" fill="none" stroke="currentColor" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-4">
                                <path d="M 80 80 L 0 80" fill="none" stroke="currentColor" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-5">
                                <path d="M 0 160 L 160 160" fill="none" stroke="currentColor" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-6">
                                <path d="M 108.28 51.72 L 160 0" fill="none" stroke="currentColor" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-7">
                                <path d="M 0 0 L 160 160" fill="none" stroke="currentColor" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-8">
                                <path d="M 0 160 L 51.72 108.28" fill="none" stroke="currentColor" stroke-width="4" />
                            </g>
                            <g data-cell-id="GTzlfEPMz8TtkYW5QEB7-1">
                                <ellipse cx="80" cy="80" rx="40" ry="40" fill="none" stroke="currentColor" stroke-width="4" />
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
        </a>

        <ul id="ulNav">
            <li><a href="/">Accueil</a></li>
            <li><a href="about">À propos</a></li>
            <li><a href="inscription">Inscription</a></li>
            <li><a href="connexion">Connexion</a></li>
            <?php if (!empty($_SESSION['idUser'])) {
                echo '<li><a href="deconnexion">Se deconnecter</a></li>';
                echo '<li><a href="calendar">Calendrier</a></li>';
            }; ?>
        </ul>

        <div class="user-greeting">
            <?php if (isset($_SESSION['pseudo']) && $_SESSION['pseudo'] !== null): ?>
                <strong><?= 'Bonjour ' . htmlspecialchars($_SESSION['pseudo']) . ' !' ?></strong>
            <?php endif; ?>
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
        <p>
            <a href="#" id="cookie-settings" style="color: #fff; text-decoration: underline;">Paramètres cookies</a>
        </p>
        <p><a href="politique-confidentialite">politique de confidentialite</a></p>

    </footer>

    <!-- Librairies tierces -->
    <script src="https://accounts.google.com/gsi/client" async></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    <!-- Classe utilitaire -->
    <script src="assets/js/UserSession.js" defer></script>

    <!-- Scripts généraux -->
    <script src="assets/js/default.js" defer></script>

    <!-- Scripts spécifiques à la page -->
    <?php
    if (!empty($js)) {
        echo '<script src="' . htmlspecialchars($js) . '" defer></script>';
    }
    ?>

</body>

</html>