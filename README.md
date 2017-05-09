Informations :
-----------

* Open-Source
* Version  : 3.1 Bêta
* Créateur : Fabien Beaujean
* Contributeurs : Lucas5190, t1307
* Description : framework PHP MVC de développement d'applications Web.

Spécifications :
-----------

* PHP 5.6 minimum
* Chmod en lecture écriture (755) (cache, log, console)
* rewrite_module apache activé

Installation avec Composer
-----------

Le GCsystem dispose d'un petit programme séparé qui va se charger de l'installation. 
Tout d'abord, téléchargez ce petit programme en mode global depuis composer. 
Par la suite, exécutez la commande Composer ``gcsystem`` nouvellement créée dans le répertoire de votre choix, 
et enfin faites un simple ``composer update``

```text
> composer global require "gcsystem/installer=dev-master"
> composer gcsystem
> composer update
```

Vous pouvez également utiliser la commande ``create-project`` de Composer : 

```text
> composer create-project "gcsystem/gcsystem=dev-master"
> cd gcsystem
> composer update
```

* dans le fichier app/config.php, modifiez la valeur "framework/folder" et indiquez-y le répertoire où se trouve le framework. Par exemple : projet/
* chargez l'url racine du site (/)

Installation avec Github et Composer
-----------

Tout d'abord, récupérez le framework (sans son coeur), depuis le dépôt Github :

```text
> git clone https://github.com/fabsgc/gcsystem.git
```

Ensuite, il vous suffit d'exécuter la commande ``update`` de Composer pour mettre à jour toutes les dépendances (le coeur du framework) :

```text
> composer update
```

Documentation :
-----------

* La documentation du framework est disponible ici ``doc/build/singlehtml/index.html``

Site du projet
-----------

[GCsystem : framework PHP français hautes performances][1]

[1]: http://gcs-framework.dzv.me/

Licence :
-----------

Le GCsystem est distribué sous licence [MIT](http://opensource.org/licenses/MIT)