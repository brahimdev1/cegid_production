<?php
$serverName = "192.168.1.177";
$connectionOptions = array(
    "Database" => "MEDIDISCEGIDREELLE",
    "Uid" => "SA",
    "PWD" => "cegid.2008"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

// Vérification de la connexion
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>