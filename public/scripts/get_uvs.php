<?php

session_start();

include('init_db.php');
if (!isset($db)) {
    exit();
}

$query = "SELECT * FROM uvs WHERE username = :username AND ver = 'new'";

try {
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $_SESSION['username']);
    $result = $stmt->execute();

} catch (Exception $e) {
    die('Erreur lors de la récupération de la liste des UV : ' . $e->getMessage());
}