<!DOCTYPE html>
<html lang="en">
<head>
    <title>Offres</title>
    <script type="text/javascript" src="./resources/jquery.js"></script>
    <script type="text/javascript" src="./resources/mustache.js"></script>
    <link rel="stylesheet" type="text/css" href="resources/styles/offers.css">
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
include('navbar-view.php');
?>
<div class="content">
    <div class="title-filter">
        <h1>Stages</h1>
        <!-- <input type="checkbox" class="testbox"></input> -->
        <div class="filter">
            <button class="outlined btn-filter-popup" onclick="openPopUp('offers-popup-filter')">
                <span class="text"> Filtrer / Trier </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
        </div>
    </div>
    <div class="popup" id="offers-popup-filter">
        <!-- Filter Pop up form -->
        <form>
            <div class="close">
                <span class="material-symbols-rounded popup-close"> close </span>
            </div>
            <label>
                <select id="skills">
                    <option class="placeholder" value="0">Compétences</option>
                    <?= $skills_options ?>
                </select>
            </label>
            <div id="selected_skills" class="selected-filters"></div>
            <label>
                <select id="promotions">
                    <option class="placeholder" value="0">Promotions</option>
                    <?= $promotions_options ?>
                </select>
            </label>
            <div id="selected_promotions" class="selected-filters"></div>
            <label>
                Trier par : <select id="sort">
                    <option>ne pas trier</option>
                    <option value="placesasc">nombre de places offertes croissant</option>
                    <option value="placesdesc">nombre de places offertes décroissant</option>
                    <option value="durationasc">durée croissante</option>
                    <option value="durationdesc">durée décroissante</option>
                    <option value="salaryasc">base de rémunération croissante</option>
                    <option value="salarydesc">base de rémunération décroissante</option>
                    <option value="dateasc">date plus proches</option>
                    <option value="datedesc">date plus éloginées</option>
                </select>
            </label>
            <button type="button" onclick="loadFiltered()">
                <span class="text"> Enregistrer </span>
                <span class="material-symbols-rounded"> chevron_right </span>
            </button>
        </form>
    </div>
    <div class="offers-layout-cards content-layout">
        {{#offers}}
        <form method="get" class="card">

            <input type="hidden" name="id_offer" value="{{id_offer}}">
            <img src='{{link}}' alt='logo test' class='logosmall'>
            <div class="offer-info">
                <div class="card-header">
                <h3>{{description}}</h3>
                <?php
                echo ($favorites)?
                    '<input type=checkbox class="favorite" id="fav-{{id_offer}}" {{favorite}} onclick="setFavorite({{id_offer}},this)">
                    <label for="fav-{{id_offer}}" class="material-symbols-rounded">
                    <span class="material-symbols-rounded">Bookmark</span>
                    </label>
                    ' : '';
                ?>
                </div>
                <div>
                    <div class="header-info">
                        <span class="material-symbols-rounded">Work</span>
                        <p>{{name}}</p>
                    </div>
                    <div class="header-info">
                        <span class="material-symbols-rounded">Home_Pin</span>
                        <p>{{city}}</p>
                    </div>
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
                    <button class="outlined" type="submit">
                        <span class="text"> Postuler </span>
                        <span class="material-symbols-rounded"> open_in_new </span>
                    </button>
                </div>
            </div>
        </form>
        {{/offers}}
    </div>
</div>

<script>
    const base_html = $(".offers-layout-cards").html();
    $(".offers-layout-cards").hide();
    $.ajax({
        type: "POST",
        url: "./model/offers_ajax.php",
        dataType: "json",
        data: {
            action: "showAll"
        },
        success: function (response) {
            formatDisplay(response);
        }
    });
    $(".placeholder").hide();
    $("#skills").change(function() {
        if(!$("#selected_skills span[value="+$("#skills option:selected").val()+"]").length) {
            $("<span/>", {
                value: $("#skills option:selected").val(),
                html: $("#skills option:selected").text()+" <span class=\"material-symbols-rounded popup-close\"> close </span>",
                click: function(){
                    $(this).remove();
                }
            }).appendTo("#selected_skills");
        }
        $("#skills").val(0);
    });
    $("#promotions").change(function() {
        if(!$("#selected_promotions span[value="+$("#promotions option:selected").val()+"]").length) {
            $("<span/>", {
                value: $("#promotions option:selected").val(),
                html: $("#promotions option:selected").text()+" <span class=\"material-symbols-rounded popup-close\"> close </span>",
                click: function(){
                    $(this).remove();
                }
            }).appendTo("#selected_promotions");
        }
        $("#promotions").val(0);
    });
    function setFavorite(id_offer, checkbox) {
        console.log(checkbox.checked);    
        $.ajax({
            type: "POST",
            url: "./model/offers_ajax.php",
            dataType: "json",
            data: {
                action: "setFavorite",
                id_offer: id_offer,
                create : checkbox.checked
            },
            success: function (response) {
            }
        });
    }
    
    function loadFiltered() {
        var skills = [];
        $('#selected_skills span').each(function() {
            skills.push($(this).attr("value"));
        });
        var promotions = [];
        $('#selected_promotions span').each(function() {
            promotions.push($(this).attr("value"));
        });
        $(".offers-layout-cards").hide();
        $.ajax({
            type: "POST",
            url: "./model/offers_ajax.php",
            dataType: "json",
            data: {
                action: "showFiltered",
                skills: skills,
                promotions: promotions,
                sort: $("#sort option:selected").attr("value")
            },
            success: function (response) {
                formatDisplay(response);
            }
        });
    }
    function formatDisplay(response) {
        format = [["groups", "concerns", 20], ["abilities", "required", 10]];
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
        $(".offers-layout-cards").html(Mustache.render(base_html, {"offers": response.message}));
        $(".offers-layout-cards").show();
    }
</script>

<?php
include('footer-view.html');
?>
</body>
</html>