<?php include 'pause.php' ?>

<?php
include'connexion.php';

// Traitement de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numFabrication = $_POST["num_fabrication"];
    // Vérification si le numéro de fabrication existe dans la table
    $sql = "SELECT * FROM WORDREPHASE WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
    $params = array($numFabrication);
    $result = sqlsrv_query($conn, $sql, $params);

    if ($result !== false && sqlsrv_has_rows($result)) {
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        $qteAccepte = $row["WOP_QACCSAIS"];
        $qteLancee = $row["WOP_QLANSAIS"];
        $qteReceptionnee = $row["WOP_QRECSAIS"];
        $qteRestante = $qteAccepte - $qteReceptionnee;

        // Vérifier si le formulaire a été soumis pour lancer la production
        if (isset($_POST["submit_lancer"])) {
            // Vérifier si la quantité restante est supérieure à zéro
            if ($qteRestante > 0) {
                $quantite = $_POST["quantite"];
                $nouvelleQteLancee = $qteLancee + $quantite;

                // Mettre à jour la quantité lancée dans la table
                $sql = "UPDATE WORDREPHASE SET WOP_QLANSAIS = ? WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
                $params = array($nouvelleQteLancee, $numFabrication);
                $result = sqlsrv_query($conn, $sql, $params);

                if ($result !== false) {
                    // Récupérer les valeurs actualisées depuis la base de données
                    $sql = "SELECT WOP_QACCSAIS, WOP_QLANSAIS, WOP_QRECSAIS FROM WORDREPHASE WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
                    $params = array($numFabrication);
                    $result = sqlsrv_query($conn, $sql, $params);
                    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

                    $qteAccepte = $row["WOP_QACCSAIS"];
                    $qteLancee = $row["WOP_QLANSAIS"];
                    $qteReceptionnee = $row["WOP_QRECSAIS"];

                    // Redirection vers la page success.php après le traitement du formulaire
                    echo "<script> window.location.href='success_Lan.php';</script>";

                } else {
                  
                    ?>
                    <div class="error">
                        <?php   echo "Erreur lors du lancement de la quantité."; ?>
                    </div>
                    <?php
                }
            } else {
               
                ?>
                <div class="error">
                    <?php  echo "Aucune action ne peut être effectuée. La quantité restante est égale à zéro."; ?>
                </div>
                <?php
            }
        }

        // Vérifier si le formulaire a été soumis pour réceptionner la production
        if (isset($_POST["submit_reception"])) {
            // Vérifier si la quantité restante est supérieure à zéro
            if ($qteRestante > 0) {
                $quantite = $_POST["quantite"];
                $nouvelleQteReceptionnee = $qteReceptionnee + $quantite;
                $qteRestante = $qteAccepte - $nouvelleQteReceptionnee;

                // Mettre à jour la quantité réceptionnée et la quantité restante dans la table
                $sql = "UPDATE WORDREPHASE SET WOP_QRECSAIS = ? WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
                $params = array($nouvelleQteReceptionnee, $numFabrication);
                $result = sqlsrv_query($conn, $sql, $params);

                if ($result !== false) {
                    // Récupérer les valeurs actualisées depuis la base de données
                    $sql = "SELECT WOP_QACCSAIS, WOP_QRECSAIS FROM WORDREPHASE WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
                    $params = array($numFabrication);
                    $result = sqlsrv_query($conn, $sql, $params);
                    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

                    $qteAccepte = $row["WOP_QACCSAIS"];
                    $qteReceptionnee = $row["WOP_QRECSAIS"];

                    // Redirection vers la page success.php après le traitement du formulaire
                    echo "<script> window.location.href='success_Rec.php';</script>";

                } else {
                    
                    ?>
                    <div class="error">
                        <?php echo "Erreur lors de la réception de la quantité."; ?>
                    </div>
                    <?php
                }
            } else {
                
                ?>
                <div class="error">
                    <?php echo "Aucune action ne peut être effectuée. La quantité restante est égale à zéro."; ?>
                </div>
                <?php
            }
        }
    } else {
        ?>
        <div class="error">
            <?php echo "Le numéro de fabrication n'existe pas."; ?>
        </div>
        <?php
}
}

// ...

// Fermeture de la connexion à la base de données
sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion de la production</title>
    <script src="./node_modules/html5-qrcode/html5-qrcode.min.js"></script>
    <link rel="icon" href="medidis.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.1/dist/css/bootstrap.min.css">
    <style>
        .result {
            background-color: green;
            color: #fff;
            padding: 20px;
        }

        .row {
            display: flex;
        }

        .custom-margin {
            margin-left: 10%;
        }

        .rec {
            margin-top: 10px;
        }

        .dev {
            margin-left: -25%;
            margin-top: 10%;
        }

        .error {
            text-align: center;
        }

        main {
            display: flex;
            justify-content: right;
            align-items: center;
            position: relative;
            top: -30%;
            left: -15%;
        }

        #reader {
            width: 40%;
            border: 2px solid #000;
            background-color: #fff;
        }

        #result {
            font-size: 1.5rem;
        }

        @media (max-width: 2000px) {
            /* Styles spécifiques pour les téléphones */

            /* Place la caméra au centre */
            main {
                flex-direction: column-reverse;
                align-items: center;
                justify-content: center;
                position: relative;
                top: 10%;
                left: 0;
            }

            #reader {
                width: 80%;
                margin-bottom: 20px;
            }

            /* Place les inputs en bas */
            .dev {
                margin-left: 0;
                margin-top: 10px;
            }

            /* Styles supplémentaires pour les boutons */
            .lnc,
            .rec {
                margin-top: 10px;
                width: 100%;
            }
        }
        
    </style>
</head>
<body>
<?php include 'Home.php'; ?>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="dev">
            <div class="mb-3 custom-margin">
                <label for="num_fabrication" class="form-label">Numéro de fabrication :</label>
                <input type="text" class="form-control" id="num_fabrication" name="num_fabrication" placeholder="Code-barres" required>
            </div>

            <div class="mb-3 custom-margin">
                <label for="quantite" class="form-label">Quantité :</label>
                <input type="number" class="form-control" id="quantite" name="quantite" placeholder="Qte a Saisir" required>
            </div>

            <div class="d-grid gap-2 custom-margin">
                <div class="lnc">
                    <button type="submit" class="btn btn-primary" name="submit_lancer">Lancer la production</button>
                </div>
                <div class="rec">
                    <button type="submit" class="btn btn-primary" name="submit_reception">Réceptionner la production</button>
                </div>
            </div>
        </div>
    </form>

    <main>
        <div id="reader"></div>
        <div id="result"></div>
    </main>

    <script>
        const scanner = new Html5QrcodeScanner('reader', {
            qrbox: {
                width: 250,
                height: 150,
            },
            fps: 100,
        });

        scanner.render(success, error);

        function success(result) {
            document.getElementById('num_fabrication').value = result;
            // Met le résultat du scanner dans l'input avec l'id "num_fabrication"

            scanner.clear();
            document.getElementById('reader').remove();
        }

        function error(err) {
            console.error(err);
        }
    </script>

</body>
</html>
