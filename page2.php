<!-- page2.php -->
<?php
session_start(); // Start the session

if (isset($_SESSION['page1_value'])) {
    $page1_value = $_SESSION['page1_value'];
} else {
    // Handle the case when the value is not set (optional)
    $page1_value = "No value set from page1";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... (head content) ... -->
</head>
<body>
    <?php include 'menu.php'; ?>

    <!-- Contenu de la page 2 -->
    <h1>Hello, ceci est la page 2</h1>
    <p>Value from page1: <?php echo $page1_value; ?></p>

    <!-- ... (script includes) ... -->
</body>
</html>
