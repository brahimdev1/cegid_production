<?php
include 'Home.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sélection de magasin</title>
    <style>
        form,h1 {
    text-align : center;
        }
        .buttonImp{
            
  position: relative;
  top: 50%;
  left: 43%;

}
         form {
            margin: 20px auto;
        }

        label {
            font-weight: bold;
        }

        select {
            padding: 5px;
            font-size: 16px;
        }

        #photoMagasin img{
    top: 50%;
    left: 50%;
    margin: 20px auto;
    width: 15cm; /* Largeur de l'image de 10 cm */
    height: 20cm; /* Hauteur de l'image de 10 cm */
}


        button {
            display: block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    
    <h1>étiquette LUXUS</h1>
    <form>
        <label for="magasin">Choisissez une étiquette :</label>
        <select id="magasin" name="magasin">
           <option value="select">----selectionner une Dimension----</option>
            <option value="magasin1">LUXUS 90x200</option>
            <option value="magasin2">LUXUS 120x200</option>
            <option value="magasin3">LUXUS 140X190</option>
            <option value="magasin4">LUXUS 140X200</option>
            <option value="magasin5">LUXUS 160X190</option>
            <option value="magasin6">LUXUS 160x200</option>
            <option value="magasin7">LUXUS 180x200</option>
        




        </select>
    
 
    <div id="photoMagasin">
        <img src="" alt="Photo du magasin">
    </div>
   
    <button class="buttonImp" id="imprimerButton" >  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
            </svg> Imprimer </button>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Récupérer la liste déroulante et l'élément img
            const magasinSelect = document.getElementById("magasin");
            const photoMagasin = document.querySelector("#photoMagasin img");

            // Écouter les changements de sélection
            magasinSelect.addEventListener("change", function () {
                // Récupérer la valeur sélectionnée
                const selectedMagasin = magasinSelect.value;

                // Mettre à jour l'attribut src de l'image en fonction de la sélection
                switch (selectedMagasin) {
                    case "magasin1":
                        photoMagasin.src = "./Etiquette_matelas/luxus/9094301-LUXUS MEMORY MATELAS DIM 90X200 CM-1.png";
                        break;
                    case "magasin2":
                        photoMagasin.src = "./Etiquette_matelas/luxus/9094302-LUXUS MEMORY MATELAS DIM 120X200 CM-1.png";
                        break;
                    case "magasin3":
                        photoMagasin.src = "./Etiquette_matelas/luxus/9094303-LUXUS MEMORY MATELAS DIM 140X190 CM-1.png";
                        break;
                    case "magasin4":
                        photoMagasin.src = "./Etiquette_matelas/luxus/9094304-LUXUS MEMORY MATELAS DIM 140X200 CM-1.png";
                        break; 
                    case "magasin5":
                        photoMagasin.src = "./Etiquette_matelas/luxus/9094305-LUXUS MEMORY MATELAS DIM 160X190 CM-1.png";
                        break;                       
                    case "magasin6":
                        photoMagasin.src = "./Etiquette_matelas/luxus/9094306-LUXUS MEMORY MATELAS DIM 160X200 CM-1.png";
                        break;
                    case "magasin7":
                        photoMagasin.src = "./Etiquette_matelas/luxus/9094547-LUXUS MEMORY MATELAS DIM 180X200 CM-1.png";
                        break;
               

                    
                    // Ajoutez autant de cas que nécessaire pour chaque magasin
                    default:
                        photoMagasin.src = ""; // Si aucune sélection ou sélection invalide, effacez l'image
                        break;
                }
            });

            // Fonction pour imprimer la photo au format A4
            function imprimerAuFormatA4() {
                const imprimerDiv = document.createElement('div');
                imprimerDiv.appendChild(photoMagasin.cloneNode(true));

                // Créer une fenêtre d'impression
                const printWindow = window.open('', '', 'width=900,height=600');
                printWindow.document.open();
                printWindow.document.write('<html><head><title>Impression</title></head><body>');
                printWindow.document.write(imprimerDiv.innerHTML);
                printWindow.document.write('</body></html>');
                printWindow.document.close();

                // Imprimer le document
                printWindow.print();
                printWindow.close();
            }

            // Écouter le clic sur un bouton d'impression (ajoutez un bouton dans votre HTML pour imprimer)
            const imprimerButton = document.getElementById("imprimerButton");
            imprimerButton.addEventListener("click", function () {
                imprimerAuFormatA4();
            });
        });
    </script>
</body>
</html>
