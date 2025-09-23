CREATE DATABASE IF NOT EXISTS ecoride_db;

CREATE USER IF NOT EXISTS 'utilisateur'@'localhost' IDENTIFIED BY 'mot_de_passe';
GRANT ALL PRIVILEGES ON ecoride_db.* TO 'utilisateur'@'localhost';
FLUSH PRIVILEGES;

USE ecoride_db;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(15),
    adresse VARCHAR(255),
    date_de_naissance DATE,
    pseudo VARCHAR(50) UNIQUE,
    photo_de_profil BLOB,
    role_id INT DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des rôles
CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

-- Table des marques de voitures (focus écologique)
CREATE TABLE IF NOT EXISTS marques (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE,
    pays_origine VARCHAR(50),
    type_specialite ENUM('electrique', 'hybride', 'thermique', 'mixte') DEFAULT 'mixte',
    score_ecologique INT CHECK (score_ecologique >= 1 AND score_ecologique <= 10),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des voitures (priorité écologique)
CREATE TABLE IF NOT EXISTS voitures (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    marque_id INT,
    modele VARCHAR(100),
    numero_immatriculation VARCHAR(20) UNIQUE,
    type_de_carburant ENUM('electrique', 'hybride', 'essence', 'diesel') DEFAULT 'electrique',
    couleur VARCHAR(50),
    date_premiere_immatriculation DATE,
    annee INT CHECK (annee >= 1900 AND annee <= YEAR(CURDATE())),
    nombre_places INT DEFAULT 4 CHECK (nombre_places >= 1 AND nombre_places <= 9),
    consommation_100km DECIMAL(4,2) COMMENT 'kWh/100km pour électrique, L/100km pour thermique',
    autonomie_km INT COMMENT 'Autonomie en kilomètres',
    emission_co2 INT DEFAULT 0 COMMENT 'g CO2/km - 0 pour électrique',
    score_ecologique INT CHECK (score_ecologique >= 1 AND score_ecologique <= 10),
    certification_verte ENUM('crit_air_0', 'crit_air_1', 'crit_air_2', 'crit_air_3', 'crit_air_4', 'crit_air_5') DEFAULT 'crit_air_0',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (marque_id) REFERENCES marques(id) ON DELETE SET NULL
);

-- Table des covoiturages (avec impact écologique)
CREATE TABLE IF NOT EXISTS covoiturages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    conducteur_id INT NOT NULL,
    voiture_id INT,
    lieu_depart VARCHAR(255) NOT NULL,
    lieu_arrivee VARCHAR(255) NOT NULL,
    date_depart DATE NOT NULL,
    heure_depart TIME NOT NULL,
    nombre_places_disponibles INT NOT NULL CHECK (nombre_places_disponibles >= 1),
    prix_par_personne DECIMAL(10,2) NOT NULL CHECK (prix_par_personne >= 0),
    distance_km INT,
    duree_estimee_minutes INT,
    co2_economise_grammes INT DEFAULT 0 COMMENT 'CO2 économisé grâce au covoiturage',
    statut ENUM('actif', 'complet', 'annule', 'termine') DEFAULT 'actif',
    description TEXT,
    preferences TEXT COMMENT 'Non fumeur, musique, etc.',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (conducteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (voiture_id) REFERENCES voitures(id) ON DELETE SET NULL
);

-- Table des réservations
CREATE TABLE IF NOT EXISTS reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    covoiturage_id INT NOT NULL,
    passager_id INT NOT NULL,
    nombre_places_reservees INT NOT NULL DEFAULT 1 CHECK (nombre_places_reservees >= 1),
    statut ENUM('en_attente', 'confirmee', 'annulee', 'terminee') DEFAULT 'en_attente',
    message_passager TEXT COMMENT 'Message du passager au conducteur',
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (covoiturage_id) REFERENCES covoiturages(id) ON DELETE CASCADE,
    FOREIGN KEY (passager_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reservation (covoiturage_id, passager_id)
);

-- Table des avis
CREATE TABLE IF NOT EXISTS avis (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `evaluateur_id` int(11) NOT NULL,
    `evalue_id` int(11) NOT NULL,
    `covoiturage_id` int(11) NOT NULL,
    `note` int(11) NOT NULL CHECK (`note` >= 1 AND `note` <= 5),
    `commentaire` text DEFAULT NULL,
    `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `evaluateur_id` (`evaluateur_id`),
    KEY `evalue_id` (`evalue_id`),
    KEY `covoiturage_id` (`covoiturage_id`),
    FOREIGN KEY (`evaluateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`evalue_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`covoiturage_id`) REFERENCES `covoiturages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table de configuration écologique
CREATE TABLE IF NOT EXISTS configuration (
    id INT PRIMARY KEY AUTO_INCREMENT,
    parametre VARCHAR(50) NOT NULL UNIQUE,
    valeur VARCHAR(255) NOT NULL,
    description TEXT,
    categorie ENUM('ecologie', 'general', 'securite', 'prix') DEFAULT 'general',
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des statistiques écologiques par utilisateur
CREATE TABLE IF NOT EXISTS stats_ecologiques (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    km_covoiturees INT DEFAULT 0,
    co2_economise_total_grammes INT DEFAULT 0,
    nb_covoiturages_proposes INT DEFAULT 0,
    nb_covoiturages_pris INT DEFAULT 0,
    score_ecoride INT DEFAULT 0 COMMENT 'Score écologique global de l\'utilisateur',
    date_derniere_maj TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Ajout de la contrainte de rôle
ALTER TABLE utilisateurs ADD FOREIGN KEY (role_id) REFERENCES roles(id);

-- Ajouter la colonne crédits à la table utilisateurs
ALTER TABLE utilisateurs ADD COLUMN credits_eco INT DEFAULT 20 COMMENT 'Crédits écologiques de l\'utilisateur';

-- Mettre à jour les utilisateurs existants pour qu'ils aient 20 crédits
UPDATE utilisateurs SET credits_eco = 20 WHERE credits_eco IS NULL;

-- Table des tentatives de connexion
CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `attempts` int(11) DEFAULT 1,
    `last_attempt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `attempted_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `email_ip` (`email`, `ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des sessions utilisateur
CREATE TABLE IF NOT EXISTS `user_sessions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `session_token` varchar(255) DEFAULT NULL,
    `ip_address` varchar(45) NOT NULL,
    `user_agent` text NOT NULL,
    `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
    `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `is_active` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table des rôles avec permissions
CREATE TABLE IF NOT EXISTS `roles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nom` varchar(50) NOT NULL,
    `description` text DEFAULT NULL,
    `permissions` json DEFAULT NULL,
    `niveau` int(11) DEFAULT 1,
    `actif` tinyint(1) DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insérer les rôles par défaut
INSERT INTO `roles` (`id`, `nom`, `description`, `permissions`, `niveau`) VALUES
(1, 'admin', 'Administrateur système', '["admin", "manage_users", "manage_trips", "view_analytics", "manage_system"]', 100),
(2, 'employe', 'Employé EcoRide', '["manage_trips", "moderate_content", "customer_support", "view_analytics"]', 50),
(3, 'utilisateur', 'Utilisateur standard', '["create_trip", "book_trip", "manage_profile", "leave_review"]', 10);

-- Ajouter la colonne role_id aux utilisateurs si pas présente
ALTER TABLE `utilisateurs` ADD COLUMN IF NOT EXISTS `role_id` int(11) DEFAULT 3;
ALTER TABLE `utilisateurs` ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

-- Mettre à jour les utilisateurs existants
UPDATE `utilisateurs` SET `role_id` = 3 WHERE `role_id` IS NULL;

-- Insertion des rôles
INSERT INTO roles (libelle, description) VALUES 
('utilisateur', 'Utilisateur standard pouvant proposer et réserver des covoiturages'),
('ecodriver', 'Conducteur certifié véhicule écologique avec bonus'),
('moderateur', 'Modérateur pouvant gérer les avis et signalements'),
('administrateur', 'Administrateur système avec tous les droits');

-- Insertion des marques écologiques prioritaires
INSERT INTO marques (nom, pays_origine, type_specialite, score_ecologique) VALUES 
('Tesla', 'États-Unis', 'electrique', 10),
('BYD', 'Chine', 'electrique', 9),
('Nissan', 'Japon', 'electrique', 8),
('Renault', 'France', 'electrique', 8),
('Volkswagen', 'Allemagne', 'electrique', 7),
('BMW', 'Allemagne', 'electrique', 7),
('Mercedes', 'Allemagne', 'electrique', 7),
('Hyundai', 'Corée du Sud', 'electrique', 8),
('Kia', 'Corée du Sud', 'electrique', 8),
('Peugeot', 'France', 'electrique', 7),
('Toyota', 'Japon', 'hybride', 6),
('Lexus', 'Japon', 'hybride', 6);

-- Configuration écologique par défaut
INSERT INTO configuration (parametre, valeur, description, categorie) VALUES 
('bonus_electrique', '20', 'Bonus en % pour véhicules électriques', 'ecologie'),
('bonus_hybride', '10', 'Bonus en % pour véhicules hybrides', 'ecologie'),
('co2_reference_voiture', '120', 'Émission CO2 moyenne voiture particulière (g/km)', 'ecologie'),
('score_min_ecodriver', '50', 'Score minimum pour devenir EcoDriver', 'ecologie'),
('prix_max_km', '0.10', 'Prix maximum par kilomètre', 'prix');

-- Index pour optimiser les performances
CREATE INDEX IF NOT EXISTS idx_email ON utilisateurs(email);
CREATE INDEX IF NOT EXISTS idx_pseudo ON utilisateurs(pseudo);
CREATE INDEX IF NOT EXISTS idx_date_depart ON covoiturages(date_depart);
CREATE INDEX IF NOT EXISTS idx_lieu_depart ON covoiturages(lieu_depart);
CREATE INDEX IF NOT EXISTS idx_lieu_arrivee ON covoiturages(lieu_arrivee);
CREATE INDEX IF NOT EXISTS idx_statut_covoiturage ON covoiturages(statut);
CREATE INDEX IF NOT EXISTS idx_type_carburant ON voitures(type_de_carburant);
CREATE INDEX IF NOT EXISTS idx_score_ecologique ON voitures(score_ecologique);
CREATE INDEX IF NOT EXISTS idx_conducteur ON covoiturages(conducteur_id);
CREATE INDEX IF NOT EXISTS idx_passager ON reservations(passager_id);