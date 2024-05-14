<?php
include 'connexion.php';
include 'home.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>


        table {
            border-collapse: collapse;
            width: 100%;
            color:black;
        }

        th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
        
    </style>
    <style>
tr {
  border: 2px solid black;
  border-collapse: collapse;
}
th {
  background-color: #96D4D4;
}
</style>
</head>
<body>
<h2 style="text-align:center">Etat de Carnet de Commande Matelas</h2>
<form method="GET">
    <label for="etat_filtre">Filtrer par état :</label>
    <select name="etat_filtre" id="etat_filtre">
        <option value="LAN" <?php if(isset($_GET['etat_filtre']) && $_GET['etat_filtre'] == 'LAN') echo 'selected'; ?>>LANCER</option>
        <option value="DCL" <?php if(isset($_GET['etat_filtre']) && $_GET['etat_filtre'] == 'DCL') echo 'selected'; ?>>DECLARÉ</option>
        <option value="DEC" <?php if(isset($_GET['etat_filtre']) && $_GET['etat_filtre'] == 'DEC') echo 'selected'; ?>>DECOMPOSÉ</option>
        <option value="REC" <?php if(isset($_GET['etat_filtre']) && $_GET['etat_filtre'] == 'REC') echo 'selected'; ?>>RECEPTIONNÉ</option>
        <option value="TER" <?php if(isset($_GET['etat_filtre']) && $_GET['etat_filtre'] == 'TER') echo 'selected'; ?>>TERMINÉ</option>
        <option value="SOL" <?php if(isset($_GET['etat_filtre']) && $_GET['etat_filtre'] == 'SOL') echo 'selected'; ?>>SOLDÉ</option>
    </select>

    <label for="depot_filtre">Filtrer par DEPOT :</label>
    <select name="depot_filtre" id="depot_filtre">
        <option value="SI5" <?php if(isset($_GET['depot_filtre']) && $_GET['depot_filtre'] == 'SI5') echo 'selected'; ?>>SITE 5</option>
        <option value="MED" <?php if(isset($_GET['depot_filtre']) && $_GET['depot_filtre'] == 'MED') echo 'selected'; ?>>SITE 1</option>
        
    </select>
    <button type="submit">Filtrer</button>
</form>
<br>

<?php

$etat_filtre = isset($_GET['etat_filtre']) ? $_GET['etat_filtre'] : 'LAN';
$depot_filtre = isset($_GET['depot_filtre']) ? $_GET['depot_filtre'] : 'SI5';

// Exécutez la requête SQL
$sql = "SELECT 
            T_LIBELLE,
            WOL_CHARLIBRE2, 
            WOL_CHARLIBRE3, 
            WOL_LIGNEORDRE,
            WOL_DATECREATION, 
            WOL_DATEDEBCONST, 
            WOL_DateFinConst,
            WOL_CODEARTICLE, 
            GA_LIBELLE, 
            cast(WOP_QACCSAIS as int) as WOP_QACCSAIS,
            cast(WOP_QLANSAIS as int) as WOP_QLANSAIS,
            cast(WOP_QRECSAIS as int) as WOP_QRECSAIS,
            WOP_PHASELIB
        FROM 
            WORDRELIG
        INNER JOIN 
            WORDREPHASE ON WORDREPHASE.WOP_LIGNEORDRE = WORDRELIG.WOL_LIGNEORDRE
            INNER JOIN 
            ARTICLE ON ARTICLE.GA_CODEARTICLE = WORDRELIG.WOL_CODEARTICLE
        INNER JOIN
            TIERS ON WORDRELIG.WOL_TIERS = TIERS.T_TIERS
        LEFT JOIN 
            PIECEADRESSE ON PIECEADRESSE.GPA_NUMERO = WORDRELIG.WOL_NUMERO 
                AND PIECEADRESSE.GPA_NATUREPIECEG = 'CC'
                AND PIECEADRESSE.GPA_TYPEPIECEADR = '001'
        WHERE 
            WOL_CHARLIBRE1 IS NOT NULL
            AND (WOL_TYPEORDRE = 'NUL' OR WOL_TYPEORDRE = 'VTE') 
            
            AND WOL_ETATLIG = '$etat_filtre' 
            AND WOL_DEPOT='$depot_filtre' 
            AND WOP_PHASE NOT IN ('L11', 'L12', 'L18', 'L19', 'L16', 'L17') 
            AND WOP_ETATPHASE != 'SOL'
        ORDER BY 
            WOL_LIGNEORDRE, WOP_OPECIRC"; 

