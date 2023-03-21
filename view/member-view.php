<!DOCTYPE html>
<html lang="en">
<head>
    <title>Member</title>
    <script type="text/javascript" src="./resources/jquery.js"></script>
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
require_once('view/navbar.php');
?>

<p>Pour rappel, vous êtes un <?= $type ?></p>
<div class="content">
    <div class="member-name-info">
        <h1> Bonjour
        <div id="info">
            <h3>Infos</h3>
            <!-- Mettre les infos de l'utilisateur avec mustache -->
        </div>
    </div>
    <?php
    if(($type == "pilote")||($type == "admin")) {
        echo '<button type="button" onclick="window.location.href=\'dashboard.php\'">
                <span class="material-symbols-rounded"> Team_Dashboard </span>
                <span class="text"> Dashboard </span>
            </button>';
    }
    ?>

    <button>
        <span class="material-symbols-rounded"> logout </span>
        <span class="text"> Se déconnecter </span>
    </button>

</div>

<script>
    $(".content button").click(function() {
        window.location.replace("?disconnect");
    });
</script>

<?php
require_once('view/footer.html');
?>
</body>
</html>
