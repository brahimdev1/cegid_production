<php include'connexion.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <table class="table table-bordered text-center">
        <tr>
            <th colspan="6"></th>
            <th colspan="2">Garnissage</th>
            <th colspan="2">Habillage</th>
            <th colspan="2">Bordeuse</th>
            <th colspan="2">Emballage</th>
        </tr>
        <tr>
            <th>Client</th>
            <th>Num cmd</th>
            <th>OF</th>
            <th>REF</th>
            <th>LIBELLE</th>
            <th>QTE</th>

            <th>Lancement</th>
            <th>réception</th>
            <th>Lancement</th>
            <th>réception</th>
            <th>Lancement</th>
            <th>réception</th>
            <th>Lancement</th>
            <th>réception</th>
        </tr>
        <tr>
            <?php
    try {
        $conn = new PDO("sqlsrv:Server=192.168.1.177;Database=MEDIDISCEGIDREELLE", "SA", "cegid.2008");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT DISTINCT GPA_LIBELLE
                  FROM ligne
                  INNER JOIN PIECEADRESSE ON LIGNE.GL_TIERS = SUBSTRING(PIECEADRESSE.GPA_AUXICONTACT, 5, LEN(PIECEADRESSE.GPA_AUXICONTACT))
                  WHERE GL_ARTICLE IS NOT NULL AND GL_ARTICLE <> '' AND GL_NATUREPIECEG = 'CC'  
                  AND GL_DATEPIECE >= CONVERT(datetime, '2023-05-16 00:00:00.000', 121) AND GL_LIBREART8 = 'ATL' AND GL_ETATSOLDE = 'ENC'
                  ORDER BY GPA_LIBELLE ASC";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($clients as $client) {
            echo '<tr>';
            echo '<td>' . $client['GPA_LIBELLE'] . '</td>';
            echo '</tr>';
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
    ?>
       
        
        </tr>

    </table>



</body>

</html>