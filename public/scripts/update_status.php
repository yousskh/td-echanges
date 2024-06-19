<?php

session_start();

include('init_db.php');
if (!isset($db)) {
    exit();
}


$query = "UPDATE uvs SET statut = 'default' WHERE username = :username AND ver = 'new' AND statut = 'public'";

$uvs_to_update = $_POST['public'];
try {
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $_SESSION['username']);
    $result = $stmt->execute();
} catch (Exception $e) {
    die('Erreur lors de la récupération de la liste des UV : ' . $e->getMessage());
}

foreach ($uvs_to_update as $uv_unformatted) {
    $uv = explode('-', $uv_unformatted)[1];
    $query = "UPDATE uvs SET statut = 'public' WHERE username = :username AND ver = 'new' AND uv = :uv";
    try {
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $_SESSION['username']);
        $stmt->bindValue(':uv', $uv);
        $result = $stmt->execute();
    } catch (Exception $e) {
        die('Erreur lors de la récupération de la liste des UV : ' . $e->getMessage());
    }
}

header('Location: ../pages/app.php?page=1&success=1');