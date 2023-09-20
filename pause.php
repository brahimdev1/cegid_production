<?php
// Connexion à la base de données SQL Server
$serverName = "192.168.1.177";
$connectionOptions = array(
    "Database" => "MEDIDISCEGIDREELLE",
    "Uid" => "SA",
    "PWD" => "cegid.2008"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Vérification de la connexion à la base de données
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Traitement du formulaire
if (isset($_POST['submit_lancer']) && isset($_POST['num_fabrication'])) {
    $id = $_POST['num_fabrication'];

    // Vérifier si le time_in n'est pas déjà défini pour ce numéro de fabrication
    $sqlCheckTimeIn = "SELECT time_in FROM WORDREPHASE WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
    $paramsCheckTimeIn = array($id);
    $stmtCheckTimeIn = sqlsrv_query($conn, $sqlCheckTimeIn, $paramsCheckTimeIn);
    if ($stmtCheckTimeIn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Vérifier s'il y a des résultats renvoyés
    if (sqlsrv_has_rows($stmtCheckTimeIn)) {
        $row = sqlsrv_fetch_array($stmtCheckTimeIn, SQLSRV_FETCH_ASSOC);
        $timeIn = $row['time_in'];

        if ($timeIn === null) {
            // Récupérer l'heure d'arrivée actuelle
            $timeIn = date("d-m-y H:i:s");

            // Mettre à jour l'heure d'arrivée dans la base de données
            $sql = "UPDATE WORDREPHASE SET time_in = ? WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
            $params = array($timeIn, $id);
            $stmt = sqlsrv_query($conn, $sql, $params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            // Redirection vers une page de succès
            echo "Début enregistré : " ;
        } }
    // Redirection vers une autre page après le traitement du formulaire


} elseif (isset($_POST['submit_reception']) && isset($_POST['num_fabrication'])) {
    $id = $_POST['num_fabrication'];

   
        $timeOut = date("d-m-y H:i:s");

        // Mettre à jour l'heure de fin dans la base de données
        $sql = "UPDATE WORDREPHASE SET time_out = ? WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
        $params = array($timeOut, $id);
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

           // Redirection vers une page de succès
   

        echo " fin enregistrée : " ;
     // Redirection vers une autre page après le traitement du formulaire
    
}

// 
/* Execution de difference sans gestion de temps nuits
...

$sql = "UPDATE WORDREPHASE
        SET difference = 
            CASE 
                WHEN time_in IS NOT NULL AND time_out IS NOT NULL THEN
                    DATEDIFF(MINUTE, 
                        CASE
                            WHEN time_in < DATEADD(HOUR, 8, CAST(CONVERT(DATE, time_in) AS DATETIME)) THEN DATEADD(HOUR, 8, CAST(CONVERT(DATE, time_in) AS DATETIME))
                            WHEN time_in > DATEADD(HOUR, 18, CAST(CONVERT(DATE, time_in) AS DATETIME)) THEN DATEADD(HOUR, 18, CAST(CONVERT(DATE, time_in) AS DATETIME))
                            ELSE time_in
                        END,
                        CASE
                            WHEN time_out < DATEADD(HOUR, 8, CAST(CONVERT(DATE, time_out) AS DATETIME)) THEN DATEADD(HOUR, 8, CAST(CONVERT(DATE, time_out) AS DATETIME))
                            WHEN time_out > DATEADD(HOUR, 18, CAST(CONVERT(DATE, time_out) AS DATETIME)) THEN DATEADD(HOUR, 18, CAST(CONVERT(DATE, time_out) AS DATETIME))
                            ELSE time_out
                        END
                    ) - (
                        CASE
                            WHEN (time_in >= '10:00' AND time_in <= '10:10') OR (time_out >= '10:00' AND time_out <= '10:10') THEN 10
                            WHEN (time_in >= '13:00' AND time_in <= '14:00') OR (time_out >= '13:00' AND time_out <= '14:00') THEN 60
                            WHEN (time_in >= '16:00' AND time_in <= '16:10') OR (time_out >= '16:00' AND time_out <= '16:10') THEN 10
                            ELSE 0
                        END
                    ) / (
                        SELECT WOP_QACCSAIS
                        FROM WORDREPHASE AS s
                        WHERE s.WOP_LIGNEORDRE = WORDREPHASE.WOP_LIGNEORDRE
                        AND s.WOP_PHASE = WORDREPHASE.WOP_PHASE
                    )
                ELSE NULL
            END
        WHERE time_in IS NOT NULL AND time_out IS NOT NULL";

$params = array(); // Remplacez $yourCondition par la valeur souhaitée

$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

*/
$sql = "UPDATE WORDREPHASE
        SET difference = DATEDIFF(MINUTE, time_in, time_out) / (
            SELECT WOP_QACCSAIS
            FROM WORDREPHASE AS s
            WHERE s.WOP_LIGNEORDRE = WORDREPHASE.WOP_LIGNEORDRE
            AND s.WOP_PHASE = WORDREPHASE.WOP_PHASE
        )
        WHERE time_in IS NOT NULL AND time_out IS NOT NULL";

$params = array(1); // Remplacez $yourCondition par la valeur souhaitée

$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fermer la connexion à la base de données
sqlsrv_close($conn);



?>

<!DOCTYPE html>
<html>
<head>
    <title>Time In / Time Out</title>
</head>
<body>
 
</body>
</html>