$query = sqlsrv_query($conn, $sql);

if (!$query) {
    die("La requête a échoué: " . print_r(sqlsrv_errors(), true));
}

// Afficher les résultats dans un tableau HTML
echo "<table border='1px bold'>";
echo "<tr >";
echo "<th>Etat</th>";
echo "<th  >CLIENT</th>";
echo "<th  >NºCMD</th>";
echo "<th  >NºFICHE</th>";
echo "<th  >NºOF</th>";
echo "<th   style='width: 200px;'>Date Creation OF</th>";
echo "<th   style='width: 200px;'>Date Lancement OF</th>";
echo "<th   style='width: 200px;'>Date fin OF</th>";
echo "<th  >CODE ARTICLE</th>";
echo "<th  >ARTICLE</th>";
echo "<th  >QTE CMDé</th>";
echo "<th   colspan='12'>Phase de production</th>";
echo "</tr>";

$currentOF = null;
$phases = array(); // Tableau pour stocker les phases de la commande actuelle
$firstLineData = null; // Données de la première ligne de la commande OF

while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    // Si c'est une nouvelle ligne de commande, afficher les données de cette ligne
    if ($currentOF !== $row['WOL_LIGNEORDRE']) {
        // Afficher les données de la première ligne de la commande OF
        
        if ($firstLineData !== null) {
            // Remplacer les dates '01/01/1900' par '-' pour le considerer comme en cours de traitement
            $dateCreation = $firstLineData['WOL_DATECREATION']->format("d/m/Y H:i:s");
            $dateDebConst = $firstLineData['WOL_DATEDEBCONST']->format("d/m/Y");
            $dateFinConst = $firstLineData['WOL_DateFinConst']->format("d/m/Y");
            
            if ($dateCreation == "01/01/1900") {
                $dateCreation = "-";
            }
            if ($dateDebConst == "01/01/1900") {
                $dateDebConst = "-";
            }
            if ($dateFinConst == "01/01/1900") {
                $dateFinConst = "-";
            }
            
            echo "<tr class='solid_tr'>";
            echo "<td class='td_fixed' rowspan='2'>" . $etat_filtre . "</td>";
            echo "<td rowspan='2'>" . (!empty($firstLineData['T_LIBELLE']) ? $firstLineData['T_LIBELLE'] : 'STOCK') . "</td>";
            echo "<td rowspan='2'>" . $firstLineData['WOL_CHARLIBRE3'] . "</td>";
            echo "<td rowspan='2'>" . $firstLineData['WOL_CHARLIBRE2'] . "</td>";
            echo "<td rowspan='2'>" . $firstLineData['WOL_LIGNEORDRE'] . "</td>";
            echo "<td rowspan='2'>" . $dateCreation . "</td>";
            echo "<td rowspan='2'>" . $dateDebConst . "</td>";
            echo "<td rowspan='2'>" . $dateFinConst . "</td>";
            echo "<td rowspan='2'>" . $firstLineData['WOL_CODEARTICLE'] . "</td>";
            echo "<td rowspan='2'>" . $firstLineData['GA_LIBELLE'] . "</td>";
            echo "<td rowspan='2'>" . $firstLineData['WOP_QACCSAIS'] . "</td>";

            // Affichage des phases accumulées horizontalement
            foreach ($phases as $phase) {
                echo "<th colspan='2'>" . $phase . "</th>";
            }
            echo "</tr>";

            // Nouvelle ligne pour afficher les quantités lancées et réceptionnées
            echo "<tr>";
            foreach ($phases as $phase) {
                // Ajoutez une requête SQL pour récupérer les quantités lancées et réceptionnées
                $sql_quantities = "SELECT SUM(WOP_QLANSAIS) AS QLANSAIS, SUM(WOP_QRECSAIS) AS QRECSAIS 
                                    FROM WORDREPHASE 
                                    WHERE WOP_LIGNEORDRE = '{$firstLineData['WOL_LIGNEORDRE']}' 
                                    AND WOP_PHASELIB = '{$phase}'";
                $query_quantities = sqlsrv_query($conn, $sql_quantities);
                $quantities = sqlsrv_fetch_array($query_quantities, SQLSRV_FETCH_ASSOC);

                // Affichez les quantités récupérées dans le tableau
                echo "<td>LAN: " . number_format($quantities['QLANSAIS'], 2) . "</td>";
                echo "<td>REC: " . number_format($quantities['QRECSAIS'], 2) . "</td>";

                // Nettoyez la requête et les résultats
                sqlsrv_free_stmt($query_quantities);
            }
            echo "</tr>";
        }

        // Réinitialiser les variables pour la nouvelle commande OF
        $firstLineData = $row;
        $currentOF = $row['WOL_LIGNEORDRE'];
        $phases = array(); // Réinitialiser le tableau des phases pour la nouvelle commande
    }

    // Ajouter la phase actuelle au tableau des phases
    $phases[] = $row["WOP_PHASELIB"];
}

