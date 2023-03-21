<!DOCTYPE html>
<html lang="en">
<head>
    <title>Member</title>
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
require_once('view/navbar.php');
?>

<div class="content">
    //deconnexion
    <button>
        <span class="material-symbols-rounded"> logout </span>
        <span class="text"> Se déconnecter </span>
    </button>

</div>

<?php
require_once('view/footer.html');
?>
</body>
</html>
