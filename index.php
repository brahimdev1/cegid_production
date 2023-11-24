<?php
session_start();

// Configuration de la connexion à la base de données
$server = "serveurmedidis2";
$database = ""; // Nom de la base de données (sera défini par l'utilisateur)
$username = "SA"; // Nom d'utilisateur de la base de données
$password = "cegid.2008"; // Mot de passe de la base de données

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedDb = $_POST["database"];

    // Vérifier si une base de données a été sélectionnée
    if (empty($selectedDb)) {
        die("Veuillez sélectionner une base de données.");
    }

    // Vérifier si la base de données sélectionnée est autorisée
    $allowedDatabases = array("MEDIDISTEST", "MEDIDISCEGIDREELLE");
    if (!in_array($selectedDb, $allowedDatabases)) {
        die("Base de données non autorisée.");
    }

    // Connexion à la base de données sélectionnée
    $conn = sqlsrv_connect($server, array("Database" => $selectedDb, "UID" => $username, "PWD" => $password,"TrustServerCertificate"=>true));
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Vérification de l'utilisateur et du mot de passe
    // Vérification de l'utilisateur et du mot de passe
$inputUsername = $_POST["username"];
$inputPassword = $_POST["password"];
$sql = "SELECT * FROM utilisat WHERE US_ABREGE = ? AND US_TEL1 = ?";
$params = array($inputUsername, $inputPassword);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row) {
    // L'utilisateur existe et le mot de passe est correct

    // Stocker le nom d'utilisateur dans la session
    $_SESSION["username"] = $row["username"];

    // Utilisateur authentifié
    $_SESSION["authenticated"] = true;

    // Rediriger vers la page appropriée en fonction du type d'utilisateur
    if ($row["usertype"] === "user" || $row["usertype"] === "admin") {
        // Rediriger vers le tableau de bord
        header("Location: home.php");
    }
    exit();
} else {
    // Nom d'utilisateur ou mot de passe incorrect
    $errorMessage = "Nom d'utilisateur ou mot de passe incorrect.";
}

    if ($row) {
        // L'utilisateur existe et le mot de passe est correct

        // Stocker le nom d'utilisateur dans la session
        $_SESSION["username"] = $row["username"];

        // Rediriger vers la page appropriée en fonction du type d'utilisateur
        if ($row["usertype"] === "user") {
            header("Location: home.php");
        } elseif ($row["usertype"] === "admin") {
            header("Location: home.php");
        }
        exit();
    } else {
        // Nom d'utilisateur ou mot de passe incorrect
        $errorMessage = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Cegid Production</title>
    <link rel="icon" type="image/png" sizes="180x180" href="medidis.png">
<link rel="icon" type="image/png" sizes="32x32" href="medidis.png">
<link rel="icon" type="image/png" sizes="16x16" href="medidis.png">



    <style>
        body {
            background-color: #f1f1f1;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
        }

        .login-container {
            background-color: #fff;
            width: 400px;
            margin: 0 auto;
            padding: 30px;
            margin-top: 50px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .login-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .login-container select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
        }

        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <center>
        <h1>Formulaire de connexion</h1>
        <div class="login-container">
            <form action="#" method="POST">
                <label>Nom Database :</label>
                <select name="database">
                    <option value="MEDIDISTEST">MEDIDISTEST</option>
                    <option value="MEDIDISCEGIDREELLE" selected>MEDIDISCEGIDREELLE</option>
                </select>
                <label>Nom d'utilisateur :</label>
                <input type="text" name="username" required>

                <label>Mot de passe :</label>
                <input type="password" name="password" required>

                <input type="submit" value="Se connecter">
                <?php if (isset($errorMessage)): ?>
                    <p class="error-message"><?php echo $errorMessage; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </center>
</body>
</html>
