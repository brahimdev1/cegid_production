<?php
include 'connexion.php';
include 'home.php';

// Votre requête SQL
$sql = "
SELECT WOL_NUMERO, WOL_LIGNEORDRE, WOL_CODEARTICLE, WOL_LIBELLE, WOL_QACCSAIS, CAST(WOP_QLANSAIS AS INT) AS WOP_QLANSAIS,
    CAST(WOP_QRECSAIS AS INT) AS WOP_QRECSAIS, WOP_PHASELIB,time_in, time_out, difference
FROM WORDRELIG
JOIN WORDREPHASE ON WOP_LIGNEORDRE = WOL_LIGNEORDRE
WHERE WOL_CHARLIBRE1 IS NOT NULL
    AND (WOL_TYPEORDRE = 'NUL' OR WOL_TYPEORDRE = 'VTE')
 AND  ( WOP_LIGNEORDRE = 2166 OR WOP_LIGNEORDRE = 2168 OR WOP_LIGNEORDRE = 2171 OR WOP_LIGNEORDRE = 2172 OR WOP_LIGNEORDRE = 2175 OR WOP_LIGNEORDRE = 2178 OR WOP_LIGNEORDRE = 2181 OR WOP_LIGNEORDRE = 2182  OR WOP_LIGNEORDRE = 2183 OR WOP_LIGNEORDRE = 2184)
    AND WOP_PHASE NOT IN ('L11', 'L12', 'L18', 'L19', 'L16', 'L17');

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

        th,
        td {
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
        /* Ajoutez un style pour mettre en gras chaque quatrième ligne */
        tr:nth-child(4n) {
            font-weight: bold;
        }
    </style>
    <table class="table table-bordered text-center">
        <tr>
            <th colspan="6"></th>
            <th colspan="5">Etat actuel</th>
        </tr>
        <tr>
            <th style="width: 150px;">nºcmd</th>
            <th style="width: 150px;">OF</th>
            <th>Code Article</th>
            <th style="width: 300px;">Libelle</th>
            <th>Qte Commande</th>
            <th>phase</th>
            <th>ENTRE</th>
            <th>SORTIE</th>
            <th style="width: 150px;">temps de début</th>
            <th style="width: 150px;">temps de fin</th>
            <th>temps cycle/min</th>
        </tr>
        <?php
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
