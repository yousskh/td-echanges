<?php

session_start();

include('init_db.php');
if (!isset($db)) {
    exit();
}

$username = $_POST["username"];
$password = $_POST["password"];

$query = "SELECT password, prenom, nom, permission FROM users WHERE username = :username";

try {
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username);
    $result = $stmt->execute();

    if ($row = $result->fetchArray()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['prenom'] = $row['prenom'];
            $_SESSION['nom'] = $row['nom'];
            $_SESSION['permission'] = $row['permission'];
            header('Location: ../pages/app.php');
        } else {
            header('Location: ../pages/index.php?error=1');
        }
    } else {
        header('Location: ../pages/index.php?error=2');
    }
    exit();
} catch (Exception $e) {
    die('Erreur lors de l\'authentification : ' . $e->getMessage());
}
