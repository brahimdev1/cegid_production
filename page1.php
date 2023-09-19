<!-- page1.php -->
<?php
session_start(); // Start the session

include 'connexion.php';
if (isset($_POST['suivreButton'])) {

if (isset($_POST['page1_value'])) {
    $_SESSION['page1_value'] = $_POST['page1_value'];
    // Redirect to page2.php after form submission to avoid resubmission on page refresh
    header("Location: page2.php");
    exit();
}}

// Requête SQL

$query = "SELECT DISTINCT GPA_LIBELLE
          FROM ligne
          INNER JOIN PIECEADRESSE ON LIGNE.GL_TIERS = SUBSTRING(PIECEADRESSE.GPA_AUXICONTACT, 5, LEN(PIECEADRESSE.GPA_AUXICONTACT))
          WHERE GL_ARTICLE IS NOT NULL AND GL_ARTICLE <> '' AND GL_NATUREPIECEG = 'CC'  
          AND GL_DATEPIECE >= CONVERT(datetime, '2023-05-16 00:00:00.000', 121) AND GL_LIBREART8 = 'ATL' AND GL_ETATSOLDE = 'ENC'
          ORDER BY GPA_LIBELLE ASC";

// Execute the query
$result = sqlsrv_query($conn, $query);

if (!$result) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... (head content) ... -->
</head>
<body>
    <?php include 'menu.php'; ?>

    <!-- Contenu de la page 1 -->
    <h1>Hello, ceci est la page 1</h1>
    <form method="post" action="page1.php">
        <!-- Change the action to "page1.php" so the form submits to this page -->
        <label for="GPA_LIBELLE">CLIENT :</label>
        <select name="page1_value" id="GPA_LIBELLE">
            <!-- Add an empty option to allow no selection (optional) -->
            <option value="">Select a client</option>
            <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) { ?>
                <option value="<?php echo $row['GPA_LIBELLE']; ?>"><?php echo $row['GPA_LIBELLE']; ?></option>
            <?php } ?>
        </select>
        <input type="submit" name="submit" value="Submit">
        <input type="submit" name="suivreButton" value="suivreButton">

    </form>
    <!-- ... (script includes) ... -->
</body>
</html>
