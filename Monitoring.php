<?php
    include 'connexion.php';

    // Your SQL query
$sql = "
Select  GPA_LIBELLE,RD8_RD8LIBDATE4,WOL_NUMERO,WOL_LIGNEORDRE,WOL_CODEARTICLE,WOL_LIBELLE ,WOL_QACCSAIS,WOL_LIBELLE,WOP_QACCSAIS,WOP_PHASELIB, WOP_QLANSAIS, WOP_QRECSAIS from WORDRELIG,PIECEADRESSE,RTINFOS008,WORDREPHASE
where CAST(WOL_DATECREATION as date)= '2023-10-09' AND WOL_TYPEORDRE='VTE' 
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
    <table class="table table-bordered text-center">
        <tr>
            <th colspan="8"></th>
            <th colspan="2">Garnissage</th>
            <th colspan="2">Habillage</th>
            <th colspan="2">Bordeuse</th>
            <th colspan="2">Emballage</th>
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
        
            <td>ENTRE</td>
            <td>SORTIE</td>
            <td>ENTRE</td>
            <td>SORTIE</td>
            <td>ENTRE</td>
            <td>SORTIE</td>
            <td>ENTRE</td>
            <td>SORTIE</td>
        </tr>
        <?php
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['GPA_LIBELLE'] . "</td>";
            echo "<td >" . $row['RD8_RD8LIBDATE4']->format('d-m-Y ') . "</td>";
            echo "<td>" . $row['WOL_NUMERO'] . "</td>";
            echo "<td>" . $row['WOL_LIGNEORDRE'] . "</td>";
            echo "<td>" . $row['WOL_CODEARTICLE'] . "</td>";
            echo "<td>" . $row['WOL_LIBELLE'] . "</td>";
            echo "<td>" . $row['WOL_QACCSAIS'] . "</td>";
            echo "<td>" . $row['WOP_PHASELIB'] . "</td>";
            echo "<td>" . $row['WOP_QLANSAIS'] . "</td>";
            echo "<td>" . $row['WOP_QRECSAIS'] . "</td>";




            echo "</tr>";
        }
            ?>
    </table>



</body>

</html>