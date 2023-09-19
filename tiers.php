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
    
    <h1>Sélection de magasin</h1>
    <form>
        <label for="magasin">Choisissez un magasin :</label>
        <select id="magasin" name="magasin">
           <option value="select">----selectionner un magasin----</option>
            <option value="magasin1">YATOUT HOME NEJMA</option>
            <option value="magasin2">MOBILIUM TEMARA</option>
            <option value="magasin3">YADECO MARJANE TANGER</option>
            <option value="magasin4">YATOUT MLY YOUSSEF TANGER</option>
            <option value="magasin5">YATOUT BERCHET</option>
            <option value="magasin6">MOBILIUM SALE MARINA</option>
            <option value="magasin7">YADECO SAFI</option>
            <option value="magasin8">MIZA EQUIPEMENT</option>
            <option value="magasin9">YATOUT HOME RABAT CENTRE</option>
            <option value="magasin10">MOBILIUM RABAT AGDAL</option>
            <option value="magasin11">YATOUT HOME MOHAMMEDIA</option>
            <option value="magasin12">MOBILIUM MEKNES ATACADAO</option>
            <option value="magasin13">YADECO MEKNES</option>
            <option value="magasin14">YATOUT HOME MARRAKECH</option>
            <option value="magasin15">MOBILIUM LARRACHE</option>
            <option value="magasin16">YATOUT HOME LKSAR</option>
            <option value="magasin17">YATOUT OULED OUJIH</option>
            <option value="magasin18">YATOUT HOME FES AGDAL</option>
            <option value="magasin19"> YATOUT HOME EL JADIDA</option>
            <option value="magasin20">ISTIKBAL DAR BOUAZZA</option>
            <option value="magasin21">MOBILIUM BOUSKOURA</option>
            <option value="magasin22">YATOUT MAARIF</option>
            <option value="magasin23">ISTIKBAL AIN SEBAA</option>
            <option value="magasin24">YATOUT HOME AIN SEBAA</option>
            <option value="magasin25">ISTIKBAL AGADIR TILILA</option>
            <option value="magasin26">YADECO MARRAKECH</option>
            <option value="magasin27">YATOUT HOME AGADIR</option>

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
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-1.png";
                        break;
                    case "magasin2":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-2.png";
                        break;
                    case "magasin3":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-3.png";
                        break;
                     case "magasin4":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-4.png";
                        break; 
                        case "magasin5":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-5.png";
                        break;                       
                        case "magasin6":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-6.png";
                        break;
                        case "magasin7":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-7.png";
                        break;
                        case "magasin8":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-8.png";
                        break;
                        case "magasin9":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-9.png";
                        break;
                        case "magasin10":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-10.png";
                        break;
                        case "magasin11":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-11.png";
                        break;
                        case "magasin12":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-12.png";
                        break;
                        case "magasin13":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-13.png";
                        break;
                        case "magasin14":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-14.png";
                        break;
                        case "magasin15":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-15.png";
                        break;
                        case "magasin16":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-16.png";
                        break;
                        case "magasin17":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-17.png";
                        break;
                        case "magasin18":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-18.png";
                        break;
                        case "magasin19":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-19.png";
                        break;
                        case "magasin20":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-20.png";
                        break;
                        case "magasin21":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-21.png";
                        break;
                        case "magasin22":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-22.png";
                        break;
                        case "magasin23":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-23.png";
                        break;
                        case "magasin24":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-24.png";
                        break;
                        case "magasin25":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-25.png";
                        break;
                        case "magasin26":
                        photoMagasin.src = "./TIERS_png/p1h8hh2cr51sbs1hb6achvdr8or4-26.png";
                        break;
                        case "magasin27":
                        photoMagasin.src = "./TIERS_png/agadir.png";
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
                const printWindow = window.open('', '', 'width=200,height=300');
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
