# toDoList

🟢 Fonctionnalités de base :

Ajouter une tâche ✅

Modifier une tâche ✅

Supprimer une tâche ✅

Marquer comme terminée / non terminée ✅

Afficher la liste des tâches ✅

🟡 Fonctionnalités intermédiaires :

Filtrer les tâches : toutes / terminées / en cours

Trier les tâches : par date, priorité, ordre alphabétique

Définir une date d’échéance

Ajouter une priorité (basse, moyenne, haute)

Rechercher une tâche

Catégoriser les tâches (travail, perso, urgent, etc.)

Enregistrer les données localement (via localStorage ou IndexedDB)

🔴 Fonctionnalités avancées:

Notifications ou rappels (via Web Notifications ou mails)

Synchronisation entre appareils (nécessite un backend)

Partage de la liste avec d'autres utilisateurs

Collaboration en temps réel

Ajout de fichiers ou pièces jointes

Commentaires ou sous-tâches

Mode hors-ligne

Dark mode / personnalisation de thème

Statistiques (ex. nombre de tâches accomplies)

🔐 Principe de protection
À chaque tentative de connexion :

Si les identifiants sont faux → on incrémente failed_attempts et on met à jour last_attempt.

Si les identifiants sont bons → on réinitialise failed_attempts à 0.

Si failed_attempts dépasse un seuil (ex. 5) dans un laps de temps (ex. 10 minutes), bloquer temporairement la tentative.

🔒 Pour aller encore plus loin (en bonus) :
Stocker aussi l’IP de l’utilisateur et le user-agent pour repérer les attaques plus fines

Ajouter un captcha après 3 ou 5 tentatives

Envoyer un e-mail de sécurité à l’utilisateur si beaucoup de tentatives échouées

(bonus) augmenter le temps par exemple au bout de 3 tantative bloquer 2 mins au bout de 5 bloquer 10 mins au bout de 10 bloquer 30mins et au bout de 15 bloquer 1h30

DELETE FROM ppllmm_project;
ALTER TABLE ppllmm_project AUTO_INCREMENT=1;
DELETE FROM ppllmm_tasks;
ALTER TABLE ppllmm_tasks AUTO_INCREMENT=1;
DELETE FROM ppllmm_users;
ALTER TABLE ppllmm_users AUTO_INCREMENT=1;
