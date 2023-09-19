<!DOCTYPE html>
<html>
<head>
    <title>Atelier de Matelas Appel d'offre</title>
    <style>
        /* Styles spécifiques pour un écran TV de 40 pouces (largeur approximative) */
@media screen and (min-width: 1000px) {
    body {
        font-size: 24px; /* Taille de police plus grande pour les TV */
    }

    h1 {
        font-size: 80px; /* Taille de police plus grande pour les titres */
    }

    h2 {
        font-size: 45px; /* Taille de police plus grande pour les sous-titres */
    }

    .heure {
        font-size: 60px; /* Taille de police plus grande pour l'horloge */
    }

    /* Ajoutez d'autres styles spécifiques pour les écrans TV ici */
}

  /* Styles pour les h2 dans la table */
  table h2 {
        font-size: 70px; /* Modifiez la taille de police selon vos préférences */
        color:white;
        left:100px;
  }
 table input, table span {
    font-size: 100px; /* Modifiez la taille de police selon vos préférences */
    color:white;
    text-align:center;
    font-weight: bold ;
 }
        body {
            margin: 0%;
            padding: 0%;
            background-color: green; /* Couleur d'arrière-plan verte */
            font-family: Arial, sans-serif; /* Police de caractères par défaut */
        }

        h1 {
            font-size: 95px;
            color: white;
            text-decoration: underline;
        }
        h2{
            position:relative;
            font-size: 80px;
            color: white;
        }
        .heure {
            /*            position: absolute;*/
        position: absolute;
        left:1000px;
        top: 50px;
        color: white;
        border-style: inset;
        font-size: 100px; /* Taille de police plus grande pour les titres et les paragraphes */

        /* Styles pour les informations avec une grande taille de texte */
        }
       
        /* Sélectionnez tous les éléments input dans le formulaire */
/* Sélectionnez tous les éléments input dans le formulaire et changez la couleur de fond en vert */
form input {
    background-color: green;
}

        /* Ajoutez d'autres styles CSS selon vos besoins */
    </style>
</head>
<body>
    <form>
    
        <h1>ATELIER MATELAS</h1>
        
        <h1 style="text-align:center">Appel d'offre : <span style="color: yellow;">AREF MARRAKECH</span></h1>
         
    <!-- Section de la quantité commandée -->
    <h2 style="text-align:center">Quantité Commandée : <span style="color: yellow; font-size:75px;" id="objectifGeneral">1320</span></h2>
    <div class="heure">
    
    <span id="dateEtHeureActuelles"></span>
</div>
    <table width="100%" height="300px" >
  <tr>
    <td><h2 for="objectifJour">Objectif du Jour</h2></td>
    <td style="text-align: center;"><input style="color:yellow" type="number" id="objectifJour" min="0"></td>
  </tr>
  <tr>
    <td><h2 for="quantiteFabriquee">Réalisé :</h2></td>
    <td style="text-align: center;"><input type="number" id="quantiteFabriquee" min="0"></td>
  </tr>
  <tr>
    <td><h2>Reste :</h2></td>
    <td style="text-align: center;" ><span style="color:black ; font-weight: bold ; font-size:100px ; " id="resteAFabriquer"></span></td>
    <tr>
            <td><h2>fabriqué Global :</h2></td>
            <td style="text-align: center;"><span style="color: black; font-weight: bold; font-size: 100px;" id="fabglobal"></span></td>
        </tr>
  
  <tr>
    <td><h2>Reste Global :</h2></td>
    <td style="text-align: center; " ><span style="color:black ; font-weight: bold ;text-decoration: underline; font-size:100px; text-align:center" id="resteAppelOffreGeneral"></span></td>
  </tr>
</table>

    <form>


    </form>
    <button onclick="window.print()">Print this page</button>

    <script>
    // Fonction pour mettre à jour la date et l'heure actuelles
    function updateDateEtHeureActuelles() {
        const now = new Date();
        const dateEtHeureActuelles = now.toLocaleString(); // Obtenir la date et l'heure au format complet
        document.getElementById('dateEtHeureActuelles').textContent = dateEtHeureActuelles;
    }

    // Fonction pour mettre à jour le reste à fabriquer
    function updateResteAFabriquer() {
        const objectifJour = parseInt(document.getElementById('objectifJour').value);
        const quantiteFabriquee = parseInt(document.getElementById('quantiteFabriquee').value);
        const quantiteDejaFabriquee = 1069; // Remplacez ceci par la quantité déjà fabriquée réelle

        const resteAFabriquer = objectifJour - quantiteFabriquee;
        document.getElementById('resteAFabriquer').textContent = resteAFabriquer;

        // Calculer le reste d'appel d'offre général
        const resteAppelOffreGeneral = 1320 - (quantiteFabriquee + quantiteDejaFabriquee);
        document.getElementById('resteAppelOffreGeneral').textContent = resteAppelOffreGeneral;

        // Calculer le total
        const x1 = parseFloat(document.getElementById("objectifGeneral").textContent);
        const x2 = parseFloat(document.getElementById("resteAppelOffreGeneral").textContent);

        if (!isNaN(x1) && !isNaN(x2)) {
            const total = x1 - x2;
            document.getElementById("fabglobal").textContent = total;
        }
    }

    // Écouteurs d'événements pour les saisies d'utilisateur
    document.getElementById('objectifJour').addEventListener('input', updateResteAFabriquer);
    document.getElementById('quantiteFabriquee').addEventListener('input', updateResteAFabriquer);

    // Mettre à jour la date et l'heure actuelles toutes les secondes
    setInterval(updateDateEtHeureActuelles, 1000);

    // Appeler la fonction initiale pour afficher les valeurs initiales
    updateResteAFabriquer();
    updateDateEtHeureActuelles();

</script>
</body>
</html>
