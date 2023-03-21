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

<div class="content">
    //deconnexion
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
