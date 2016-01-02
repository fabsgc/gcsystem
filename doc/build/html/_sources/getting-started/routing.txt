==========
Le routing
==========

Une fois que vous avez correctement configuré le framework, il serait intéressant de pouvoir commencer à créer vos propres pages. Et la première pierre de votre site passera immanquablement par la création de nouvelles urls. Nous allons donc ici vous présenter en détail le système de routing.

Chaque module que vous créez dans le répertoire ``src`` possède son propre fichier de routing pour des raisons de lisibilité. Ce fichier nommé ``route.xml`` est situé dans le sous répertoire ``src/module/resource/config/``. Voici un exemple d'un fichier de routing :

.. sourcecode:: xml

    <?xml version="1.0" encoding="utf-8"?>
    <root>
        <route name="index" url="(/*)" action="index.default" method="get"/>
        <route name="get" url="/form(/*)" action="index.get" method="get"/>
        <group name="gcs" url="/gcs">
            <route name="lang.default" url="/lang(/*)" action="lang.default"/>
            <route name="profiler.default" url="/profiler(/*)" action="profiler.default"/>
        </group>
    </root>

Introduction
============

Ce fichier est composé de deux balises différentes : les balises ``route`` et les balises ``group``. Les balises route servent à représenter une url donnée tandis que les balises group permettent d'encapsuler des routes possédents des attributs communs (noms proches, urls proches etc.).

Ces deux balises possèdent les mêmes attributs : 

- **name** : il s'agit du nom de la route. Attention, toutes les routes d'un même module doivent avoir des noms différents. En revanche, les routes provenant de modules différents peuvent avoir des noms en commun.
- **url** : il s'agit de l'url de la route.
- **vars** : pour chaque url, vous pouvez préciser des parties variables.
- **action** : cet attribut précise quelle action de quel controller devra être invoquée. 
- **method** : il est possible de limiter la méthode d'accès à certaines pages parmi : get, post, put et delete.
- **access** : il est aussi possible de limiter l'accès à une url pour certains types d'utilisateurs.
- **logged** : on peut aussi dire si la page peut-être vu en étant connecté ou non.
- **cache** : il est possible d'enregistrer un cache totale de la page (cache html) en précisant le temps de mise en cache en seconde.

Création de routes
==================

Création d'une route simple
---------------------------

Nous souhaitons créer une page d'accueil d'un blog sans aucun paramètre permettant d'appeler l'action ``index`` du controller ``blog``. Cette page ne peut être vue que en GET. Nous écrirons alors :

.. sourcecode:: xml

    <route name="blog" url="/blog" action="blog.index" method="get"/>

Il est important de souligner que toutes les urls doivent absolument commencer par un ``/``. De plus dans l'attribut action, il faut séparer le nom du controller de l'action par un ``.``.


Gestion des routes à paramètres variables
-----------------------------------------

Il arrive très souvent que vous ayez besoin de rajouter des parties variables dans vos urls. Pour cela, le routeur du GCS s'appuie sur la syntaxe des Regex. Imaginons par exemple une route permettant d'afficher un article d'un blog. Cette route devra contenir le numéro de l'article ainsi que son titre. L'url sera donc :

.. code:: html

    url="/blog/article/([0-9]+)/(.+)"

Maintenant que l'url est écrire, il ne reste plus qu'à préciser au framework quelles sont les parties variables. Pour cela, nous utilisons l'attribut ``vars``. Il suffit d'y lister dans leur ordre d'apparition le nom des variables que vous voulez créer et le framework se chargera d'associer à chaque variable la bonne valeur : 

.. code:: html

    vars="id,title"

Grâce à cette ligne, le GCS va créer dans le tableau ``$_GET`` les clefs ``id`` et ``title``. Nous obtenons donc au final la route suivante :

.. sourcecode:: xml

    <route name="blog.article" url="/blog/article/([0-9]+)/(.+)" vars="id,title" action="blog.article" method="get"/>

Gestion des groupes de routes
-----------------------------

Il arrive parfois que vous ayez de nombreuses urls avec des points communs. Dans ce cas, il devient vite fastidieux d'écrire les parties redondantes. Pour éviter cela, le GCS propose l'utilisation des groupes. Imaginons alors l'exemple du CRUD. Nous avons dans un module 4 action permettant de lister, créer, modifier et supprimer des données. Ces urls ont beaucoup de points communs (name, url, action, logged, access, method et cache). Nous écrirons alors :

.. sourcecode:: xml

    <group name="crud" url="/crud" action="crud" method="*">
        <route name="default" url="(/*)" action="home" method="get" />
        <route name="insert" url="/insert(/*)" action="insert" method="post" />
        <route name="update" url="/update/([0-9]+)(/*)" action="update" vars="id" method="put" />
        <route name="delete" url="/delete/([0-9]+)(/*)" action="delete" vars="id" method="delete" />
    </group>

Vous pouvez remarquer que les attributs name, url et action vons se concatener tandis que l'attribut methode de la balise group va être remplacer par l'attribut method de ses enfants.

Restreindre l'accès à une url
=============================

Il existe deux façons de protéger l'accès à vos page : 

- vérifier si l'utilisateur est connecté ou non
- vérifier si l'utilisateur connecté possède les droits adéquats

Connexion de l'utilisateur
--------------------------

La vérification de la connexion de l'utilisateur se fait via l'attribut ``logged``. Il peut prendre trois valeurs : ``true``, ``false`` ou ``*`` (les deux). Si cet attribut n'est pas renseigné pour une route, la valeur par défaut est ``*``.

.. sourcecode:: xml

    <route name="blog" url="/blog" action="blog.index" method="get" logged="*"/>

Statut de l'utilisateur
-----------------------

Si jamais vous voulez que l'utilisateur soit connecté pour accéder à une page, vous pouvez, en relation avec les rôles écrits dans le parefeu, préciser quels utilisateurs peuvent voire cette page en les listant dans l'attribut ``access`` : 

.. sourcecode:: xml

    <route name="blog" url="/blog" action="blog.index" method="get" logged="true" access="ADMIN,USER"/>