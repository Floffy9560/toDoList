<?php ob_start() ?>

<section>

    <div class="containerAbout">

        <span>À propos</span>

        <p><strong>Nom du projet :</strong> Gestionnaire de Tâches</p>

        <p>
            Ce projet a été conçu pour offrir aux utilisateurs une solution complète afin d'organiser efficacement leurs projets et tâches quotidiennes.
            Il s'agit d'une application PHP orientée objet, intégrant un système de gestion de sessions et de base de données.
            Développé dans le cadre d’un projet personnel, il m'a permis de me perfectionner en programmation orientée objet et en utilisation de JavaScript avec AJAX.
        </p>

        <p><strong>Stack technique :</strong></p>
        <ul>
            <li><strong>PHP :</strong> logique serveur, gestion des sessions utilisateurs et communication sécurisée avec la base de données.</li>
            <li><strong>MySQL :</strong> base de données relationnelle pour gérer projets, tâches et utilisateurs, avec des requêtes optimisées.</li>
            <li><strong>HTML5 :</strong> structure sémantique et accessible, facilitant la lecture et la navigation.</li>
            <li><strong>CSS3 :</strong> mise en page responsive avec Flexbox et Grid, utilisation de dégradés et d’une identité visuelle cohérente.</li>
            <li><strong>JavaScript :</strong> utilisation d’AJAX pour charger et mettre à jour les données sans rechargement de page,
                et délégation d’événements pour gérer efficacement les interactions dynamiques.</li>
            <li><strong>Bootstrap Icons :</strong> intégration d’icônes modernes et légères pour améliorer l’expérience utilisateur.</li>
        </ul>

        <p>
            <strong>Auteur :</strong> F-L-O-X.
            <br>
            <strong>GitHub :</strong> <a href="https://github.com/Floffy9560">F-L-O-X</a>
        </p>

        <p>
            Pour tout retour ou question : <a href="mailto:auvrayflorian@aol.com">auvrayflorian@aol.com</a>
        </p>

    </div>

</section>


<?php
render('default', true, [
    'title' => 'A propos',
    'style' => 'assets/css/about.css',
    'content' => ob_get_clean(),
]) ?>