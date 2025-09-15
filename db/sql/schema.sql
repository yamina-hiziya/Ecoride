-- db/sql/schema.sql
CREATE DATABASE IF NOT EXISTS ecoride_db;
USE ecoride_db;

CREATE USER 'utilisateur'@'localhost' IDENTIFIED BY 'mot_de_passe';
-- créer l'utilisateur avec un mot de passe sécurisé
GRANT ALL PRIVILEGES ON ecoride_db .* TO 'utilisateur'@'localhost';
-- donne tous les privilèges sur la base de données à l'utilisateur

FLUSH PRIVILEGES;  -- applique les changements de privilèges

-- Table des utilisateurs (users)
-- Nous combinons les utilisateurs, employés et administrateurs dans une seule table
-- avec un role_id, ce qui est une meilleure pratique.
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(15),
    adresse VARCHAR(255),
    date_de_naissance DATE,
    pseudo VARCHAR(50) UNIQUE,
    photo_de_profil BLOB
);

-- Table des voitures (cars)
CREATE TABLE voitures (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    modele VARCHAR(50),
    numero_immatriculation VARCHAR(20) UNIQUE,
    type_de_carburant VARCHAR(20),
    couleur VARCHAR(20),
    date_premiere_immatriculation DATE,
    annee INT,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);


-- Table des covoiturages (covoiturages)
CREATE TABLE covoiturages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    conducteur_id INT NOT NULL,
    lieu_depart VARCHAR(255) NOT NULL,
    lieu_arrivee VARCHAR(255) NOT NULL,
    date_depart DATE NOT NULL,
    heure_depart TIME NOT NULL,
    nombre_places INT NOT NULL,
    prix_par_personne DECIMAL(10,2) NOT NULL,
    statut ENUM('en_attente', 'accepte', 'refuse', 'termine') DEFAULT 'en_attente',
    cree_le DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conducteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des avis (avis)
CREATE TABLE avis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    covoiturage_id INT NOT NULL,
    note INT CHECK (note >= 1 AND note <= 5),
    commentaire TEXT,
    status ENUM('en_attente', 'approuve', 'refuse') DEFAULT 'en_attente',
    FOREIGN KEY (utilisateur_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (covoiturage_id) REFERENCES covoiturages(id) ON DELETE CASCADE
);

-- Table des réservations (reservations)
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    covoiturage_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nombre_places_reservees INT NOT NULL,
    FOREIGN KEY (covoiturage_id) REFERENCES covoiturages(id) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Les tables suivantes sont optionnelles pour ton projet et peuvent être simplifiées ou supprimées si tu manques de temps
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL UNIQUE
);
-- Ajouter une colonne role_id à la table users
ALTER TABLE users ADD COLUMN role_id INT DEFAULT 1;
ALTER TABLE users ADD FOREIGN KEY (role_id) REFERENCES roles(id);
INSERT INTO roles (libelle) VALUES ('Utilisateur'), ('Employe'), ('Administrateur');

CREATE TABLE marque (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE configuration (
    id INT PRIMARY KEY AUTO_INCREMENT,
    parametre VARCHAR(50) NOT NULL UNIQUE,
    valeur VARCHAR(255) NOT NULL
);
