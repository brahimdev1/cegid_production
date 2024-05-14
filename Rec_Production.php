<?php 
include 'connexion.php';
include 'connexion1.php';


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
        $quantite = $_POST["quantite"];


        //RECUPERE GA_LIBELLE NOM D'ARTICLE DE RECEPTION
        $sql = "SELECT wr.*, a.GA_LIBELLE,wl.WOL_CHARLIBRE3,wl.WOL_CHARLIBRE2,wl.WOL_NUMERO,t.T_LIBELLE,a.GA_LIBREART8
        FROM WORDREPHASE AS wr
        INNER JOIN ARTICLE AS a ON wr.WOP_CODEARTICLE = a.GA_CODEARTICLE
        INNER JOIN WORDRELIG AS wl ON wl.WOL_LIGNEORDRE = wr.WOP_LIGNEORDRE
        INNER JOIN TIERS as t ON t.T_TIERS = wl.WOL_TIERS

        WHERE CAST(wr.WOP_LIGNEORDRE AS varchar) + '$' + wr.WOP_PHASE = ?";
$params = array($numFabrication);
$result = sqlsrv_query($conn, $sql, $params);

if ($result !== false && sqlsrv_has_rows($result)) {
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
//FIN RECUPERATION


                    if (isset($_POST["submit_reception"])) {
        
                                                $phaseActuelle = $row["WOP_OPECIRC"];
                                                $OFActuelle = $row["WOP_LIGNEORDRE"];
                                            
                                                $quantite = $_POST["quantite"];
                                                $nouvelleQteReceptionnee = $qteReceptionnee + $quantite;
                                                $qteRestante = $qteAccepte - $nouvelleQteReceptionnee;

                                                // Mettre à jour la quantité réceptionnée et la quantité restante dans la table
                                                $sql = "UPDATE WORDREPHASE SET WOP_QRECSAIS = ?, WOP_ETATPHASE = ?, WOP_AVANCEQTE = ($nouvelleQteReceptionnee*100/$qteAccepte) WHERE CAST(WOP_LIGNEORDRE AS varchar) + '$' + WOP_PHASE = ?";
                                                
                                                // Si la quantité restante atteint zéro, mettre à jour WOP_ETATPHASE à 'TER'
                                                $etatPhase = ($qteRestante == 0 || $qteRestante < 0) ? 'TER' : 'REC';

                                                $params = array($nouvelleQteReceptionnee, $etatPhase, $numFabrication);
                                                $result = sqlsrv_query($conn, $sql, $params);
                                                $action="Reception";


$insertSql = "INSERT INTO HistoriqueReception (WOP_LIGNEORDRE, WOP_OPECIRC, WOP_PHASELIB,WOP_CODEPHASE,WOP_ACTION,WOP_QSAIS,WOP_CODEARTICLE,GA_LIBELLE,GA_LIBREART8,WOL_CHARLIBRE1,WOL_CHARLIBRE2,WOL_NUMERO,WOL_TIERS) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
$insertParams = array($row['WOP_LIGNEORDRE'], $row['WOP_OPECIRC'], $row['WOP_PHASELIB'], $row['WOP_PHASE'], $action, $quantite,$row['WOP_CODEARTICLE'],$row['GA_LIBELLE'],$row['GA_LIBREART8'],$row['WOL_CHARLIBRE3'],$row['WOL_CHARLIBRE2'],$row['WOL_NUMERO'],$row['T_LIBELLE']);
$insertResult = sqlsrv_query($conn2, $insertSql, $insertParams);

// Vérification du résultat de l'insertion
if ($insertResult === false) {
    die(print_r(sqlsrv_errors(), true));
}
                    }
                                                // Récupérer la phase suivante depuis la base de données
                                                $sqlPhaseSuivante = "SELECT TOP 1 wr.WOP_OPECIRC, wr.WOP_QLANSAIS, wr.WOP_QACCSAIS,wr.WOP_PHASELIB,wr.WOP_PHASE,wr.WOP_CODEARTICLE,a.GA_LIBELLE,a.GA_LIBREART8,wl.WOL_CHARLIBRE3,wl.WOL_CHARLIBRE2,wl.WOL_NUMERO,t.T_LIBELLE
                                                                         FROM WORDREPHASE as wr
                                                                         INNER JOIN ARTICLE AS a ON wr.WOP_CODEARTICLE = a.GA_CODEARTICLE
                                                                         INNER JOIN WORDRELIG AS wl ON wl.WOL_LIGNEORDRE = wr.WOP_LIGNEORDRE
                                                                         INNER JOIN TIERS as t ON t.T_TIERS = wl.WOL_TIERS

                                                                        WHERE WOP_LIGNEORDRE = ? AND WOP_OPECIRC > ? ORDER BY WOP_OPECIRC ";
                                                $paramsPhaseSuivante = array($OFActuelle, $phaseActuelle);
                                                $resultPhaseSuivante = sqlsrv_query($conn, $sqlPhaseSuivante, $paramsPhaseSuivante);

                                                if ($resultPhaseSuivante && sqlsrv_has_rows($resultPhaseSuivante)) {
                                                    $rowPhaseSuivante = sqlsrv_fetch_array($resultPhaseSuivante);
                                                    $phaseSuivante = $rowPhaseSuivante['WOP_OPECIRC'];
                                                    $qteLanceePhaseSuivante = $rowPhaseSuivante['WOP_QLANSAIS'];
                                                    $qteAccepteePhaseSuivante = $rowPhaseSuivante['WOP_QACCSAIS'];
                                                    $libellePhaseSuivante = $rowPhaseSuivante['WOP_PHASELIB'];
                                                    $codePhaseSuivante = $rowPhaseSuivante['WOP_PHASE'];
                                                    $codeArticleSuivante = $rowPhaseSuivante['WOP_CODEARTICLE'];
                                                    $LibelleArticleSuivante = $rowPhaseSuivante['GA_LIBELLE'];
                                                    $LibelleDEPOTSuivante = $rowPhaseSuivante['GA_LIBREART8'];
                                                    $LibelleNCMDSuivante = $rowPhaseSuivante['WOL_CHARLIBRE3'];
                                                    $LibelleNFICHESuivante = $rowPhaseSuivante['WOL_CHARLIBRE2'];
                                                    $LibelleNCMDSYSSuivante = $rowPhaseSuivante['WOL_NUMERO'];
                                                    $LibelleCLIENTSuivante = $rowPhaseSuivante['T_LIBELLE'];



                                                    // Calculer la quantité restante disponible dans la phase suivante
                                                    $qteRestantePhaseSuivante = $qteAccepteePhaseSuivante - $qteLanceePhaseSuivante;

                                                    // Calculer la quantité à lancer pour la phase suivante
                                                    $qteLancerPourPhaseSuivante = min($qteRestantePhaseSuivante, $quantite);

                                                    // Mettre à jour la quantité lancée pour la phase suivante
                                                    $nouvelleQteLanceePhaseSuivante = $qteLanceePhaseSuivante + $qteLancerPourPhaseSuivante;
                                                    $sqlMajQuantitePhaseSuivante = "UPDATE WORDREPHASE SET WOP_QLANSAIS = ?, WOP_ETATPHASE = 'LAN' WHERE WOP_LIGNEORDRE = ? AND WOP_OPECIRC = ?";
                                                    $paramsMajQuantitePhaseSuivante = array($nouvelleQteLanceePhaseSuivante, $OFActuelle, $phaseSuivante);
                                                    $resultMajQuantitePhaseSuivante = sqlsrv_query($conn, $sqlMajQuantitePhaseSuivante, $paramsMajQuantitePhaseSuivante);
                                                   // Enregistrement de l'historique pour la deuxième mise à jour
$action2 = "Lancement"; // Définir l'action comme un lancement

$insertSql2 = "INSERT INTO HistoriqueReception (WOP_LIGNEORDRE, WOP_OPECIRC, WOP_PHASELIB, WOP_CODEPHASE, WOP_ACTION, WOP_QSAIS,WOP_CODEARTICLE,GA_LIBELLE,GA_LIBREART8,WOL_CHARLIBRE1,WOL_CHARLIBRE2,WOL_NUMERO,WOL_TIERS) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
$insertParams2 = array($OFActuelle, $phaseSuivante,$libellePhaseSuivante,$codePhaseSuivante, $action2, $qteLancerPourPhaseSuivante,$codeArticleSuivante,$LibelleArticleSuivante,$LibelleDEPOTSuivante,$LibelleNCMDSuivante,$LibelleNFICHESuivante,$LibelleNCMDSYSSuivante,$LibelleCLIENTSuivante); // Utilisez $qteLancerPourPhaseSuivante pour enregistrer la quantité lancée pour cette phase
$insertResult2 = sqlsrv_query($conn2, $insertSql2, $insertParams2);

// Vérification du résultat de l'insertion
if ($insertResult2 === false) {
    die(print_r(sqlsrv_errors(), true));
}

    
                                                            // Assurez-vous de gérer les résultats de la mise à jour, par exemple, vérifiez si elle a réussi ou non.
                                                        }
                                                        // Afficher une alerte de succès avec Bootstrap
                                                echo '<div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert" style="text-align: center;">';
                                                echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">';
                                                echo '  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>';
                                                echo '</svg>';
                                                echo '  SUCCES: votre reception de ' . $_POST["quantite"] . ' a été enregistré avec succès.';
                                                echo '</div>';

                                                 }  
                                            
                                                    }
                                                    else {
                                                       
                                                    
                                                        // Afficher un message d'erreur indiquant que la requête n'a pas été envoyée
                                                        // Vous pouvez également ajouter un message d'erreur ici pour confirmer que le bloc else est atteint
                                                        echo '<div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style="text-align: center;">';
                                                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">';
                                                        echo '  <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>';
                                                        echo '</svg>';
                                                        echo '  ERREUR: l\'Ordre de fabrication saisi est incorrect.';
                                                        echo '</div>';
                                                    }
                                                }

  

