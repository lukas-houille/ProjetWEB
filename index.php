<!DOCTYPE html>
<html lang="en">
<head>
    <title>Accueil</title>
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
include('navbar.php');
?>

<div id="landing-page">
    <div id="welcome-text">
        <h2>Vous cherchez un stage ? </h2>
        <h2>Pen'CESI (pensez y)</h2>
        <button type="button" onclick="window.location.href='offers.php'">Voir les stages !</button>
    </div>
    <div id="welcome-image">
        <img src="resources/images/corpo.svg" alt="Logo">
    </div>
</div>

<?php
include('footer.html');
?>
</body>
</html>