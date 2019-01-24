# Mak'API Website

**Mak'API** est un outil de création, gestion et d'hébergement d'API en ligne. Il permet à n'importe qui de pouvoir facilement construire son API.

## Guide d'installation

### Environnement

Pour commencer à travailler au sein de notre repository, il est nécessaire dans un premier temps, de préparer votre environnement. Pour avoir l'environnement adéquat, vous devez vous assurer d'avoir les outils suivant, avec la version correspondante ou supérieur :
* Symfony - v4.2
* Docker - v18.09
* npm - v6.4.1
* yarn  - 1.13

### Installation

L'installation se fait en plusieurs étapes. Pour vous aider, ce guide part de l'étape du clônage :
1. `git clone https://github.com/Mak-API/website.git .`

Le "**.**" est facultatif, il permet d'indiquer que vous voulez clôner votre repository à l'endroit où vous lancer la commande. Vous pouvez y indiquer un chemin ou simplement renommer le futur répertoire de travail. La commande précédente va nommer votre répertoire à l'identique du nom du repository, mais dans le cas où vous voudriez modifier le nom en, par exemple, **makapi-website**, il suffira de taper la commande suivante : `git clone https://github.com/Mak-API/website.git makapi-website`.

Après ça, vous devez installer les différents outils, grâce aux commandes suivantes :
2. `composer install`
3. `yarn install`

_**En cas d'erreur lors du lancement d'une commande, nous vous invitons à vous dirriger vers les différents éditeurs pour en connaître la raison.**_

Après ça, votre espace de travail est presque entièrement terminé ! Mais avant d'aller plus loin, nous allons compiler l'ensemble des fichiers nécessaire pour les différentes vues de **Mak'API**.

4. `yarn encore dev`

Cette dernière commande peut, sur certains OS, activer une notification pour indiquer le résultat de la compilation. Si elle se termine avec la notion de "**build successfully**", c'est que l'installation de votre espace de travail est terminée.

Enfin, il reste une dernière étape pour Docker, avec une simple commande :
5. `docker volume create postgres_database`

### Start your development

Il vous reste quelques dernières choses à savoir pour commencer à travailler :
* `yarn encore dev` => Permet de lancer Webpack et de réaliser la compilation des fichiers de travail (dans Webpack : scss, js, etc.)
* `yarn encore dev --watch` => Permet de lancer cette commande à chaque sauvegarde des fichiers de travail de Webpack.
* `php bin/console s:r` => Permet de lancer le serveur de Symfony, attention, cette commande permet de n'avoir que des vues sans données en base.
* `docker-compose up` => Permet de lancer les containers de Docker pour travailler sur **Mak'API**. Nous vous proposons de ne pas de le lancer en arrière plan, mais si vous souhaitez le faire, il suffit de rajouter `-d`, comme cela :


# OLD VERSION
# Mak'API website

## Description


# README Dev

## Edit database var in .env

If you edit :
* DATABASE_PASSWORD
* DATABASE_USER
* DATABASE_NAME
* DATABASE_URL
* DATABASE_PORT

Please, delete this folder :
> /docker/db