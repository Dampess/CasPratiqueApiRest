# PokeAPI - Projet Symfony

## Description

PokeAPI est un projet développé avec Symfony qui permet d'interagir avec l'API Pokémon. Il permet aux utilisateurs de rechercher des Pokémon par nom ou par type, d'afficher les détails des Pokémon, et de visualiser les différents types de Pokémon à travers une interface conviviale.

## Fonctionnalités

- Recherche de Pokémon par nom ou par type.
- Affichage des détails d'un Pokémon, y compris ses types et ses capacités.
- Interface utilisateur intuitive avec des carrousels pour parcourir les Pokémon par type.
- Fonctionnalités CRUD pour gérer les Pokémon (Créer, Lire, Mettre à jour, Supprimer).

## Technologies Utilisées

- **Symfony** : Framework PHP pour le développement web.
- **Doctrine** : Gestion de la base de données.
- **Twig** : Moteur de templates pour Symfony.
- **Bootstrap** : Framework CSS pour le design réactif.
- **MySQL** : Système de gestion de bases de données.
- **Docker** : Conteneurisation de l'application.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

- [PHP](https://www.php.net/) (version 7.4 ou supérieure)
- [Composer](https://getcomposer.org/)
- [Docker](https://www.docker.com/) (pour l'environnement de développement)
- [Node.js](https://nodejs.org/) et [npm](https://www.npmjs.com/) (pour la gestion des dépendances front-end, si nécessaire)

## Installation

Suivez les étapes ci-dessous pour installer et configurer le projet sur votre machine locale.

## Clonez le dépôt :

   ```bash
   git clone https://github.com/dampess/caspratiqueapirest.git
   cd pokeapi

## Installez les dépendances avec Composer :


Copier le code:
composer install

## Configurez votre base de données :

Modifiez le fichier .env pour ajouter vos informations de connexion à la base de données. Remplacez user, password et autres paramètres selon votre configuration.
dotenv

DATABASE_URL=mysql://user:password@db:3306/caspratiqueapirest

Lancez Docker pour l'environnement de développement :

docker-compose up -d

## Migrate les bases de données (si nécessaire) : Exécutez les migrations pour créer les tables nécessaires dans la base de données.

docker-compose exec php php bin/console doctrine:migrations:migrate

## Accédez à l'application :

Ouvrez votre navigateur et allez à http://localhost:8080.
Utilisation
Recherche de Pokémon : Utilisez la barre de recherche pour trouver un Pokémon par son nom.

Affichage des détails : Cliquez sur un Pokémon pour voir ses détails, y compris ses types et capacités.

CRUD : Les opérations CRUD peuvent être réalisées pour gérer les Pokémon dans la base de données.

Pour ajouter un Pokémon, utilisez le formulaire d'ajout.
Pour modifier un Pokémon, accédez à la page de modification.
Pour supprimer un Pokémon, utilisez le bouton de suppression sur la page de détails.

## Contribution
Les contributions sont les bienvenues ! Si vous souhaitez contribuer, veuillez suivre ces étapes :

Fork le projet.
Créez une branche pour votre fonctionnalité : git checkout -b feature/AmazingFeature.
Faites vos modifications et committez-les : git commit -m 'Add some AmazingFeature'.
Poussez votre branche : git push origin feature/AmazingFeature.
Ouvrez une Pull Request.
License
Ce projet est sous la licence MIT - voir le fichier LICENSE pour plus de détails.

## Acknowledgments

Merci à PokéAPI pour fournir les données des Pokémon.
Merci à la communauté Symfony pour le soutien et les ressources.
markdown
