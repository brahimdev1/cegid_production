<?php
include 'home.php';
include 'connexion.php';



// Your SQL query
$sql = "SELECT GL_REFEXTERNE,WOL_LIGNEORDRE, GL_DATEPIECE, GL_NUMERO , GL_LIBELLE, GL_DATELIVRAISON,GL_QTERESTE, DATEDIFF(DAY, GL_DATEPIECE + 3, GETDATE()) as temps_Reste
        FROM ligne LEFT JOIN WORDRELIG ON LIGNE.GL_NUMERO=WORDRELIG.WOL_NUMERO
        WHERE GL_ARTICLE IS NOT NULL AND GL_ARTICLE <> '' AND GL_NATUREPIECEG = 'CC' AND
        GL_DATEPIECE >= '2023-16-05 00:00:00.000' AND GL_LIBREART8 = 'ATL' AND GL_ETATSOLDE = 'ENC' 
        ORDER BY temps_Reste DESC";


$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table View</title>
    <style>
        .colored-red {
    background-color: red;
    color: white;
}

.colored-orange {
    background-color: orange;
    color: white;
}

.colored-green {
    background-color: green;
    color: white;
}

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center"> Carnet de Commmande Matelas</h2>
    
    <table>
        <tr>
            <th>Client</th>
            <th>OF</th>

            <th class="text-center" style="width: 120px">Date Saisie</th>
            <th>NºCmd</th>
            <th>Designation</th>
            <th class="text-center" style="width: 120px" >Date livraison</th>
            <th>Quantite</th>
            <th>Retard</th>
        </tr>

        <?php
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['GL_REFEXTERNE'] . "</td>";
            echo "<td>" . (isset($row['WOL_LIGNEORDRE']) ? $row['WOL_LIGNEORDRE'] : 'non géneré') . "</td>";
            echo "<td >" . $row['GL_DATEPIECE']->format('d-m-Y ') . "</td>";
            echo "<td>" . $row['GL_NUMERO'] . "</td>";
            echo "<td>" . $row['GL_LIBELLE'] . "</td>";
            echo "<td>" . $row['GL_DATELIVRAISON']->format('d-m-Y ') . "</td>";
            echo "<td>" . $row['GL_QTERESTE'] . "</td>";

             // Check the value of the column and apply CSS class based on the condition
    $retard = $row['temps_Reste'];
    if ($retard >= 0) {
        echo '<td class="colored-red">' . $retard . '</td>';
    } elseif ($retard == -1) {
        echo '<td class="colored-orange">' . $retard . '</td>';
        
    } else {
        echo '<td class="colored-green">' . $retard . '</td>';
    }
            echo "</tr>";
        }
        ?>

    </table>
    <button onclick="window.print()">Print this page</button>
</body>
</html>

<?php
// Close the statement and connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>


