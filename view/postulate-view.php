<!DOCTYPE html>
<html lang="en">
<head>
    <title>Postuler</title>
    <script type="text/javascript" src="./resources/jquery.js"></script>
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
<?= $panel ?>
</div>

<script>
    $("#send").hide();
    $("#confirm").click(function() {
        if(this.checked) {
            $("#send").show();
        }
        else {
            $("#send").hide();
        }
    });
</script>

<?php
include('footer-view.html');
?>
</body>
</html>