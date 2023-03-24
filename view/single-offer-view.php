<!DOCTYPE html>
<html lang="en">
<head>
    <title>Offers</title>
    <script type="text/javascript" src="./resources/jquery.js"></script>
    <script type="text/javascript" src="./resources/mustache.js"></script>
    <link rel="stylesheet" href="style/postulate.scss">
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
include('navbar-view.php');
?>

<div class="postulatebody">
<?= $content ?>
</div>


<?php
include('footer-view.html');
?>
</body>
</html>