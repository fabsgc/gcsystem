==========
Le parefeu
==========

Pour rappel, le parefeu est un système entièrement configurable qui vous permet de pouvoir gérer aisément les droits d'accès à vos page en tenant compte du statut de connexion ainsi que du grade de vos visiteurs s'ils sont connectés. Il vous protège également contre la faille CSRF en GET et en POST.

Il existe un parefeu pour chaque module que vous créez. Comme ce fichier est assez complexe, nous allons le détailler point par point d'après un exemple :

.. sourcecode:: xml

    <?xml version="1.0" encoding="utf-8"?>
    <root>
        <roles name="role.gcs">
            <role name="USER" />
        </roles>
        <config>
            <login>
                <source name=".gcs.index" vars=""/>
            </login>
            <default>
                <source name=".gcs.gcs.profiler" vars=""/>
            </default>
            <forbidden template=".app/error/firewall">
                <variable type="lang" name="title" value="system.firewall.forbidden.title"/>
                <variable type="lang" name="message" value="system.firewall.forbidden.content"/>
            </forbidden>
            <csrf name="token.gcs" template=".app/error/firewall" enabled="true">
                <variable type="lang" name="title" value="system.firewall.csrf.title"/>
                <variable type="lang" name="message" value="system.firewall.csrf.content"/>
            </csrf>
            <logged name="logged.gcs"/>
        </config>
    </root>

Les rôles
=========

Les redirections
================

Page de connexion
-----------------

Page par défaut
---------------

Les pages d'erreur
==================

La faille CSRF
==============