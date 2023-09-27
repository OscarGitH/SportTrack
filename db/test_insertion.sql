-- R3.01 - Base de données SQLite3
-- Test d'insertion de données dans la base de données

-- Supprimer toutes les données des tables
DELETE FROM Données;
DELETE FROM Activité;
DELETE FROM Utilisateur;

-- Activer les contraintes de clé étrangère
PRAGMA foreign_keys = ON;

-- Remettre à 0 les auto-incréments
DELETE FROM sqlite_sequence WHERE name = 'Utilisateur';
DELETE FROM sqlite_sequence WHERE name = 'Activité';
DELETE FROM sqlite_sequence WHERE name = 'Données';


-- Insertion de données valides dans la table Utilisateur
INSERT INTO Utilisateur (nom, prenom, dateNaissance, genre, taille, poids, mail, MDP)
VALUES ('Pierre', 'Noé', '04/11/2004', 'M', 1.80, 75, 'noe@gmail.com', 'mdptest#');

INSERT INTO Utilisateur (nom, prenom, dateNaissance, genre, taille, poids, mail, MDP)
VALUES ('Pavoine', 'Oscar', '14/11/2004', 'Autre', 1.70, 75, 'oscar@gmail.com', 'motdepasse');

INSERT INTO Utilisateur (nom, prenom, dateNaissance, genre, taille, poids, mail, MDP)
VALUES ('Test', 'Michelle', '14/11/2000', 'F', 1.70, 75, 'testmichelle@gmail.com', 'bonjour');

INSERT INTO Utilisateur (nom, prenom, dateNaissance, genre, taille, poids, mail, MDP)
VALUES ('Test', 'Michel', '14/11/2000', 'M', 1.70, 75, 'michel@gmail.com', 'bonjour');

INSERT INTO Utilisateur (idUtilisateur, nom, prenom, dateNaissance, genre, taille, poids, mail, MDP)
VALUES (5, 'Bazin', 'Maelys', '5/11/2000', 'F', 1.65, 75, 'maelys@gmail.com', 'bonjour');


-- Insertion de données invalides dans la table Utilisateur
-- L'idUtilisateur 1 existe déjà
INSERT INTO Utilisateur (idUtilisateur, nom, prenom, dateNaissance, genre, taille, poids, mail, MDP)
VALUES (1, 'Dupont', 'Alice', '10/05/1990', 'F', 1.65, 60, 'alice@gmail.com', 'motdepasse');

-- mot de passe trop court
INSERT INTO Utilisateur (nom, prenom, dateNaissance, genre, taille, poids, mail, MDP)
VALUES ('Pierre', 'Noé', '04/11/2004', 'M', 1.80, 75, 'noe@gmail.com', 'mdp');

-- adresse mail invalide
INSERT INTO Utilisateur (nom, prenom, dateNaissance, genre, taille, poids, mail, MDP)
VALUES ('Pierre', 'Noé', '04/11/2004', 'M', 1.80, 75, 'noegmail', 'mdptest#');

INSERT INTO Utilisateur (nom, prenom, dateNaissance, genre, taille, poids, mail, MDP)
VALUES ('Pierre', 'Noé', '04/11/2004', 'M', 1.80, 75, 'noegmail.fr', 'mdptest#');

INSERT INTO Utilisateur (nom, prenom, dateNaissance, genre, taille, poids, mail, MDP)
VALUES ('Pierre', 'Noé', '04/11/2004', 'M', 1.80, 75, 'noe@gmail', 'mdptest#');

-- genre invalide
INSERT INTO Utilisateur (nom, prenom, dateNaissance, genre, taille, poids, mail, MDP)
VALUES ('Pierre', 'Noé', '04/11/2004', 'W', 1.80, 75, 'noe@gmail.com', 'mdptest#');


-- Insertion de données valides dans la table Activité
INSERT INTO Activité (lUtilisateur, date, description, temps, distance, vitesseMoyenne, vitesseMax, altitudeTotal, moyenneFréquenceCardiaque, maxFréquenceCardiaque, minFréquenceCardiaque)
VALUES (1, '2023-09-11', 'Course à pied', '01:30:00', 10.5, 7.0, 12.0, 100.0, 150, 175, 130);

INSERT INTO Activité (idActivité, lUtilisateur, date, description, temps, distance, vitesseMoyenne, vitesseMax, altitudeTotal, moyenneFréquenceCardiaque, maxFréquenceCardiaque, minFréquenceCardiaque)
VALUES (2, 2, '2023-09-10', 'Cyclisme', '02:00:00', 25.0, 12.5, 30.0, 200.0, 140, 160, 120);


-- Insertion de données invalides dans la table Activité
-- L'utilisateur 6 n'existe pas
INSERT INTO Activité (lUtilisateur, date, description, temps, distance, vitesseMoyenne, vitesseMax, altitudeTotal, moyenneFréquenceCardiaque, maxFréquenceCardiaque, minFréquenceCardiaque)
VALUES (6, '2023-09-09', 'Course à pied', '01:30:00', 10.5, 7.0, 12.0, 100.0, 150, 175, 130);

-- Format de temps invalide
INSERT INTO Activité (idActivité, lUtilisateur, date, description, temps, distance, vitesseMoyenne, vitesseMax, altitudeTotal, moyenneFréquenceCardiaque, maxFréquenceCardiaque, minFréquenceCardiaque)
VALUES (1, 1, '2023-09-08', 'Course à pied', '1:30', 10.5, 7.0, 12.0, 100.0, 150, 175, 130);

-- Insertion de données valides dans la table Données
INSERT INTO Données (lActivité, date, description, temps, frequenceCardiaque, latitude, longitude, altitude)
VALUES (1, '2023-09-11', 'Données de la course à pied', '00:00:15', 160, 40.7128, -74.0060, 50);

INSERT INTO Données (idDonnées, lActivité, date, description, temps, frequenceCardiaque, latitude, longitude, altitude)
VALUES (2, 2, '2023-09-10', 'Données du cyclisme', '00:01:00', 130, 34.0522, -118.2437, 100);


-- Insertion de données invalides dans la table Données
-- L'activité 8 n'existe pas
INSERT INTO Données (lActivité, date, description, temps, frequenceCardiaque, latitude, longitude, altitude)
VALUES (8, '2023-09-09', 'Données de la course à pied', '00:00:15', 160, 40.7128, -74.0060, 50);

-- Format de temps invalide
INSERT INTO Données (idDonnées, lActivité, date, description, temps, frequenceCardiaque, latitude, longitude, altitude)
VALUES (3, 1, '2023-09-08', 'Données de la course à pied', '15', 160, 40.7128, -74.0060, 50);