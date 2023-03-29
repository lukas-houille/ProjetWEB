<!DOCTYPE html>
<html lang="en">
<head>
    <title>Businesses</title>
    <script type="text/javascript" src="./resources/jquery.js"></script>
    <script type="text/javascript" src="./resources/mustache.js"></script>
    <link rel="stylesheet" href="resources/styles/company.css">
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

    <div id="company-popup-filter">
        <!-- Filter Pop up form -->
        <form action="offers.php" method="get">
            <div class="close">
                <span class="material-symbols-rounded popup-close"> close </span>
            </div>
            <label>
                <select id="fields">
                    <option class="placeholder" value="0">Secteur d'activité</option>
                    <?= $fields_options ?>
                </select>
            </label>
            <div id="selected_fields" class="selected-filters"></div>
            <button type="button" onclick="loadFiltered()">
                <span class="text"> Enregistrer </span>
                <span class="material-symbols-rounded"> chevron_right </span>
            </button>
        </form>
    </div>

    <div class="businesses-layout-cards content-layout">
        {{#companies}}
        <form method="get" class="card">
            <input type="hidden" name="id_business" value="{{id_company}}">
            <img src='{{link}}' alt='logo test' class='logosmall'>
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
    $("#fields").change(function() {
        if(!$("#selected_fields span[value="+$("#fields option:selected").val()+"]").length) {
            $("<span/>", {
                value: $("#fields option:selected").val(),
                html: $("#fields option:selected").text()+" <span class=\"material-symbols-rounded popup-close\"> close </span>",
                click: function(){
                    $(this).remove();
                }
            }).appendTo("#selected_fields");
        }
        $("#fields").val(0);
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
    function loadFiltered() {
        var fields = [];
        $('#selected_fields span').each(function() {
            fields.push($(this).attr("value"));
        });
        $(".offers-layout-cards").hide();
        $.ajax({
            type: "POST",
            url: "./model/companies_ajax.php",
            dataType: "json",
            data: {
                action: "showFiltered",
                fields: fields
            },
            success: function (response) {
                formatDisplay(response);
            }
        });
    }
</script>

<?php
require_once('footer-view.html');
?>
</body>
</html>
