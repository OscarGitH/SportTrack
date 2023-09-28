# SportTrack

## Description

SportTrack est une application web de suivi d'activités sportives.

Les fonctionnalités :

- Création d'un compte, connexion, déconnexion, consultation des informations du compte
- Ajout d'une activité sportive à partir d'un fichier JSON
- Consultation des activités sportives et des statistiques associées (distance, durée, vitesse moyenne, dénivelé...)

## Installation

### Prérequis

- PHP 8.1
- SQLite 3

### Installation

- Création de la base de données : `sqlite3 model/sporttrack.db < model/sporttrack.sql`
- Lancement du serveur (à la racine du projet) : `php -S localhost:8080`
- Accès à l'application : http://localhost:8080

## Auteurs

- Noé PIERRE
- Oscar PAVOINE
