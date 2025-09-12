<?php ob_start() ?>

<section class="privacy-policy">
      <h1>Politique de Confidentialité</h1>

      <p>
            Nous attachons une grande importance à la protection de vos données personnelles.
            Cette politique explique quelles informations nous collectons, pourquoi nous les collectons,
            et comment nous les utilisons, conformément au <strong>Règlement Général sur la Protection des Données (RGPD)</strong>.
      </p>

      <h2>1. Données collectées</h2>
      <p>
            Lors de votre utilisation de notre site, nous pouvons collecter :
      </p>
      <ul>
            <li>Vos informations de connexion (adresse e-mail, identifiant) lors de l’inscription ou de la connexion via Google.</li>
            <li>Des cookies techniques nécessaires au bon fonctionnement du site.</li>
            <li>Votre consentement relatif aux cookies optionnels.</li>
      </ul>

      <h2>2. Utilisation des données</h2>
      <p>
            Les données collectées sont utilisées uniquement pour :
      </p>
      <ul>
            <li>Vous permettre de vous connecter et d’accéder à votre compte.</li>
            <li>Assurer le bon fonctionnement et la sécurité du site.</li>
            <li>Améliorer votre expérience utilisateur.</li>
      </ul>

      <h2>3. Partage des données</h2>
      <p>
            Nous ne vendons ni ne louons vos données.
            Certaines données peuvent être transmises à des prestataires tiers (comme Google pour la connexion) uniquement dans le cadre de leur service.
      </p>

      <h2>4. Cookies</h2>
      <p>
            Les cookies sont utilisés pour mémoriser vos préférences et faciliter votre navigation.
            Vous pouvez accepter ou refuser les cookies optionnels à tout moment via la bannière ou les
            <a href="#" id="cookie-settings">paramètres cookies</a>.
      </p>

      <h2>5. Vos droits</h2>
      <p>
            Conformément au RGPD, vous disposez des droits suivants :
      </p>
      <ul>
            <li>Droit d’accès, de rectification et de suppression de vos données.</li>
            <li>Droit de limiter ou d’opposer leur traitement.</li>
            <li>Droit à la portabilité de vos données.</li>
      </ul>
      <p>
            Pour exercer vos droits, vous pouvez nous contacter à l’adresse suivante :
            <a href="mailto:contact@votresite.com">contact@votresite.com</a>.
      </p>

      <h2>6. Sécurité</h2>
      <p>
            Nous mettons en place des mesures techniques et organisationnelles pour protéger vos données contre tout accès non autorisé.
      </p>

      <h2>7. Modifications</h2>
      <p>
            Cette politique peut être mise à jour pour refléter les évolutions légales ou techniques.
            Nous vous invitons à la consulter régulièrement.
      </p>

      <p class="last-update">
            Dernière mise à jour : <?= date("d/m/Y") ?>
      </p>
</section>

<?php
render('default', true, [
      "title" => 'politique-confidentialite',
      'style' => "assets/css/politique-confidentialite.css",
      "content" => ob_get_clean(),
]) ?>