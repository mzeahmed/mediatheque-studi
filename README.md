# Aprés avoir cloné le depot

````shell
$ composer install
````

* Coommandes pour installer les depandances php

### Editer le fichier .env

* Mettre à jour le MAILER_DSN
* Editer la variable `app.admin.email` dans le fichier `config/services.yaml` pour mettre l'adresse de mail de l'
  employé, pour la reception des mails lorsqu'un habitant créer un compte.
* Eventuellement changer le transporter, `Mailjet` est installé, se rendre sur la doc pour plus d'inforation
* [Doc Symfony Mailer](https://symfony.com/doc/current/mailer.html#transport-setup)

### Lancer les fixtures

````shell
$ php bin/console doctrine:fixtures:load
````