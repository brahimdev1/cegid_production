<?php
$serverName2 = "192.168.1.177";
$connectionOptions2 = array(
    "Database" => "MEDIDISCEGIDSUIVIEPROD",
    "Uid" => "SA",
    "PWD" => "cegid.2008",
    "TrustServerCertificate"=>true
);

$conn2 = sqlsrv_connect($serverName2, $connectionOptions2);

// Vérification de la connexion
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>