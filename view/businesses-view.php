<!DOCTYPE html>
<html lang="en">
<head>
    <title>Offers</title>
    <script type="text/javascript" src="./resources/jquery.js"></script>
    <script type="text/javascript" src="./resources/mustache.js"></script>
    <?php
    require_once('header.php');
    ?>
</head>
<body>
<?php
require_once('navbar.php');
?>

<div class="content">

    <div class="title-filter">
        <h1>Entreprises</h1>
        <div class="filter">
            <button class="outlined btn-filter-popup">
                <span class="text"> Filtrer / Trier </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
        </div>
    </div>

    <div class="pop-up">
        <!-- Filter Pop up form -->
        <form action="offers.php" method="get">
            <div class="close">
                <span class="material-symbols-rounded popup-close"> close </span>
            </div>
            <button>
                <span class="text"> Afficher les offres </span>
                <span class="material-symbols-rounded"> chevron_right </span>
            </button>
        </form>
    </div>

</div>

<?php
require_once('footer.html');
?>
</body>
</html>
