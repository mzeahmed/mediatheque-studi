# Aprés avoir cloné le depot

* lancer la commande ci-dessus afin d'installer les dependances php

````shell
$ composer install
````

### Edition le fichier .env

* Mettre à jour le MAILER_DSN
* Editer la variable `app.admin.email` dans le fichier `config/services.yaml` à la ligne 7, pour la reception des mails
  lorsqu'un habitant créer un compte.
* Eventuellement changer le transporter, `Mailjet` est installé, se rendre sur la doc pour plus d'information
* [Doc Symfony Mailer](https://symfony.com/doc/current/mailer.html#transport-setup)

### Lancer les fixtures

````shell
$ php bin/console doctrine:fixtures:load
````

* Deux faux comptes vont être créer : `mzeahmed@employee.com` avec le mot de passe `password`,
  et `louisresident@resident.net` avec le même mot de passe
* Editer éventuellement le fichier `src/DataFixtures/AppFixtures.php` au lignes 31 et 46 pour mettre vos propres
  informations.