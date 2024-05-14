<?php
// Code pour la connexion à la base de données (incluez 'connexion.php' si nécessaire)
include 'connexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['num_fabrication'])) {
    // Assurez-vous de bien inclure le fichier de connexion à la base de données ici si nécessaire

    // Récupérez le numéro de fabrication depuis la requête POST
    $numFabrication = $_POST['num_fabrication'];

    // Effectuer la requête SQL pour obtenir les quantités depuis la base de données
    // Vous devrez adapter cette requête SQL en fonction de la structure de votre base de données
    $sql = "SELECT WOP_QRECSAIS, (WOP_QACCSAIS - WOP_QRECSAIS) AS qteRestante FROM WORDREPHASE WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
    $params = array($numFabrication);
    $result = sqlsrv_query($conn, $sql, $params);

    // Vérifiez si la requête a réussi et s'il y a des résultats
    if ($result !== false && sqlsrv_has_rows($result)) {
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        // Obtenez les quantités réceptionnées et restantes à partir des résultats de la requête
        $qteReceptionnee = $row['WOP_QRECSAIS'];
        $qteRestante = $row['qteRestante'];
        
 // Assurez-vous que la quantité restante ne devient pas négative
 if ($qteRestante < 0) {
    $qteRestante = 0;
}
        // Construire et renvoyer la réponse JSON
        $response = array('qteReceptionnee' => $qteReceptionnee, 'qteRestante' => $qteRestante);
        echo json_encode($response);
    } else {
        // Si la requête échoue ou s'il n'y a pas de résultats, renvoyez une réponse JSON vide ou un message d'erreur
        echo json_encode(array('error' => 'Les quantités n\'ont pas pu être récupérées depuis la base de données.'));
    }

    // Fermez la connexion à la base de données si nécessaire
    sqlsrv_close($conn);

    // Terminez le script
    exit;
}
?>
