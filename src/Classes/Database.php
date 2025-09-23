<?php
// src/Classes/Database.php

class Database {
    private static $instance = null; // contiendra l'instance PDO

    // Empêcher l'instanciation directe
    private function __construct() {}

    // Empêcher le clonage
    private function __clone() {}

    // Connexion unique (Singleton)
    public static function getConnection() {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    "mysql:host=127.0.0.1;dbname=ecoride;charset=utf8",
                    "root",
                    ""
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base : " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
