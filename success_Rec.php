<!DOCTYPE html>
<html>
<head>
<link rel="icon" href="medidis.png" />

    <title>Lancement OK</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }

        .success-message {
            margin-top: 200px;
            padding: 20px;
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            border-radius: 4px;
            display: inline-block;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            margin-bottom: 0;
        }
        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="success-message">
        <h1>Reception effectué avec succès !</h1>
        <p>Le processus de Reception a été terminé avec succès.</p>
    </div>
    <?php
    // Vérifier si le bouton "Retour" a été cliqué
    if (isset($_POST['retour'])) {
        // Rediriger vers la page login.php
        header("Location: home.php");
        exit(); // Assure que le script se termine après la redirection
    }
    ?>
    
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input class="button" type="submit" name="retour" value="Retour">
    </form>
</body>
</html>
