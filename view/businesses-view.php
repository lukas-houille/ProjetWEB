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
require_once('navbar-view.php');
?>

<div class="content">

    <div class="title-filter">
        <h1>Entreprises</h1>
        <div class="filter">
            <button class="outlined btn-filter-popup" onclick="openPopUp('company-popup-filter')">
                <span class="text"> Filtrer / Trier </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
        </div>
    </div>

    <div class="popup" id="company-popup-filter">
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
        {{#companies}}
        <form method="get" action="/postulate.php" class="card">
            <input type="hidden" name="id_business" value="{{company_id}}">
            <img src='resources/images/Logo small.svg' alt='logo test' class='logosmall'>
            <div class="offer-info">
                <h3>{{company_name}}</h3>
                <div>
                    <div class="header-info">
                        <span class="material-symbols-rounded">Home_Pin</span>
                        <p>{{cities}}</p>
                    </div>
                    <div class="header-info">
                        <span>Confiance des tuteurs:</span>
                        <p>{{grade}}</p>
                    </div>
                </div>
                <div class="description">
                    <div class="vertical-align">
                        <p>Secteur d'activité:<span> {{field_name}}</span></p>
                        <p>Étudiants CESI déjà pris:<span> {{cesi_interns}}</span></p>
                    </div>
                </div>
                <div class="submit">
                    <button class="outlined" type="submit">
                        <span class="text"> Voir plus </span>
                        <span class="material-symbols-rounded"> open_in_new </span>
                    </button>
                </div>
            </div>
        </form>
        {{/companies}}

    </div>

</div>

<script>
    const base_html = $(".businesses-layout-cards").html();
    $(".businesses-layout-cards").hide();
    $.ajax({
        type: "POST",
        url: "./model/companies_ajax.php",
        dataType: "json",
        data: {
            action: "showAll"
        },
        success: function (response) {
            console.log(response);
            formatDisplay(response);
        }
    });
    function formatDisplay(response) {
        format = [["localisations", "cities", 50]];
        for (let f = 0; f < format.length; f++) {
            for (let y = 0; y < response.message.length; y++) {
                response.message[y][format[f][1]] = "";
                for (let i = 0; i < response.message[y][format[f][0]].length; i++) {
                    if ((response.message[y][format[f][1]] + response.message[y][format[f][0]][i].name).length >= format[f][2]) {
                        response.message[y][format[f][1]] += "...";
                        break
                    } else {
                        if (i != 0 && i != response.message[y][format[f][0]].length - 1 || i == 1) {
                            response.message[y][format[f][1]] += ",";
                        }
                        response.message[y][format[f][1]] += response.message[y][format[f][0]][i].name;
                    }
                }
            }
        }
        $(".businesses-layout-cards").html(Mustache.render(base_html, {"companies": response.message}));
        $(".businesses-layout-cards").show();
    }
</script>

<?php
require_once('footer-view.html');
?>
</body>
</html>
