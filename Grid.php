<?php
session_start(); // Start the session
include 'connexion.php';
include 'Home.php'; 
if (isset($_POST['suivreButton'])) {
    // This block will execute only if the "Suivre" button is clicked
    if (isset($_POST['GPA_LIBELLE'])) {
        $_SESSION['GPA_LIBELLE'] = $_POST['GPA_LIBELLE'];
        // Redirect to page2.php after form submission to avoid resubmission on page refresh
        echo '<script>
        window.location.href = "Suivie.php";
    </script>';
    
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Document</title>
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
</style>

</head>
<body>
<?php
  


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Récupérer le numéro saisi par l'utilisateur

}

// Vérifier si le formulaire a été soumis
if (isset($_POST['GPA_LIBELLE'])) {
// Récupérer les valeurs saisies par l'utilisateur
$GPA_LIBELLE= $_POST['GPA_LIBELLE'];





// Requête 1 : SELECT GL_DATECREATION, GL_LIBELLE, GL_DATELIVRAISON
$query1 = "SELECT DISTINCT GL_DATECREATION, GL_DATEPIECE ,GL_LIBELLE, GL_DATELIVRAISON,GL_QTERESTE, DATEDIFF(DAY, GL_DATEPIECE + 3, GETDATE()) as temps_Reste
FROM ligne INNER JOIN PIECEADRESSE ON  LIGNE.GL_TIERS = SUBSTRING(PIECEADRESSE.GPA_AUXICONTACT, 5, LEN(PIECEADRESSE.GPA_AUXICONTACT)) 
WHERE GL_ARTICLE IS NOT NULL AND GL_ARTICLE <> '' AND GL_NATUREPIECEG = 'CC' AND GPA_LIBELLE = ? AND
GL_DATEPIECE >= '2023-16-05 00:00:00.000' AND GL_LIBREART8 = 'ATL' AND GL_ETATSOLDE = 'ENC' 
ORDER BY temps_Reste DESC";


// Préparer la requête SQL avec les paramètres utilisateur
$params1 = array($GPA_LIBELLE);
$result1 = sqlsrv_query($conn, $query1, $params1);

if ($result1 === false) {
    die(print_r(sqlsrv_errors(), true));
}

$resultsArray1 = array();
while ($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
    $resultsArray1[] = $row1;
}

sqlsrv_free_stmt($result1);


sqlsrv_close($conn);
}

?>

<!-- Formulaire HTML pour saisir GL_TIERS et GL_NUMERO -->
<!-- Formulaire HTML pour saisir GL_TIERS et GL_NUMERO -->
<form method="POST">
    <label for="GPA_LIBELLE">CLIENT :</label>
    <select name="GPA_LIBELLE" id="GPA_LIBELLE">
        <option value="">Sélectionnez un client</option>

        <?php
        try {
            $conn = new PDO("sqlsrv:Server=192.168.1.177;Database=MEDIDISCEGIDREELLE", "SA", "cegid.2008");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = "SELECT DISTINCT GPA_LIBELLE
                      FROM ligne
                      INNER JOIN PIECEADRESSE ON LIGNE.GL_TIERS = SUBSTRING(PIECEADRESSE.GPA_AUXICONTACT, 5, LEN(PIECEADRESSE.GPA_AUXICONTACT))
                      WHERE GL_ARTICLE IS NOT NULL AND GL_ARTICLE <> '' AND GL_NATUREPIECEG = 'CC'  
                      AND GL_DATEPIECE >= CONVERT(datetime, '2023-05-16 00:00:00.000', 121) AND GL_LIBREART8 = 'ATL' AND GL_ETATSOLDE = 'ENC'
                      ORDER BY GPA_LIBELLE ASC";

            $stmt = $conn->prepare($query);
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($clients as $client) {
                $selected = '';
                if (isset($_POST['GPA_LIBELLE']) && $_POST['GPA_LIBELLE'] === $client['GPA_LIBELLE']) {
                    $selected = 'selected';
                }
                echo '<option value="' . $client['GPA_LIBELLE'] . '" ' . $selected . '>' . $client['GPA_LIBELLE'] . '</option>';
            }
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        ?>
    </select>

    <input type="submit" name="rechercherButton" value="Rechercher">
    <button type="submit" name="suivreButton">Suivre</button>
</form>

<!-- Formulaire HTML pour saisir GL_TIERS et GL_NUMERO -->


<?php
// Afficher les résultats si disponibles
if (isset($resultsArray1)) {
echo '<div class="table-container">';
echo '<table class="table table-striped table-hover">';
echo '<thead>';
echo '<tr>';
echo '<th class="text-center" style="width: 150px; border: 1px solid black;"><b>Date de Saisie</b></th>';
echo '<th class="text-center" style="width: 150px; border: 1px solid black;"><b>Date de Commmande</b></th>';
echo '<th class="text-center" style="width: 200px; border: 1px solid black;"><b>Designation</b></th>';
echo '<th class="text-center" style="width: 150px; border: 1px solid black;"><b>Date de livraison</b></th>';
echo '<th class="text-center" style="width: 150px; border: 1px solid black;"><b>Qte Reste</b></th>';
echo '<th class="text-center" style="width: 100px; border: 1px solid black;"><b>Retard</b></th>';

echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($resultsArray1 as $result) {
    echo '<tr>';
    echo '<td class="text-center" style="border: 1px solid black;">' . $result['GL_DATECREATION']->format('Y-m-d') . '</td>';
    echo '<td class="text-center" style="border: 1px solid black;">' . $result['GL_DATEPIECE']->format('Y-m-d') . '</td>';
    echo '<td class="text-center" style="border: 1px solid black;">' . $result['GL_LIBELLE'] . '</td>';
    echo '<td class="text-center" style="border: 1px solid black;">' . $result['GL_DATELIVRAISON']->format('Y-m-d') . '</td>';
    echo '<td class="text-center" style="border: 1px solid black;">' . $result['GL_QTERESTE'] . '</td>';

     // Calculate and display the retard based on the value of temps_Reste
     $retard = $result['temps_Reste'];
     if ($retard >= 0) {
         echo '<td class="text-center colored-red">' . $retard . '</td>';
     } elseif ($retard == -1) {
         echo '<td class="text-center colored-orange">' . $retard . '</td>';
     } else {
         echo '<td class="text-center colored-green">' . $retard . '</td>';
     }
  
    
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';

}

if (isset($resultsArray2)) {
echo '<div>';
echo '<h3>Nom du client  :</h3>';
foreach ($resultsArray2 as $result) {
    echo $result . '<br>';
}
echo '</div>';
}
?>



</body>
</html>








