<?php
$serverName = "serveurmedidis2";
$connectionOptions = array(
    "Database" => "MEDIDISCEGIDREELLE",
    "Uid" => "SA",
    "PWD" => "cegid.2008"
);

// Établir la connexion à la base de données
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Vérifier si le formulaire a été soumis
if (isset($_POST['GL_TIERS']) && isset($_POST['GL_NUMERO'])) {
    // Récupérer les valeurs saisies par l'utilisateur
    $GL_TIERS = $_POST['GL_TIERS'];
    $GL_NUMERO = $_POST['GL_NUMERO'];

    // Requête 1 : SELECT GL_DATECREATION, GL_LIBELLE, GL_DATELIVRAISON
    $query1 = "SELECT GL_DATECREATION, GL_LIBELLE, GL_DATELIVRAISON
    FROM PIECEADRESSE
    INNER JOIN LIGNE ON LIGNE.GL_NUMERO = PIECEADRESSE.GPA_NUMERO
    WHERE GL_NATUREPIECEG = 'CC' AND PIECEADRESSE.GPA_LIBELLE = ? AND LIGNE.GL_NUMERO = ?;";

    // Préparer la requête SQL avec les paramètres utilisateur
    $params1 = array($GL_TIERS, $GL_NUMERO);
    $result1 = sqlsrv_query($conn, $query1, $params1);

    if ($result1 === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $resultsArray1 = array();
    while ($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
        $resultsArray1[] = $row1;
    }

    sqlsrv_free_stmt($result1);

    // Requête 2 : SELECT GPA_LIBELLE
    $query2 = "SELECT GPA_LIBELLE
    FROM PIECEADRESSE
    WHERE GPA_NUMERO = ? AND GPA_SOUCHE = 'MCC';";

    // Préparer la requête SQL avec le paramètre utilisateur
    $params2 = array($GL_NUMERO);
    $result2 = sqlsrv_query($conn, $query2, $params2);

    if ($result2 === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $resultsArray2 = array();
    while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
        $resultsArray2[] = $row2['GPA_LIBELLE'];
    }

    sqlsrv_free_stmt($result2);

    sqlsrv_close($conn);
}
?>

<!-- Formulaire HTML pour saisir GL_TIERS et GL_NUMERO -->
<form method="POST" action="">
    <label for="GL_TIERS">CLIENT :</label>
    <input type="text" name="GL_TIERS" id="GL_TIERS">
    <label for="GL_NUMERO">Nº COMMANDE:</label>
    <input type="text" name="GL_NUMERO" id="GL_NUMERO">
    <input type="submit" value="Rechercher">
</form>

<?php
// Afficher les résultats si disponibles
if (isset($resultsArray1)) {
    echo '<div class="table-container">';
    echo '<table class="table table-striped table-hover">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="column-header text-center"><b>GL_DATECREATION</b></th>';
    echo '<th class="column-header text-center"><b>GL_LIBELLE</b></th>';
    echo '<th class="column-header text-center"><b>GL_DATELIVRAISON</b></th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($resultsArray1 as $result) {
        echo '<tr>';
        echo '<td class="text-center">' . $result['GL_DATECREATION']->format('Y-m-d') . '</td>';
        echo '<td class="text-center">' . $result['GL_LIBELLE'] . '</td>';
        echo '<td class="text-center">' . $result['GL_DATELIVRAISON']->format('Y-m-d') . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

if (isset($resultsArray2)) {
    echo '<div>';
    echo '<h3>Résultats de la requête 2 :</h3>';
    foreach ($resultsArray2 as $result) {
        echo $result . '<br>';
    }
    echo '</div>';
}
?>
