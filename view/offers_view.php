<!DOCTYPE html>
<html lang="en">
<head>
    <title>Offers</title>
    <script type="text/javascript" src="./resources/jquery.js"></script>
    <script type="text/javascript" src="./resources/mustache.js"></script>
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
include('navbar.php');
?>
<div class="content">
    <div class="title-filter">
        <h1>Stages</h1>
        <div class="filter">
            <button class="outlined btn-filter-popup">
                <span class="text"> Filtrer / Trier </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
        </div>
    </div>
    <div class="pop-up" id="offers-popup-filter">
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
            <div id="selected_skills"></div>
            <label>
                <select id="promotions">
                    <option class="placeholder" value="0">Promotions</option>
                    <?= $promotions_options ?>
                </select>
            </label>
            <div id="selected_promotions"></div>
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
    <div class="offers-layout-cards">
        {{#offers}}
        <form method="get" action="/postulate.php" class="card">
            <input type="hidden" name="id_offer" value="{{id_offer}}">
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
                html: $("#skills option:selected").text()+" ",
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
                html: $("#promotions option:selected").text()+" ",
                click: function(){
                    $(this).remove();
                }
            }).appendTo("#selected_promotions");
        }
        $("#promotions").val(0);
    });
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
include('footer.html');
?>
</body>
</html>