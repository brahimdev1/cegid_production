<?php
session_start(); // Start the session
include 'Home.php';
if (isset($_SESSION['GPA_LIBELLE'])) {
    $page1_value = $_SESSION['GPA_LIBELLE'];
} else {
    // Handle the case when the value is not set (optional)
    $page1_value = "No value set from page Grid";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi Actuel de production </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/html2pdf.js/dist/html2pdf.bundle.min.js"></script>

    <style>
        .center-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0 auto;
        }

        .pdf-page {
            padding: 10px;
        }

        h1 {
            text-align: center;
            text-decoration: underline;

        }
       


     

        .search-form {
            margin-bottom: 20px;
        }

        .pdf-table {
            font-size: 12px;
            table-layout: fixed;
            border-collapse: collapse;
            width: 100%;
        }

        .pdf-table th,
        .pdf-table td {
            padding: 5px;
            word-wrap: break-word;
        }

        table, td, th {
  border: 1px solid black;
}

       
    </style>
<body class="center-content">
<div class="pdf-page" id="invoice">
    <h1 style="color:red">processus de production </h1>
    <br>
    <h1>Client : <?php echo $page1_value; ?></h1>

<!DOCTYPE html>
<!-- Reste de votre code HTML -->
<?php
include 'connexion.php';

$sql = "SELECT WOP_LIGNEORDRE, WOP_PHASELIB, WOP_QACCSAIS, WOP_QLANSAIS, WOP_QRECSAIS, WOL_LIBELLE, time_in, time_out, difference
        FROM (
            SELECT DISTINCT WOP_LIGNEORDRE, WOP_PHASELIB, WOP_QACCSAIS, WOP_QLANSAIS, WOP_QRECSAIS, WOL_LIBELLE, time_in, time_out, difference,
                CASE 
                    WHEN LEFT(WOP_PHASELIB, 1) = 'g' THEN 1 
                    WHEN LEFT(WOP_PHASELIB, 1) = 'p' THEN 2
                    WHEN LEFT(WOP_PHASELIB, 1) = 'h' THEN 3
                    WHEN LEFT(WOP_PHASELIB, 1) = 'b' THEN 4 
                    WHEN LEFT(WOP_PHASELIB, 1) = 'm' THEN 4 
                    WHEN LEFT(WOP_PHASELIB, 1) = 'e' THEN 5
                    ELSE 6 -- Other cases
                END AS OrderPriority
            FROM WORDRELIG 
            INNER JOIN WORDREPHASE ON WOL_LIGNEORDRE = WOP_LIGNEORDRE 
            INNER JOIN PIECEADRESSE ON WORDRELIG.WOL_TIERS = SUBSTRING(PIECEADRESSE.GPA_AUXICONTACT, 5, LEN(PIECEADRESSE.GPA_AUXICONTACT)) 
            WHERE GPA_LIBELLE = '$page1_value' 
            AND WOP_PHASE NOT IN ('L16', 'L17') 
            AND WOP_ETATPHASE != 'SOL'
        ) AS Subquery
        ORDER BY WOP_LIGNEORDRE, OrderPriority ASC;";

$stmt = sqlsrv_prepare($conn, $sql, array(&$numeroCommande));

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_execute($stmt) === false) {
    die(print_r(sqlsrv_errors(), true));
}

$prevWOP_LIGNEORDRE = null; // Variable pour suivre la valeur précédente de WOP_LIGNEORDRE

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if ($row['WOP_LIGNEORDRE'] !== $prevWOP_LIGNEORDRE) {
        if ($prevWOP_LIGNEORDRE !== null) {
            echo "</table>"; // Ferme la table précédente (s'il y en avait une)
        }
        echo "<h2 style='text-align:center'>  ---------------------------------------------------------------  </h2>"; // Affiche la nouvelle valeur de WOP_LIGNEORDRE

        echo "<h3>Ordre de Fabrication : " . $row['WOP_LIGNEORDRE'] . " | Matelas : " . $row['WOL_LIBELLE'] . "</h3>"; // Affiche la nouvelle valeur de WOP_LIGNEORDRE



        echo "<table>
                <tr>
                    <th>NºOF</th>
                    <th>Phase</th>
                    <th>Quantité acceptée</th>
                    <th>Lancement</th>
                    <th>Réception</th>
                    <th>Heure de lancement</th>
                    <th>Heure de réception</th>
                    <th>Taux d'activité</th>
                </tr>";
    }

    echo "<tr>";
    echo "<td>" . $row['WOP_LIGNEORDRE'] . "</td>";
    echo "<td>" . $row['WOP_PHASELIB'] . "</td>";
    echo "<td>" . number_format($row['WOP_QACCSAIS'], 4) . "</td>";
    echo "<td>" . number_format($row['WOP_QLANSAIS'], 4) . "</td>";
    echo "<td>" . number_format($row['WOP_QRECSAIS'], 4) . "</td>";
    echo "<td>" . ($row['time_in'] ? $row['time_in']->format('Y-m-d H:i:s') : '') . "</td>";
    echo "<td>" . ($row['time_out'] ? $row['time_out']->format('Y-m-d H:i:s') : '') . "</td>";
    echo "<td>" . $row['difference'] . "</td>";
    echo "</tr>";

    $prevWOP_LIGNEORDRE = $row['WOP_LIGNEORDRE']; // Met à jour la valeur précédente de WOP_LIGNEORDRE
}

echo "</table>";

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>

</div>
<button onclick="generatePDF()" id="downloadBtn">Download</button>

<script>
function generatePDF() {
    var downloadBtn = document.getElementById("downloadBtn");
    var searchBtn = document.querySelector("input[type='submit']");

    downloadBtn.classList.add("pdf-hide"); // Ajoute la classe "pdf-hide" pour masquer le bouton de téléchargement
    searchBtn.classList.add("pdf-hide"); // Ajoute la classe "pdf-hide" pour masquer le bouton de recherche

    const element = document.getElementById("invoice");

    html2pdf()
        .from(element)
        .save()
        .then(function() {
            downloadBtn.classList.remove("pdf-hide"); // Retire la classe "pdf-hide" pour réafficher le bouton de téléchargement après le téléchargement du PDF
            searchBtn.classList.remove("pdf-hide"); // Retire la classe "pdf-hide" pour réafficher le bouton de recherche après le téléchargement du PDF
        });
}
</script>
</body>
</html>

