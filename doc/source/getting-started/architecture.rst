========================
Architecture du GCsystem
========================

Avant de commencer à développer votre application, il est important que vous preniez connaissance de l'architecture du GCsystem.

Organisation du Framework
=========================

La struture du dossier du framework est similaire à celle-ci :

.. code:: html

    app/
        cache/
        log/
        resource/
    src/
        gcs/
            controller/
            model/
            resource/
    vendor/
    web/

Le dossier ``app`` contient les fichiers de cache et de log. Il comprend également toutes les ressources qui seront communes à tous les modules de votre application (configuration, entités, évènements, fichiers de langues, validation de formulaire, template).

Le dossier ``src`` est le coeur de votre application, c'est là que vous placerez tous vos modules (contrôleurs, modèles, templates etc.), ainsi que les ressources propres à chaque module (configuration, entités, évènements, fichiers de langues, validation de formulaire).

Le dossier ``vendor`` est lié à l'intégration de Composer. C'est dans ce dossier que se trouve le framework en tant que tel. Vous n'aurez jamais à modifier vous-même ce dossier.


Cheminement d'une requête
=========================

Le premier fichier appelé avant le routing  est ``index.php``. Il s'agit du contrôleur principal de l'application qui va se charger de lancer le moteur. Son appel est paramétré dans le .htaccess situé à la racine du projet :

.. code:: apache

    php_flag short_open_tag off
    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php [QSA,L]

    php_flag zlib.output_compression on

.. attention::
   Si vous utilisez un autre logiciel qu'Apache, il vous faudra configurer l'équivalent du .htaccess par vous-même.

Une fois le moteur lancé, celui-ci va se charger d'analyser l'URL soumise afin d'en retirer les informations qui permettront d'appeler les bons contrôleurs des bonnes rubriques.

Parallèlement à l'appel des contrôleurs, le moteur se charge également des processus tels que l'antispam, le parefeu, les crons, la connexion à la base de données, le cache des pages, la création des constantes personnalisées etc.

Comme un bon croquis vaut mieux qu'un long discours, voici un résumé du fonctionnement interne du framework :

.. figure:: ../images/getting-started/query.png
   :align: center