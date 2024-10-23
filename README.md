# API_RESERVATION

# Documentation de l'API Laravel avec Sanctum

## Table des matières

- [Introduction](#introduction)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Démarrage](#démarrage)
- [Utilisation de l'API](#utilisation-de-lapi)
- [Endpoints](#endpoints)


## Introduction

Cette documentation décrit comment installer et utiliser l'API de reservation construite avec Laravel et protégée par Sanctum. L'API permet de gérer les utilisateurs, les authentifications, faire des reservations et recevoir des notifications de nouveaux évènements.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- [PHP](https://www.php.net/manual/fr/install.php) (version 8.2 ou supérieure)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/)
- [Laravel](https://laravel.com/docs/11.x/installation)

## Installation

**Clone le dépôt :**
   git clone https://votre-repo.git


**Installer les dépendances :**
composer install


**Copier le fichier .env :**
cp .env.example .env


**Configurer l'environnement :**
Éditez le fichier .env pour configurer votre base de données et autres paramètres.


**Générer la clé d'application :**
php artisan key:generate


**Migrer la base de données :**
php artisan migrate


**Installer les dépendances front-end :**
npm install


**Compiler les assets :**
npm run dev

## Démarrage
Pour démarrer le serveur de développement, utilisez la commande suivante :
php artisan serve

Cela démarrera votre application à l'adresse http://localhost:8000.



## Utilisation de l'API

Pour utiliser l'API, vous devez d'abord vous authentifier. Voici les étapes de base :
1. Inscription d'un nouvel utilisateur
2. Connexion et obtention d'un token
3. Accès aux endpoints protégés avec le token


## Endpoints

**Voici quelques endpoints disponibles :**
    POST /api/register : Inscription d'un utilisateur
    POST /api/login : Authentification de l'utilisateur
    GET /api/user : Récupérer les informations de l'utilisateur connecté
    POST /api/logout : Déconnexion de l'utilisateur
