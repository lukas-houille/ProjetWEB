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
                <span class="text"> Enregistrer </span>
                <span class="material-symbols-rounded"> chevron_right </span>
            </button>
        </form>
    </div>

    <div class="businesses-layout-cards">


        <!-- Antonin mets ton code d'une carte ici -->
        {{#offers}}
        <form method="get" action="#id_offer={{id_offer}}" class="card">
            <img src='resources/images/Logo small.svg' alt='logo test' class='logosmall'>
            <div class="offer-info">
                <h3>{{description}}</h3>
                <div>
                    <div class="header-info">
                        <span class="material-symbols-rounded">Work</span>
                        <p>{{name}}</p>
                    </div>
                </div>
                <div class="header-info">
                    <span class="material-symbols-rounded">Home_Pin</span>
                    <p>{{city}}</p>
                </div>
                <div class="description">
                    <div class="vertical-align">
                        <p>Profile:<span> {{concerns}}</span></p>
                        <p>Durée:<span> {{duration}} mois</span></p>
                    </div>
                    <div class="vertical-align">
                        <p>Savoir:<span> {{required}}</span></p>
                        <p>Debut:<span> {{date}}</span></p>
                    </div>
                </div>
                <div class="submit">
                    <button class="outlined">
                        <span class="text"> Postuler </span>
                        <span class="material-symbols-rounded"> open_in_new </span>
                    </button>
                </div>
            </div>
        </form>
        {{/offers}}

    </div>

</div>

<?php
require_once('footer.html');
?>
</body>
</html>
