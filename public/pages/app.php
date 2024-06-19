<?php

session_start();

// permission : 0 -> étudiant | 1 -> enseignant | 8 -> admin | 9 -> étudiant admin

$prenom = $_SESSION['prenom'];
$nom = $_SESSION['nom'];
$permission = $_SESSION['permission'];
$page = $_GET['page'];

if (!isset($_SESSION['username'])) {
    header("location: index.php");
}

if (!isset($permission)) {
    session_destroy();
    header('location: index.php?error=3');
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TD-Échanges | Étudiant</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;500;600;700&display=swap">
</head>
<body>
<header>
    <h1>TD-ECHANGES</h1>
</header>

<div class="page">

    <?php

    echo "<div class='equal-center'>";
    switch ($permission) {
        case 0:
            echo <<<HTML
        <div class="top-button" id="profil-button"><a href="app.php?page=1"><h4>Profil</h4></a></div>
        <div class="top-button" id="echanges-button"><a href="app.php?page=2"><h4>Échanges</h4></a></div>
        <div class="top-button" id="propositions-button"><a href="app.php?page=3"><h4>Propositions</h4></a></div>
        <div class="top-button extra-low-width" id="propositions-button"><a href="app.php?page=4"><h4>Aide</h4></a></div>
HTML;
            break;
        case 1:
            echo <<<HTML
        <div class="top-button" id="profil-button"><a href="app.php?page=1"><h4>Mes TD</h4></a></div>
        <div class="top-button" id="echanges-button"><a href="app.php?page=2"><h4>Demandes d'échanges</h4></a></div>
HTML;
            break;
        case 9:
            echo <<<HTML
        <div class="top-button" id="profil-button"><a href="app.php?page=1"><h4>Profil</h4></a></div>
        <div class="top-button" id="echanges-button"><a href="app.php?page=2"><h4>Échanges</h4></a></div>
        <div class="top-button" id="propositions-button"><a href="app.php?page=3"><h4>Propositions</h4></a></div>
        <div class="top-button adm" id="admin-button"><a href="app.php?page=admin"><h4>Panel admin</h4></a></div>
HTML;
            break;
        default:
            session_destroy();
            header('location: index.php?error=3');
    }
    echo "</div>";

    switch ($page) {
        case 1:
            echo '
    <div class="block-frame" id="profil">
    
        <div class="block">
            <h2>Mes enseignements</h2>
            <hr class="line">
            <div id="uv-list">
            
            <form id="update_status" action="../scripts/update_status.php" method="POST"></form>';
            include('../scripts/get_uvs.php');
            if (isset($result)) {
                while ($row = $result->fetchArray()) {
                    echo "<div class ='list-elt ";
                    if ($row[8] == "changed") {
                        echo "changed' title='Ce TD a été échangé.'";
                    } elseif ($row[8] == "indispo") {
                        echo "indispo' title='Ce TD ne peut pas être échangé.'";
                    } elseif ($row[8] == "autorise") {
                        echo "autorise' title='Vous n&apos;avez pas besoin d&apos;échanger ce TD.'";
                    }
                    $jour = ucwords(strtolower($row[2]));
                    echo "'>
                        <h4 class='encadre' title='Matière : $row[0]'><img src='../svg/cours.svg' alt='matière'>&nbsp;$row[0]</h4>
                        <h4 class='encadre' title='Groupe n°$row[1]'><img src='../svg/groupe.svg' alt='groupe'>&nbsp;$row[1]</h4>
                        <h4 class='encadre' title='Le $jour'><img src='../svg/calendrier.svg' alt='jour'>&nbsp;$jour</h4>
                        <h4 class='encadre' title='De $row[3] à $row[4]'><img src='../svg/horaires.svg' alt='horaires'>&nbsp;$row[3] - $row[4]</h4>
                        <h4 class='encadre' title='Salle : $row[5]'><img src='../svg/salle.svg' alt='jour'>&nbsp;$row[5]</h4>
                        <fieldset>
                            <label>Public<input form='update_status' type='checkbox' name='public[]' ";
                        if ($row[8] == "public") {
                            echo "checked ";
                        } elseif ($row[8] == "changed" || $row[8] == "indispo" || $row[8] = "autorise") {
                            echo "disabled ";
                        }
                    echo "value='public-$row[0]'></label>
                        </fieldset>
                    </div>";
                }
            }

            echo '</div>
        </div>';

            echo <<<HTML
        <div class="block">
            <button id="open-import-popup" class="mini-button" role="button"
                    onclick="document.getElementById('profil-popup').style.display = 'flex'">Importer des enseignements</button>
            <button class="mini-button" type="submit" form="update_status">Confirmer</button>
        </div>
        
    </div>
HTML;
            break;
        case 2:
            echo '
            <div class="block-frame" id="echanges">

                <div class="block extended-width">
                    <h2>Recherche de TD disponibles</h2>
                    <hr class="line">
                        <div id="uv-list-2">';
            if (!isset($_GET['search'])) {
                include('../scripts/get_uv_list.php');
            } else {
                $result = $_SESSION['result'];
            }
            if (isset($data)) {
                $count = 0;
                foreach ($data as $row) {
                    $count++;
                    $dispo = $row['nb_dispo'];
                    $jour = ucwords(strtolower($row[2]));
                    echo "<div class='list-elt elt-align-left'>";
                    if ($dispo > 1) {
                        echo "<h4 class='encadre right-margin' title='Il y a $dispo personnes qui proposent ce TD'>$dispo propositions disponibles</h4>";
                    } else {
                        echo "<h4 class='encadre right-margin' title='Il y a une personne qui propose ce TD'>1 proposition disponible</h4>";
                    }
                    echo "<h4 class='encadre right-margin' title='Matière : $row[0]'><img src='../svg/cours.svg' alt='matière'>&nbsp;$row[0]</h4>
                        <h4 class='encadre right-margin' title='Groupe n°$row[1]'><img src='../svg/groupe.svg' alt='groupe'>&nbsp;Groupe $row[1]</h4>
                        <h4 class='encadre right-margin' title='Le $jour'><img src='../svg/calendrier.svg' alt='jour'>&nbsp;$jour</h4>
                        <h4 class='encadre right-margin' title='De $row[3] à $row[4]'><img src='../svg/horaires.svg' alt='horaires'>&nbsp;$row[3] - $row[4]</h4>
                        <h4 class='encadre right-margin' title='Salle : $row[5]'><img src='../svg/salle.svg' alt='jour'>&nbsp;$row[5]</h4>
                        </div>";
                } if ($count == 0) {
                    echo "<h4>Aucun TD à afficher.</h4>";
                }
            }
            echo '
                    </div>                    
                </div>
                <form action="../scripts/get_uv_list.php" method="POST">
                    <fieldset class="vertical align-left"><legend><h4>&nbsp;Recherche&nbsp;</h4></legend>
                        <input name="uv_name" type="text" placeholder="Nom de l\'UV">
                        <select>
                            <option name="day" value="" disabled selected>Jour</option>
                            <option>Lundi</option>
                            <option>Mardi</option>
                            <option>Mercredi</option>
                            <option>Jeudi</option>
                            <option>Vendredi</option>
                        </select>
                        <input name="group" type="number" placeholder="Groupe de TD">
                        <input name="search" type="hidden" value="1">
                        <button type="submit">Rechercher</button>
                    </fieldset>
                </form>
            </div>';
            break;
        case 3:
            echo <<<HTML
            <div class="block-frame" id="propositions">
        
                <div class="block">
                </div>
        
            </div>

HTML;
            break;
        case 'admin':
            if ($_SESSION['permission'] == 9) {
                echo <<<HTML
<div class="block-frame" id="profil">
    
        <div class="block less-extended-width">
            <h2>Panel admin</h2>
            <hr class="line">
        </div>
        
        <div class="block low-width">
        
        </div>
            
            
HTML;
            } else {
                header('location: app.php');
            }
            break;
        default:
            echo <<<HTML
            <div class="block-frame" style="display: flex">
            
                <div class="block">
                    <h2>Comment ça fonctionne ?</h2>
                    <hr class="line">
                    <h4 class="unicolor">
                        <br>TD-ÉCHANGES permet de faciliter les échanges de TD pour les étudiants et enseignants, et de
                        débarrasser les groupes Facebook de toutes les demandes d'échange. Pour cela, c'est très simple, la
                        procédure se déroule en 3 étapes :<br><br>
                        1. Vous importez vos UV dans votre profil, seuls vos TD seront conservés.<br><br>
                        2. À partir du menu Échanges, vous sélectionnez les TD qui ne vous conviennent pas. Ensuite, vous
                        choisissez avec quels TD vous souhaitez les échanger.<br><br>
                        3. Vous recevez toutes les propositions correspondantes à votre demande dans l'onglet Propositions. Dès
                        qu'une personne d'un TD demandé souhaite un échange avec celui que vous proposez, vous êtes mis en relation.
                    </h4>
                </div>
            
            </div>
HTML;
    }

    echo "</div>

<footer>
    <div class='align-right'><h6>Connecté 
    en tant que $prenom $nom | Étudiant</h6><form action='../scripts/logout.php'><button id='logout' class='cancel-style' type='submit'><h6>Déconnexion</h6></button></form></div>
</footer>"

    ?>

<div id="profil-popup" class="popup">
    <div class="popup-page">
        <div class="horizontal-layout">
            <div class="popup-header">
                <h4>Importer des enseignements</h4>
                <img class="closePopup" src="../svg/close.svg" alt="Fermer" onclick="document.getElementById('profil-popup').style.display = 'none'"></div>
        </div>
        <label for="edt"></label>
        <form id="update_uvs" action="../scripts/update_uvs.php" method="POST"></form>
        <textarea form="update_uvs" name="edt" id="edt" placeholder='Collez ici vos UV reçues par mail (comme pour le site générant des EDT).
        Attention, cela supprimera vos UV déjà ajoutées.
Exemple :
CM13       C 1     JEUDI... 10:15-12:15,F1,S=FA106
CM13       D 2     MARDI... 16:30-18:30,F1,S=FA420
CM13       T 1 A   JEUDI... 14:30-18:30,F2,S=ES109

LA13       D14     MERCREDI 10:15-12:15,F1,S=FC207

LO01       C 1     LUNDI... 08:00-10:00,F1,S=FA104
LO01       D 2     MERCREDI 14:15-16:15,F1,S=FA309
LO01       T 4 A   MERCREDI 16:30-18:30,F2,S=FB116

NF02       C 1     MARDI... 08:30-10:00,F1,S=FA108
NF02       D 1     MARDI... 10:15-12:15,F1,S=FA417
NF02       T 3 B   MARDI... 14:15-16:15,F2,S=J210C

SC21       C 1     LUNDI... 14:15-16:15,F1,S=FA108
SC21       D 3     LUNDI... 18:30-19:30,F1,S=FA306

SY01       C 1     VENDREDI 10:15-12:15,F1,S=FA205
SY01       D 4     JEUDI... 08:00-10:00,F1,S=FA616

TC00       D 1     MARDI... 18:45-20:45,F1,S=FA100' cols="40" rows="30"></textarea>
        <div class="centered"><button form="update_uvs" id="confirm-edt" class="mini-button" type="submit">Importer</button></div>
    </div>
</div>

<div id="notification-frame">
</div>

<script src="../js/client.js"></script>
</body>
</html>