// Fermeture de la connexion à la base de données
sqlsrv_close($conn);
?>
<script>
// Cacher lentement l'alerte après 5 secondes
setTimeout(function() {
    var successAlert = document.getElementById('successAlert');
    if (successAlert) {
        successAlert.classList.add('hide');
        successAlert.addEventListener('transitionend', function() {
            successAlert.remove(); // Supprimer l'alerte du DOM une fois la transition terminée
        });
    }
}, 5000);
</script>


<style>
/* Ajouter la classe hide pour cacher lentement l'alerte */
.hide {
opacity: 0;
transition: opacity 3s ease-out;
}
</style>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
  
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
<div id="loadingOverlay" class="overlay">
    <div class="loader"></div>
</div>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="dev">
            <div class="mb-3 custom-margin">
                <label for="num_fabrication" class="form-label">Code-barres OF:</label>
                <input type="text" class="form-control" id="num_fabrication" name="num_fabrication" placeholder="Code-barres" required autofocus>
            </div>
            <div class="mb-3 custom-margin">
                <label for="qte_receptionnee" class="form-label">déjà réceptionnée :</label>
                <input type="text" class="form-control" id="qte_receptionnee" name="qte_receptionnee" readonly>
            </div>
            <div class="mb-3 custom-margin">
                <label for="quantite" class="form-label">Reception saisie :</label>
                <input type="tel" class="form-control" id="quantite" name="quantite" placeholder="Qte a Saisir" required pattern="\d+([,.]\d+)?">
            </div>
             
                    <div class="rec">
                    <button  class="btn"  name="submit_reception" style="background-color:#11c911">Réceptionner la production</button>
                </div>
                 

            </div>
        </div>
    </form>
    <script>
       document.getElementById('num_fabrication').addEventListener('input', function() {
    var numFabrication = this.value;

    // Effectuer une requête AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'get_quantities.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            // Mettre à jour les champs de quantité avec les valeurs reçues
            document.getElementById('qte_receptionnee').value = response.qteReceptionnee;
            document.getElementById('quantite').value = response.qteRestante; // Mettre à jour la quantité restante
        }
    };
    xhr.send('num_fabrication=' + numFabrication);
});

    </script>
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
    

</body>
</html>