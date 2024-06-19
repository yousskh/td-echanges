<?php

session_start();

try {
    $db = new SQLite3('../../../../db/tdechanges');
    $db->enableExceptions(true);
} catch (Exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}