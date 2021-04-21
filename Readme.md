# ECF
---
Liens vers Jira : https://dkoindev.atlassian.net/jira/software/projects/BANK/boards/1
---
### Environement de développement :

Langages : PHP 8.0.3.

Versionning : Git.

Serveur de développement : Symfony server et Webpack dev-server.

Serveur de prod: PHP 8.0.3, Mysql(Jaws DB).

System : WINDOWS 10 64bit.

IDE : PHPSTORM.

----
### Deploiement : HEROKU

1.  Creer un compte HEROKU
2.  Instalez le cli de Heroku https://devcenter.heroku.com/articles/heroku-cli
3.  Pour les utilisateurs de windows si le systeme d'exploitation vous bloque "Windows smart screen". Utiliser la cmd
    pour executer le heroku.exe ou si vous le pouvez ignorez l'avertissement.
4.  Utilisez la commande heroku login pour vous identifier.
5.  Avoir un depot Git existant ou en créer un.
6.  Utilisez la commande heroku create pour créer un dyno (app) sur heroku, par la même occasion cela va lier le depot git au dyno
7.  Reglez vos variables d'environnement. Deux méthodes, soit grace à la commande heroku config:set "Variables a configurer"="Valeur".
    Soit en ajoutant un fichier .env.prod avec les variables configuré a l'interieur.
8.  Tapez la commande hroku open pour ouvrir votre app une fois le code deployé.
9.  Pour déployer votre code à nouveau, 2 méthodes s'ofrrent à vous, soit avec git push heroku master, soit en liant votre compte heroku avec github et en activant le déploiement automatique aprés chaque commit.
10. Si vous avez une application necessitant une base de données, il faudra installer l'add on correspondant à votre sgbd.
---
### Installation :
1. Installez Composer avec ce lien <https://getcomposer.org/download/>
2. Installez Symfony CLI avec ce line <https://symfony.com/download>
3. Mettez en place le projet avec la commande `symfony new --full my_project`
---
### Créez la base de données
1. Recupérez le schema de votre base de données avec l'utilitaire mysqldump ou par le biais de phpmyadmin.
2. Restaurez le schema de votre Bdd dans JawsDB avec la commande suivante `mysql -h NEWHOST -u NEWUSER -pNEWPASS NEWDATABASE < backup.sql` ou avec un client type DataGrip de jetBrains
---
