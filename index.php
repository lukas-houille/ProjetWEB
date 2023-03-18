<!DOCTYPE html>
<html lang="en">
<head>
    <title>Accueil</title>
    <?php
    include('view/header.php');
    ?>
</head>
<body>
<?php
require_once('view/navbar.php');
?>
<div class="content">
    <div id="landing-page">
        <div id="welcome-text">
            <h2>Vous cherchez un stage ? </h2>
            <h2>Pen'CESI (pensez y)</h2>
            <button type="button" onclick="window.location.href='offers.php'">
                <span class="text"> Voir les stages !</span>
            </button>
        </div>
        <div id="welcome-image">
            <img src="resources/images/corpo.svg" alt="Logo">
        </div>
    </div>
</div>



<?php
require_once('view/footer.html');
?>
</body>
</html>