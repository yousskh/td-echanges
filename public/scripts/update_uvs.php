<?php

session_start();

include('init_db.php');
if (!isset($db)) {
    exit();
}

$edtText = $_POST['edt']; // Récupération du texte transmis
$lines = explode("\n", $edtText); // Séparation du texte en lignes

$query = "UPDATE uvs SET ver = 'old' WHERE username = :username AND ver = 'new' AND statut != 'changed'";
try {
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $_SESSION['username']);
    $result = $stmt->execute();
} catch (Exception $e) {
    die('Erreur lors de la mise à jour des anciennes UV de l\'utilisateur : ' . $e->getMessage());
}
foreach ($lines as $line) {
    if (preg_match("/\bD(?!\w[a-zA-Z]).*\b(LUNDI|MARDI|MERCREDI|JEUDI|VENDREDI|SAMEDI|DIMANCHE)\b.*\b([01]\d|2[0-3]):[0-5]\d.*\b([01]\d|2[0-3]):[0-5]\d/", $line)) {
        $tl = str_replace(' ', '', $line);
        $uv = substr($tl, 0, 4);
        $td = '';
        if (!preg_match("/[A-Z]/", $tl[6])) {
            $td = substr($tl, 5, 2);
            $tl = substr($tl, 0, 6) . substr($tl, 7);
        } else {
            $td = $tl[5];
        }
        $jour = str_replace('.', '', substr($tl, 6, 8));
        $hdebut = substr($tl, 14, 5);
        $hfin = substr($tl, 20, 5);
        $salle = substr($tl, 31, 5);
        dbpush($db, array($uv, $td, $jour, $hdebut, $hfin, $salle));
    }
}

header('Location: ../pages/app.php?page=1&success=2');
function dbpush($db, $values) {
    $query = "INSERT INTO uvs (uv, groupe, jour, hdebut, hfin, salle, username)
SELECT :uv, :groupe, :jour, :hdebut, :hfin, :salle, :username
WHERE NOT EXISTS (
    SELECT 1 FROM uvs WHERE uv = :uv AND username = :username AND statut = 'changed'
);";
    try {
        $stmt = $db->prepare($query);
        $stmt->bindValue(':uv', $values[0]);
        $stmt->bindValue(':groupe', $values[1]);
        $stmt->bindValue(':jour', $values[2]);
        $stmt->bindValue(':hdebut', $values[3]);
        $stmt->bindValue(':hfin', $values[4]);
        $stmt->bindValue(':salle', $values[5]);
        $stmt->bindValue(':username', $_SESSION['username']);
        $stmt->execute();
    } catch (Exception $e) {
        die('Erreur lors de l\'insertion des nouvelles UV de l\'utilisateur : ' . $e->getMessage());
    }
}