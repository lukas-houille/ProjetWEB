<!DOCTYPE html>
<html lang="en">
<head>
    <title>Entreprise</title>
    <script type="text/javascript" src="./resources/jquery.js"></script>
    <script type="text/javascript" src="./resources/mustache.js"></script>
    <script type="text/javascript" src="./view/single-view.js"></script>
    <link rel="stylesheet" type="text/css" href="resources/styles/company.css">
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
include('navbar-view.php');
?>

<div class="postulatebody content">
<?= $content ?>
</div>


<?php
include('footer-view.html');
?>
</body>
</html>