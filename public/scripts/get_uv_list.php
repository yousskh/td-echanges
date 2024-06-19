<?php

session_start();

include('init_db.php');
if (!isset($db)) {
    exit();
}

$query = "SELECT *, COUNT(*) as nb_dispo
FROM uvs
WHERE ver = 'new' AND statut = 'public' AND username != :username";

if (!empty($_POST['uv_name'])) {
    $query = $query . " AND uv LIKE :uv_name";
}

if (!empty($_POST['day'])) {
    $query = $query . " AND jour = :day";
}

if (!empty($_POST['group'])) {
    $query = $query . " AND groupe = :group";
}

$query_end = "
GROUP BY uv, groupe, salle
ORDER BY uv, groupe;
";

$query = $query . $query_end;

try {
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $_SESSION['username']);
    $stmt->bindValue(':uv_name', $_POST['uv_name']);
    $stmt->bindValue(':day', $_POST['day']);
    $stmt->bindValue(':group', $_POST['group']);
    $result = $stmt->execute();
    $data = [];
    while ($row = $result->fetchArray()) {
        $data[] = $row;
    }
    if (isset($_POST['search'])) {
        $_SESSION['result'] = $data;
        header("location: ../pages/app.php?page=2&search=1");
    }
} catch (Exception $e) {
    die('Erreur lors de l\'obtention de la liste des UV : ' . $e->getMessage());
}