<?php include 'pause.php' ;
include 'connexion.php';


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
            if ($qteReceptionnee == 0 )  {
                $quantite = $_POST["quantite"];
                $nouvelleQteLancee = $qteLancee + $quantite;
        
                // Mettre à jour la quantité lancée dans la table
                $sql = "UPDATE WORDREPHASE SET WOP_QLANSAIS = ?, WOP_ETATPHASE='LAN' WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
                $params = array($nouvelleQteLancee, $numFabrication);
                $result = sqlsrv_query($conn, $sql, $params);
        
                if ($result === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
        
                // Redirection vers la page success.php après le traitement du formulaire
                echo "<script> window.location.href='success_Lan.php';</script>";
            } else {
                // Mettre à jour la quantité lancée dans la table
                $quantite = $_POST["quantite"];
                $nouvelleQteLancee = $qteLancee + $quantite;
                $sql = "UPDATE WORDREPHASE SET WOP_QLANSAIS = ?, WOP_ETATPHASE='REC' WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
                $params = array($nouvelleQteLancee, $numFabrication);
                $result = sqlsrv_query($conn, $sql, $params);
        
                if ($result === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
        
                echo "<script> window.location.href='success_Lan.php';</script>";
                    ?>
                    
                    <?php
                }
            } else {
               
                ?>
                <div class="error">
                    <?php  echo "Erreur de saisie d'rdre de fabrication."; ?>
                </div>
                <?php
            }
        }

       // Vérifier si le formulaire a été soumis pour réceptionner la production
if (isset($_POST["submit_reception"])) {
    // Vérifier si la quantité restante est supérieure à zéro
    if ($qteRestante > 0 && $qteReceptionnee < $qteAccepte) {
        $quantite = $_POST["quantite"];
        $nouvelleQteReceptionnee = $qteReceptionnee + $quantite;
        $qteRestante = $qteAccepte - $nouvelleQteReceptionnee;

        // Mettre à jour la quantité réceptionnée et la quantité restante dans la table
        $sql = "UPDATE WORDREPHASE SET WOP_QRECSAIS = ?, WOP_ETATPHASE = ?,WOP_AVANCEQTE = ($nouvelleQteReceptionnee*100/$qteAccepte) WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
        
        // Si la quantité restante atteint zéro, mettre à jour WOP_ETATPHASE à 'TER'
        $etatPhase = ($qteRestante == 0) ? 'TER' : 'REC';
        
        $params = array($nouvelleQteReceptionnee, $etatPhase, $numFabrication);
        $result = sqlsrv_query($conn, $sql, $params);

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
                    <?php echo "Erreur de saisie d'rdre de fabrication."; ?>
                </div>
                <?php
            }
        }
  


// ...

// Fermeture de la connexion à la base de données
sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>


    <style>
       
        .custom-margin {
            margin-bottom: 15px;
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
            button{
                background-color: #10D83A;
                width: 250px;
                display: block;
                margin-left: 100 px;
                margin-right: center;
             

            }
        }
        
    </style>
  
  <style>
    /* Style pour l'overlay de chargement */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7); /* Fond semi-transparent */
        z-index: 1000; /* Assure que l'overlay est au-dessus de tout le reste */
        justify-content: center;
        align-items: center;
    }

    /* Style pour l'indicateur de chargement */
    .loader {
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

</head>
<body>
<?php include 'Home.php'; ?>
<div class="overlay" id="loadingOverlay">
    <div class="loader"></div>
</div>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="dev">
            <div class="mb-3 custom-margin">
                <label for="num_fabrication" class="form-label">Code-barres :</label>
                <input type="text" class="form-control" id="num_fabrication" name="num_fabrication" placeholder="Code-barres" required>
            </div>

            <div class="mb-3 custom-margin">
                <label for="quantite" class="form-label">Quantité :</label>
                <input type="number" class="form-control" id="quantite" name="quantite" placeholder="Qte a Saisir" required>
            </div>
           
            <div class="d-grid gap-2 custom-margin">
                <div class="lnc">
                    <button  class="btn"  name="submit_lancer" >Lancer la production</button>
                </div>
                <div class="rec">
                    <button  class="btn"  name="submit_reception" ">Réceptionner la production</button>
                </div>
                

            </div>
        </div>
    </form>
   <script>
    // Affiche l'overlay de chargement
function showLoadingOverlay() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

// Masque l'overlay de chargement
function hideLoadingOverlay() {
    document.getElementById('loadingOverlay').style.display = 'none';
}

// Exemple d'utilisation lors de la soumission du formulaire
document.addEventListener('submit', function () {
    showLoadingOverlay();
});

    </script>
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

            scanner.clear();
            document.getElementById('reader').remove();
        }

        function error(err) {
            console.error(err);
        }
    </script>

</body>
</html>