<?php ob_start() ?>

<section>

    <div class="containerAbout">

        <span>À propos</span>

        <p><strong>Nom du projet :</strong> Gestionnaire de Tâches</p>

        <p>
            Ce projet a été créé pour permettre aux utilisateurs de mieux organiser leurs projets au quotidien.
            Il s'agit d'une application PHP orientée objet, avec un système de gestion de session et de base de données.
            Développé dans le cadre d’un projet personnel afin de me perfectionner avec la POO et js ajax.
        </p>

        <p><strong>Technologies utilisées</strong></p>
        <ul>
            <li>PHP orienté objet</li>
            <li>MySQL</li>
            <li>HTML5 / CSS3 (sans framework lourd)</li>
        </ul>

        <p>
            <strong>Auteur :</strong> F-L-O-X.
            <br>
            <strong>GitHub :</strong> <a href="https://github.com/Floffy9560">F-L-O-X</a>
        </p>

        <p>
            <strong>Technologies :</strong> PHP, MySQL, HTML/CSS ,JS
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