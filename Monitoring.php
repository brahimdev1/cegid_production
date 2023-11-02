<?php
include 'connexion.php';
include 'home.php';

// Votre requête SQL
$sql = "
SELECT  GPA_LIBELLE, WOL_NUMERO, WOL_LIGNEORDRE, WOL_CODEARTICLE, WOL_LIBELLE, CAST(WOL_QACCSAIS AS INT) AS WOL_QACCSAIS, CAST(WOP_QLANSAIS AS INT) AS WOP_QLANSAIS,
CAST(WOP_QRECSAIS AS INT) AS WOP_QRECSAIS, WOP_PHASELIB, time_in, time_out, difference
FROM WORDRELIG
JOIN WORDREPHASE ON WOP_LIGNEORDRE = WOL_LIGNEORDRE LEFT JOIN PIECEADRESSE ON PIECEADRESSE.GPA_NUMERO = WORDRELIG.WOL_NUMERO 
AND PIECEADRESSE.GPA_NATUREPIECEG = 'CC'
AND PIECEADRESSE.GPA_TYPEPIECEADR = '001'
WHERE WOL_CHARLIBRE1 IS NOT NULL
AND (
(WOL_TYPEORDRE = 'NUL' OR WOL_TYPEORDRE = 'VTE')
AND   wol_ligneordre=2220 OR wol_ligneordre=2217 OR wol_ligneordre=2214 OR wol_ligneordre=2257 OR wol_ligneordre=2236 OR wol_ligneordre=2254 
OR wol_ligneordre=2260 OR wol_ligneordre=2226 OR wol_ligneordre=2284 OR wol_ligneordre=2290 OR wol_ligneordre=2287 OR wol_ligneordre=2302
OR wol_ligneordre=2295 OR wol_ligneordre=2199 OR wol_ligneordre=2248 OR wol_ligneordre=2242 OR wol_ligneordre=2245 OR wol_ligneordre=2263
OR wol_ligneordre=2264 OR wol_ligneordre=2265 OR wol_ligneordre=2266 OR wol_ligneordre=2268 OR wol_ligneordre=2269 OR wol_ligneordre=2293
OR wol_ligneordre=2294 OR wol_ligneordre=2295 )
AND WOP_PHASE NOT IN ('L11', 'L12', 'L18', 'L19', 'L16', 'L17') 

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
            border : 3px solid black;
        }


        .green-bg {
            background-color: green;
        }

        .bold-text {
            font-weight: bold;
        }
        /* Ajoutez un style pour mettre en gras chaque quatrième ligne */
        tr:nth-child(4n) {
            font-weight: bold;
        }
        .bigth{
            width : 300px
        }
        .smallth{
            width : 100px
        }
    </style>
    <table class="table table-bordered text-center">
        <tr>
            <th colspan="6"></th>
            <th colspan="5">Etat actuel</th>
        </tr>
        <tr>
            <th class="bigth">Client</th>
            <th >nºcmd</th>
            <th >OF</th>
            <th>Code Article</th>
            <th class="bigth">Libelle</th>
            <th>Qte Commande</th>
            <th>phase</th>
            <th>ENTRE</th>
            <th>SORTIE</th>
            <th class="smallth">temps de début</th>
            <th class="smallth">temps de fin</th>
            <th>temps cycle/min</th>
        </tr>
        <?php
        $previousgpalibelle = null;
        $previousWolNumero = null;
        $previousWolLigneOrdre = null;
        $previousWolCodeArticle = null;
        $previousWolLibelle = null;
        $previousWolQAccSais = null;

        $rowNumber = 0;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if ($rowNumber % 4 === 0) {
                echo '<tr class="bold-text">';
            } else {
                echo '<tr>';
            }

            // Gpa_libelle
            $isWolclientDifferent = $row['GPA_LIBELLE'] != $previousgpalibelle;
            echo "<td" . ($isWolclientDifferent ? ' class="bold-text"' : '') . ">" . $row['GPA_LIBELLE'] . "</td>";
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
            echo "<td>" . ($row['time_in'] ? $row['time_in']->format('Y-m-d H:i:s') : '') . "</td>";
            echo "<td>" . ($row['time_out'] ? $row['time_out']->format('Y-m-d H:i:s') : '') . "</td>";
            echo "<td>" . $row['difference'] . "</td>";

            echo '</tr>';
            $rowNumber++;

            // Réinitialisez le compteur de ligne après chaque 4e ligne
            if ($rowNumber >= 4) {
                $rowNumber = 0;
                // Mettez à jour les valeurs précédentes ici
                $previousWolNumero = $row['WOL_NUMERO'];
                $previousWolLigneOrdre = $row['WOL_LIGNEORDRE'];
                $previousWolCodeArticle = $row['WOL_CODEARTICLE'];
                $previousWolLibelle = $row['WOL_LIBELLE'];
                $previousWolQAccSais = $row['WOL_QACCSAIS'];
            }
        }
        ?>
    </table>
</body>

</html>