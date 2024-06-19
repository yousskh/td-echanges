<?php

session_start();

if (isset($_SESSION['username'])) {
    header('Location: app.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TD ECHANGES</title>
</head>
<body>
    <form action="../scripts/login.php" method="POST">
        <label>
            Nom d'utilisateur
            <input type="text" name="username">
        </label>
        <label>
            Mot de passe
            <input type="password" name="password">
        </label>
        <button type="submit">Connexion</button>
<?php

if(isset($_GET['error'])) {
    $err = $_GET['error'];
    switch ($err) {
        case 1:
            echo "<p style='color:red'>Nom d'utilisateur ou mot de passe incorrect.</p>";
            break;
        case 2:
            echo "<p style='color:red'>Veuillez entrer un nom d'utilisateur et un mot de passe.</p>";
            break;
        case 3:
            echo "<p style='color:red'>Veuillez vous reconnecter.</p>";
            break;
        default:
            header('Location: index.php');
    }
}
?>
    </form>
</body>
</html>
