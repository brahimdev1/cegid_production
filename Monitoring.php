<?php
include 'connexion.php';
include 'home.php';


// Votre requête SQL
$sql = "
Select  GPA_LIBELLE,RD8_RD8LIBDATE4,WOL_NUMERO,WOL_LIGNEORDRE,WOL_CODEARTICLE,WOL_LIBELLE ,WOL_QACCSAIS,WOL_LIBELLE,WOP_QACCSAIS, CAST(WOP_QLANSAIS AS INT) AS WOP_QLANSAIS,
CAST(WOP_QRECSAIS AS INT) AS WOP_QRECSAIS, WOP_PHASELIB from WORDRELIG,PIECEADRESSE,RTINFOS008,WORDREPHASE
where CAST(WOL_DATECREATION as date)= '2023-10-10' AND WOL_TYPEORDRE='VTE' 
and GPA_NUMERO = WOL_NUMERO AND GPA_SOUCHE='MCC'AND GPA_TYPEPIECEADR='001'
AND RD8_CLEDATA  LIKE '%' + CAST(WOL_NUMERO AS VARCHAR(255)) + '%' and  RD8_CLEDATA LIKE '%CC%' and  RD8_CLEDATA LIKE '%000001%'
AND WOP_LIGNEORDRE = WOL_LIGNEORDRE AND WOP_PHASE NOT IN ('L11','L12','L18','L19') 
";

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
die(print_r(sqlsrv_errors(), true));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

     

        .green-bg {
            background-color: green;
        }

        .bold-text {
            font-weight: bold;
        }
    </style>
    <table class="table table-bordered text-center">
        <tr>
            <th colspan="8"></th>
            <th colspan="2">Etat actuelle</th>
        </tr>
        <tr>
            <th style="width: 300px;">Client</th>
            <th style="width: 200px;">Date Cde</th>
            <th>Nºcde</th>
            <th>OF</th>
            <th>REF</th>
            <th style="width: 300px;">LIBELLE</th>
            <th>QTE</th>
            <th>PHASE</th>
            <th>ENTRE</th>
            <th>SORTIE</th>
        </tr>
        <?php
        $previousGpaLibelle = null;
        $previousRd8Rd8LibDate4 = null;
        $previousWolNumero = null;
        $previousWolLigneOrdre = null;
        $previousWolCodeArticle = null;
        $previousWolLibelle = null;
        $previousWolQAccSais = null;

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            
            // GPA_LIBELLE
            $isGpaLibelleDifferent = $row['GPA_LIBELLE'] != $previousGpaLibelle;
            echo "<td" . ($isGpaLibelleDifferent ? ' class="bold-text"' : '') . ">" . $row['GPA_LIBELLE'] . "</td>";
            
            // RD8_RD8LIBDATE4
            $isRd8Rd8LibDate4Different = $row['RD8_RD8LIBDATE4'] != $previousRd8Rd8LibDate4;
            echo "<td" . ($isRd8Rd8LibDate4Different ? ' class="bold-text"' : '') . ">" . $row['RD8_RD8LIBDATE4']->format('d-m-Y ') . "</td>";

            // WOL_NUMERO
            $isWolNumeroDifferent = $row['WOL_NUMERO'] != $previousWolNumero;
            echo "<td" . ($isWolNumeroDifferent ? ' class="bold-text"' : '') . ">" . $row['WOL_NUMERO'] . "</td>";
            
            // WOL_LIGNEORDRE
            $isWolLigneOrdreDifferent = $row['WOL_LIGNEORDRE'] != $previousWolLigneOrdre;
            echo "<td" . ($isWolLigneOrdreDifferent ? ' class="bold-text"' : '') . ">" . $row['WOL_LIGNEORDRE'] . "</td>";
            
            // WOL_CODEARTICLE
            $isWolCodeArticleDifferent = $row['WOL_CODEARTICLE'] != $previousWolCodeArticle;
            echo "<td" . ($isWolCodeArticleDifferent ? ' class="bold-text"' : '') . ">" . $row['WOL_CODEARTICLE'] . "</td>";
            
            // WOL_LIBELLE
            $isWolLibelleDifferent = $row['WOL_LIBELLE'] != $previousWolLibelle;
            echo "<td" . ($isWolLibelleDifferent ? ' class="bold-text"' : '') . ">" . $row['WOL_LIBELLE'] . "</td>";
            
            // WOL_QACCSAIS
            $isWolQAccSaisDifferent = $row['WOL_QACCSAIS'] != $previousWolQAccSais;
            echo "<td" . ($isWolQAccSaisDifferent ? ' class="bold-text"' : '') . ">" . $row['WOL_QACCSAIS'] . "</td>";
            
            echo "<td>" . $row['WOP_PHASELIB'] . "</td>";

            $wopQlansais = (int) $row['WOP_QLANSAIS'];
            $wopQrecsais = (int) $row['WOP_QRECSAIS'];

            // Appliquer la classe 'green-bg' si l'une des valeurs est différente de 0
            echo "<td" . ($wopQlansais !== 0 ? ' class="green-bg"' : '') . ">" . $wopQlansais . "</td>";
            echo "<td" . ($wopQrecsais !== 0 ? ' class="green-bg"' : '') . ">" . $wopQrecsais . "</td>";

            echo "</tr>";

            // Mettre à jour les valeurs précédentes
            $previousGpaLibelle = $row['GPA_LIBELLE'];
            $previousRd8Rd8LibDate4 = $row['RD8_RD8LIBDATE4'];
            $previousWolNumero = $row['WOL_NUMERO'];
            $previousWolLigneOrdre = $row['WOL_LIGNEORDRE'];
            $previousWolCodeArticle = $row['WOL_CODEARTICLE'];
            $previousWolLibelle = $row['WOL_LIBELLE'];
            $previousWolQAccSais = $row['WOL_QACCSAIS'];
        }
        ?>
    </table>
</body>
</html>
