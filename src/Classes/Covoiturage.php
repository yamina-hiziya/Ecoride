<?php
// src/Classes/Covoiturage.php

require_once __DIR__ . '/Database.php';

class Covoiturage extends Database {
    
    protected $conn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    public function createTrajet($depart, $arrivee, $date, $heure, $places, $prix, $userId) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO covoiturages (lieu_depart, lieu_arrivee, date_depart, heure_depart, nombre_places, prix_par_personne, conducteur_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$depart, $arrivee, $date, $heure, $places, $prix, $userId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getPublicTrajets() {
        try {
            $stmt = $this->conn->query("SELECT * FROM covoiturages ORDER BY date_depart, heure_depart");
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function getMyTrajets($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM covoiturages WHERE conducteur_id = ? ORDER BY date_depart, heure_depart");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getTrajetById($trajetId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM covoiturages WHERE id = ?");
            $stmt->execute([$trajetId]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return null;
        }
    }
    
    public function updateTrajet($trajetId, $depart, $arrivee, $date, $heure, $places, $prix) {
        try {
            $stmt = $this->conn->prepare("UPDATE covoiturages SET lieu_depart = ?, lieu_arrivee = ?, date_depart = ?, heure_depart = ?, nombre_places = ?, prix_par_personne = ? WHERE id = ?");
            $stmt->execute([$depart, $arrivee, $date, $heure, $places, $prix, $trajetId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteTrajet($trajetId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM covoiturages WHERE id = ?");
            $stmt->execute([$trajetId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}