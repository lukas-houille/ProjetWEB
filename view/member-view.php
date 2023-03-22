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
require_once('view/navbar-view.php');
?>

<div class="content">
    <div class="member-name-info">
        <h1> Bonjour <?php echo $name->first_name; ?> </h1>
        <div id="info">
            <h3>Infos</h3>
            <!-- Mettre les infos de l'utilisateur avec mustache -->
        </div>
    </div>
    <?php
    if($type[0] == "Admin" || $type[0] == "Tutor") {
        echo '<button type="button" onclick="window.location.href=\'dashboard.php\'">
                <span class="text"> Dashboard </span>
                <span class="material-symbols-rounded"> Team_Dashboard </span>
            </button>';
    }
    ?>

    <button type="button" onclick="window.location.href='index.php?disconnect'">
        <span class="material-symbols-rounded"> logout </span>
        <span class="text" id="disconnect"> Deconnexion </span>
    </button>

</div>

<?php
require_once('view/footer-view.html');
?>
</body>
</html>