// Afficher les données de la dernière commande OF
if ($firstLineData !== null) {
    echo "<tr>";
    echo "<td rowspan='2'>" . $etat_filtre . "</td>";
    echo "<td rowspan='2'>" . $firstLineData['T_LIBELLE'] . "</td>";
    echo "<td rowspan='2'>" . $firstLineData['WOL_CHARLIBRE3'] . "</td>"; 
    echo "<td rowspan='2'>" . $firstLineData['WOL_CHARLIBRE2'] . "</td>";
    echo "<td rowspan='2'>" . $firstLineData['WOL_LIGNEORDRE'] . "</td>";
    echo "<td rowspan='2'>" . $dateCreation . "</td>";
    echo "<td rowspan='2'>" . $dateDebConst . "</td>";
    echo "<td rowspan='2'>" . $dateFinConst . "</td>";
    echo "<td rowspan='2'>" . $firstLineData['WOL_CODEARTICLE'] . "</td>";
    echo "<td rowspan='2'>" . $firstLineData['GA_LIBELLE'] . "</td>";
    echo "<td rowspan='2'>" . $firstLineData['WOP_QACCSAIS'] . "</td>";

   // Affichage des phases accumulées horizontalement
   foreach ($phases as $phase) {
    echo "<th colspan='2'>" . $phase . "</th>";
}
echo "</tr>";

// Nouvelle ligne pour afficher les quantités lancées et réceptionnées
echo "<tr>";
foreach ($phases as $phase) {
    // Ajoutez une requête SQL pour récupérer les quantités lancées et réceptionnées
    $sql_quantities = "SELECT SUM(WOP_QLANSAIS) AS QLANSAIS, SUM(WOP_QRECSAIS) AS QRECSAIS 
                        FROM WORDREPHASE 
                        WHERE WOP_LIGNEORDRE = '{$firstLineData['WOL_LIGNEORDRE']}' 
                        AND WOP_PHASELIB = '{$phase}'";
    $query_quantities = sqlsrv_query($conn, $sql_quantities);
    $quantities = sqlsrv_fetch_array($query_quantities, SQLSRV_FETCH_ASSOC);

    // Affichez les quantités récupérées dans le tableau
    echo "<td>LAN: " . number_format($quantities['QLANSAIS'], 2) . "</td>";
    echo "<td>REC: " . number_format($quantities['QRECSAIS'], 2) . "</td>";

    // Nettoyez la requête et les résultats
    sqlsrv_free_stmt($query_quantities);
}
echo "</tr>";


  
}

echo "</table>";

// Fermer la connexion à la base de données
sqlsrv_close($conn);

?>
</body>
</html>
