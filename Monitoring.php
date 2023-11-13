<?php
include 'connexion.php';
include 'home.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
       

        table {
            border-collapse: collapse;
            width: 100%;
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
</head>
<body>
<h2 style="text-align:center"> Etat de Carnet de Commmande Matelas</h2>



<?php


// Exécutez la requête SQL
$sql = "SELECT GPA_LIBELLE, WOL_NUMERO, WOL_LIGNEORDRE, WOL_CODEARTICLE, WOL_LIBELLE, cast(WOP_QACCSAIS as int) as WOP_QACCSAIS,cast(WOP_QLANSAIS as int) as WOP_QLANSAIS,
cast(WOP_QRECSAIS as int) as WOP_QRECSAIS
        FROM WORDRELIG
        INNER JOIN WORDREPHASE ON WORDREPHASE.WOP_LIGNEORDRE = WORDRELIG.WOL_LIGNEORDRE
        LEFT JOIN PIECEADRESSE ON PIECEADRESSE.GPA_NUMERO = WORDRELIG.WOL_NUMERO 
        AND PIECEADRESSE.GPA_NATUREPIECEG = 'CC'
        AND PIECEADRESSE.GPA_TYPEPIECEADR = '001'
        WHERE WOL_CHARLIBRE1 IS NOT NULL
        AND (
            (WOL_TYPEORDRE = 'NUL' OR WOL_TYPEORDRE = 'VTE')
            AND    wol_ligneordre=2332 OR wol_ligneordre=2320 OR wol_ligneordre=2323 OR wol_ligneordre=2335
OR wol_ligneordre=2326 OR wol_ligneordre=2329  OR wol_ligneordre=2341 OR wol_ligneordre=2347 OR wol_ligneordre=2350  OR wol_ligneordre=2353
OR wol_ligneordre=2347 OR wol_ligneordre=2371 OR wol_ligneordre=2362 OR wol_ligneordre=2368 OR wol_ligneordre=2365 OR wol_ligneordre=2374 OR wol_ligneordre=2377 
OR wol_ligneordre=2380 OR wol_ligneordre=2386 OR wol_ligneordre=2389 OR wol_ligneordre=2392 OR wol_ligneordre=2263 OR wol_ligneordre=2164 OR wol_ligneordre=2266 
OR wol_ligneordre=2398 OR wol_ligneordre=2401 OR wol_ligneordre=2407 OR wol_ligneordre=2404 OR wol_ligneordre=2410 OR wol_ligneordre=2413)
        AND WOP_PHASE NOT IN ('L11', 'L12', 'L18', 'L19', 'L16', 'L17')";

$query = sqlsrv_query($conn, $sql);

if (!$query) {
    die("La requête a échoué: " . print_r(sqlsrv_errors(), true));
}

// Initialisation des variables pour le suivi de la ligne récente
$lastLine = null;
$mergedData = [];

// Parcourir les résultats
while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    $currentLine = $row["WOL_LIGNEORDRE"];
    
    // Vérifier si la ligne actuelle est la même que la ligne précédente
    if ($currentLine !== $lastLine) {
        // Si la ligne est différente, ajoutez les données fusionnées au tableau
        if ($lastLine !== null) {
            $mergedData[] = $mergedRow;
        }
        
        // Réinitialiser les données fusionnées pour la nouvelle ligne
        $mergedRow = $row;
        $lastLine = $currentLine;
    } else {
        // Fusionner les colonnes WOL_QLANSAIS et WOL_QRECSAIS horizontalement
        $mergedRow["WOP_QLANSAIS"] .= " " . $row["WOP_QLANSAIS"];
        $mergedRow["WOP_QRECSAIS"] .= " " . $row["WOP_QRECSAIS"];
    }
}

// Ajouter la dernière ligne fusionnée au tableau
if ($lastLine !== null) {
    $mergedData[] = $mergedRow;
}

// Afficher les résultats dans un tableau HTML
echo "<table border='1px'>";
echo "<tr>";
echo "<th colspan='6'></th>";
echo "<th colspan='2'>Garnissage</th>";
echo "<th colspan='2'>Habillage</th>";
echo "<th colspan='2'>Bordeuse</th>";
echo "<th colspan='2'>Emballage</th>";
echo "</tr>";
echo "<tr>";
echo "<th>CLIENT</th>";
echo "<th>NºCMD</th>";
echo "<th>NºOF</th>";
echo "<th>CODE ARTICLE</th>";
echo "<th>LIBELLE</th>";
echo "<th>QTE QMD</th>";
echo "<th>Entré</th>";
echo "<th>Sortie</th>";
echo "<th>Entré</th>";
echo "<th>Sortie</th>";
echo "<th>Entré</th>";
echo "<th>Sortie</th>";
echo "<th>Entré</th>";
echo "<th>Sortie</th>";
echo "</tr>";

foreach ($mergedData as $row) {
    echo "<tr>";
    echo "<td>" . (isset($row['GPA_LIBELLE']) ? $row['GPA_LIBELLE'] : 'STOCK') . "</td>";
    echo "<td>" . $row["WOL_NUMERO"] . "</td>";
    echo "<td>" . $row["WOL_LIGNEORDRE"] . "</td>";
    echo "<td>" . $row["WOL_CODEARTICLE"] . "</td>";
    echo "<td>" . $row["WOL_LIBELLE"] . "</td>";
    echo "<td>" . $row["WOP_QACCSAIS"] . "</td>";

    // Afficher WOP_QLANSAIS et WOP_QRECSAIS dans des colonnes distinctes
    $qlansaisValues = explode(" ", $row["WOP_QLANSAIS"]);
    $qrecsaisValues = explode(" ", $row["WOP_QRECSAIS"]);

    // Assurez-vous que les deux tableaux ont la même longueur
    $count = max(count($qlansaisValues), count($qrecsaisValues));

    for ($i = 0; $i < $count; $i++) {
        $qlansais = isset($qlansaisValues[$i]) ? $qlansaisValues[$i] : "";
        $qrecsais = isset($qrecsaisValues[$i]) ? $qrecsaisValues[$i] : "";

        // Vérifiez si la valeur est différente de zéro et colorez en vert si nécessaire
        $qlansaisStyle = ($qlansais != 0) ? 'style="background-color: green;color:black;"' : '';
        $qrecsaisStyle = ($qrecsais != 0) ? 'style="background-color: green;color:black;"' : '';

        echo "<td $qlansaisStyle>$qlansais</td><td $qrecsaisStyle>$qrecsais</td>";
    }

    echo "</tr>";
}

echo "</table>";


// Fermer la connexion à la base de données
sqlsrv_close($conn);

?>
</body>
</html>