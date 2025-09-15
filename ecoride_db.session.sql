CREATE DATABASE IF NOT EXISTS ecoride_db;

-- db/sql/schema.sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    last_name VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    address VARCHAR(255),
    birth_date DATE,
    username VARCHAR(50) UNIQUE,
    profile_picture BLOB
profile_picture BLOB
);

CREATE TABLE cars (
id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT NOT NULL,
model VARCHAR(50),
registration_number VARCHAR(20) UNIQUE,
fuel_type VARCHAR(20),
color VARCHAR(20),
first_registration_date DATE,
year INT,
FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL UNIQUE,
);

CREATE TABLE avis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    covoiturage_id INT NOT NULL,
    note INT CHECK (note >= 1 AND note <= 5),
    commentaire TEXT,
    statuse ENUM('en_attente', 'approuve', 'refuse') DEFAULT 'en_attente',
);

CREATE TABLE covoiturages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    date_depart DATETIME NOT NULL,
    date_arrivee DATETIME NOT NULL,
    lieu_depart VARCHAR(255) NOT NULL,
    lieu_arrivee VARCHAR(255) NOT NULL,
    heure_depart TIME NOT NULL,
    heure_arrivee TIME NOT NULL,
    status ENUM('en_attente', 'accepte', 'refuse', 'termine') DEFAULT 'en_attente',
    nombre_places INT NOT NULL,
    prix_par_personne DECIMAL(10,2) NOT NULL,
    -- trajet_id INT NOT NULL,
    -- conducteur_id INT NOT NULL,
    FOREIGN KEY (trajet_id) REFERENCES trajets(id),
    FOREIGN KEY (conducteur_id) REFERENCES utilisateurs(id)
);

CREATE TABLE marque (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE configuration (
    id INT PRIMARY KEY AUTO_INCREMENT,
    parametre VARCHAR(50) NOT NULL UNIQUE,
    valeur VARCHAR(255) NOT NULL
);

create TABLE parametres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE,
    valeur VARCHAR(255) NOT NULL
);  

CREATE TABLE employes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(15),
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE ADMINISTRATEURS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(15),
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE RESERVATION (
    id INT PRIMARY KEY AUTO_INCREMENT,
    covoiturage_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nombre_places_reservees INT NOT NULL,
    FOREIGN KEY (covoiturage_id) REFERENCES covoiturages(id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);
