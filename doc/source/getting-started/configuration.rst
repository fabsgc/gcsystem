=============
Configuration
=============

La configuration du framework est contenue dans un fichier unique située à la racine : ``config.php``. Ce fichier contient une partie qui ne doit pas être modifiée (la majorité des chemins d'accès), ainsi que d'une partie personnalisable. Nous allons ici voir chacune de ces options en détails.

Connexion à la base de données
==============================

La connexion se présente sous la forme d'un tableau PHP contenant les champs habituels d'une connexion avec PDO (hôte, nom d'utilisateur, mot de passe, nom de la base). Le système propose également de choisir le driver bien qu'actuellement, seul PDO soit supporté. De plus, on peut également choisir le SGBD (seul Mysql supporté pour l'instant), ainsi que l'encodage.

.. sourcecode:: php
    
    <?php
    $db['hostname'] = "localhost";
    $db['username'] = "root";
    $db['password'] = "";
    $db['database'] = "test";
    $db['driver']   = "pdo";
    $db['type']     = "mysql";
    $db['charset']  = "utf8";
    $db['collation']= "utf8_unicode_ci";

Une fois que la connexion est configurée, vous pouvez choisir de l'activer ou non. Par défaut celle-ci est désactivée. Le paramètre à modifier est la constante ``DATABASE`` :

.. sourcecode:: php
    
    <?php
    define('DATABASE', true);

Parefeu
=======

Le parefeu est un système entièrement configurable qui vous permet de pouvoir gérer aisément les droits d'accès à vos page en tenant compte du statut de connexion ainsi que du grade de vos visiteurs s'ils sont connectés. Il vous protège également contre la faille CSRF en GET et en POST (son fonctionnement sera détaillé par la suite dans un chapitre dédié).

.. sourcecode:: php
    
    <?php
    define('SECURITY', true);

Antispam
========

L'antispam est un petit système complémentaire qui permet grâce à une reconnaissance par IP de pouvoir empêcher certaines personnes de spammer des pages en mettant en place une limite de requête pour une période donnée.

.. sourcecode:: php
    
    <?php
    define('SPAM', true);

Encodage
========

Vous pouvez spécifier dans la configuration l'encodage qui sera renvoyé par le serveur au client. Par défaut, il s'agit de l'UTF-8

.. sourcecode:: php
    
    <?php
    define('CHARSET', 'UTF-8');

Répertoire racine
=================

Il peut arriver dans certains cas que le framework ne soit pas situé à la racine du site. Par exemple si le framework est situé dans un dossier ``site``, il faudra mettre la constante ``FOLDER`` à la valeur ``site/`` .

.. sourcecode:: php
    
    <?php
    define('FOLDER', 'site/');

Langue par défaut
=================

Le framework est capable de gérer l'internationalisation. Pour cela, il vous propose d'indiquer une langue par défaut dans la configuration (le français). Par conséquent, à condition que la langue ne soit pas changée par la suite, ce sera cette langue qui sera utilisée à l'affichage. L'utilisation des fichiers de langue vous sera expliquée ensuite.

.. sourcecode:: php
    
    <?php
    define('LANG', 'fr');

Choix de l'environnement
========================

Il existe deux environnements : ``development`` et ``production``. La seule différence est qu'en production les erreurs ne sont plus affichées.

.. sourcecode:: php
    
    <?php
    define('ENVIRONMENT', 'development');

Maintenance
===========

Si jamais vous devez procéder à une maintenant sur le site et donc le rendre inaccessible pour une période donnée, vous pouvez le spécifier directement ici. Si la maintenant est activée, une page spéciale s'affichera quelque soit l'url entrée. La template de cette page se situe dans ``app/resource/template/system/maintenance.tpl``. L'utilisation des templates sera expliquée dans le chapitre sur les vues.

.. sourcecode:: php
    
    <?php
    define('MAINTENANCE', false);

Profiler
========

Le profiler est un formidable outil qui vous apportera une grande quantité d'informations sur chaque page qui est exécutée par le framework. Il enregistre par exemple le contenu de toutes les variables superglobales, les requêtes SQL, les fichiers chargés etc. Nous vous recommandons grandement de l'utiliser pour des raisons évidentes.

.. sourcecode:: php
    
    <?php
    define('PROFILER', true);

Échappement des variables superglobales
=======================================

Si vous activez ces paramètres, les variables ``$_GET`` et ``$_POST`` seront automatiquement échappées en utilisant la fonction ``htmlspecialchars``

.. sourcecode:: php
    
    <?php
    define('SECURE_GET', true);
    define('SECURE_POST', true);

Constantes utilisateur
======================

Le framework vous permet de définir dans un fichier XML vos propres constantes. Pour différencier ces constantes de celles du framework, il est recommandé de leur attribuer un préfix. L'utilisation des constantes personnalisées vous sera expliquée par la suite.

.. sourcecode:: php
    
    <?php
    define('DEFINE_PREFIX', 'USER_');

Cache
=====

Le GCsystem met à votre disposition un puissant système de cache que vous pouvez activer ou désactiver via la constante ``CACHE_ENABLED``. De plus, pour encore augmenter la performance de l'application, le framework peut mettre en cache toute la configuration du système grâce à ``CACHE_CONFIG``. Nous vous recommandons de n'activer cette option que si vous êtes en mode production car cette option nécessite de supprimer le cache après chaque modification de la configuration. Enfin si vous le souhaitez vous pouvez faire en sorte de hasher le nom des fichiers de cache avec ``CACHE_SHA1``.

.. sourcecode:: php
    
    <?php
    define('CACHE_ENABLED', true);
    define('CACHE_CONFIG', false);
    define('CACHE_SHA1', false);

Logs
====

Le GCsystem procède à l'enregistrement de différents logs dans ``app/log/`` : 

- les erreurs de php
- les erreurs propres au framework
- l'historique des requêtes
- les requêtes SQL

Vous pouvez évidemment désactiver ces logs.

.. sourcecode:: php
    
    <?php
    define('LOG_ENABLED', true);

Affichage des erreurs
=====================

Les erreurs propres au framework sont de 3 types. Vous pouvez activer ou désactiver l'affichage de ces erreurs. Notez que si vous désactiver l'affichage, ces erreurs sont quand même enregistrées dans les fichiers de log s'ils sont activés.

.. sourcecode:: php
    
    <?php
    define('DISPLAY_ERROR_FATAL', true);
    define('DISPLAY_ERROR_EXCEPTION', true);
    define('DISPLAY_ERROR_ERROR', true);

Minifier sortie html
====================

Lorsque vous affichez des pages HTML, le framework vous permet de les minifier en supprimant tous les espaces inutiles afin de les alléger.

.. sourcecode:: php
    
    <?php

Asset manager
=============

L'asset manager est un module qui vous permet de regrouper vos fichiers CSS et JS en un seul fichier dont le contenu est compressé et mis en cache. Cela vous permet d'avoir une organisation de fichiers lourdes mais claire sans pour autant entraîner le téléchargement d'un trop grand nombre de fichiers par le client. Le fonctionnement de ce module vous sera expliqué dans un chapitre séparé.

.. sourcecode:: php
    
    <?php
    define('ASSET_MANAGER', true);

Chemins vers les ressources web
===============================

Ces chemins sont quelques raccourcis qui vous permettent d'accéder plus facilement aux ressources; que vous soyez dans les fichiers de templates ou les fichiers php.

.. sourcecode:: php
    
    <?php
    define('HTML_WEB_PATH', FOLDER.'/'.WEB_PATH);
    define('PHP_WEB_PATH', WEB_PATH);

    define('IMAGE_PATH_PHP', WEB_PATH.'image/');
    define('CSS_PATH_PHP', WEB_PATH.'css/');
    define('JS_PATH_PHP', WEB_PATH.'js/');
    define('FILE_PATH_PHP', WEB_PATH.'file/');