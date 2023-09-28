# SportTrack

## Description

Ce projet a été réalisé dans le cadre de la ressources R3.01 - Développement d'applications web de l'IUT Informatique de Vannes.

SportTrack est une application web de suivi d'activités sportives.

Les fonctionnalités :

- Création d'un compte, connexion, déconnexion, consultation des informations du compte
- Ajout d'une activité sportive à partir d'un fichier JSON
- Consultation des activités sportives et des statistiques associées (distance, durée, vitesse moyenne, dénivelé...)

Pour plus d'informations, vous pouvez consulter le pdf "Présentation SportTrack"


## Technologies utilisées

- PHP 8.1
- SQLite 3
- HTML 5
- CSS 3

## Installation

### Prérequis

- PHP 8.1
- SQLite 3

### Installation

- Création de la base de données (à partir du répertoire `db/`) : `sqlite3 sporttrack.db < sporttrack.sql`
- Lancement du serveur (à la racine du projet) : `php -S localhost:8080`
- Accès à l'application : http://localhost:8080

## Auteurs

- Noé PIERRE
- Oscar PAVOINE
