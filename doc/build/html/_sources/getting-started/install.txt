============
Installation
============

Actuellement, il existe deux manières différentes d'installer le framework.

.. attention::
   Pour pouvoir utiliser le GCsystem, vous devez absolument remplir les conditions suivantes :

   - PHP 5.4 minimum
   - Réécriture d'URL activée (Apache, Nginx ou autre)
   - Chmod 755 sur les situés dans app/
   - Composer

Installation avec Composer
==========================

Le GCsystem dispose d'un petit programme séparé qui va se charger de l'installation. Tout d'abord, téléchargez ce petit programme en mode global depuis composer.Par la suite, exécutez la commande Composer ``gcsystem`` nouvellement créée dans le répertoire de votre choix, et enfin faites un simple ``composer update``

.. sourcecode:: bash

    composer global require "gcsystem/installer=dev-master"
    composer gcsystem
    composer update

Vous pouvez également utiliser la commande ``create-project`` de Composer : 

.. sourcecode:: bash
    
    composer create-project "gcsystem/gcsystem=dev-master"
    cd gcsystem
    composer update

Installation avec Github et Composer
====================================

Tout d'abord, Récupérer le framework (sans son coeur), depuis le dépôt Github :

.. sourcecode:: bash

    git clone https://github.com/fabsgc/gcsystem.git

Ensuite, il vous suffit d'exécuter la commande ``update`` de Composer pour mettre à jour toutes les dépendances (le coeur du framework :

.. sourcecode:: bash
    
    composer update