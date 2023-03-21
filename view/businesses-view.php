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
        {{#offers}}
        <form method="get" action="/postulate.php" class="card">
            <input type="hidden" name="id_business" value="{{id_business}}">
            <img src='resources/images/Logo small.svg' alt='logo test' class='logosmall'>
            <div class="offer-info">
                <h3>{{Business_name}}</h3>
                <div>
                    <div class="header-info">
                        <span class="material-symbols-rounded">Home_Pin</span>
                        <p>{{city}}</p>
                    </div>
                    <div class="header-info">
                        <span class="material-symbols-rounded">Home_Pin</span>
                        <p>{{note}}</p>
                    </div>
                </div>
                <div class="description">
                    <div class="vertical-align">
                        <p>Secteur d'activité:<span> {{field}}</span></p>
                        <p>Autres locatlités:<span> {{date}}</span></p>
                    </div>
                </div>
                <div class="submit">
                    <button class="outlined" type="submit">
                        <span class="text"> Postuler </span>
                        <span class="material-symbols-rounded"> open_in_new </span>
                    </button>
                </div>
            </div>
        </form>
        {{/offers}}
        {{/offers}}

    </div>

</div>

<?php
require_once('footer.html');
?>
</body>
</html>
