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
            <select id="skills">
                <?= $options ?>
            </select>
            <div id="selected_skills"></div>
            <button type="button" onclick="load()">
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
    $(".offers-layout-cards").hide();
    $.ajax({
        type: "POST",
        url: "./model/offers_ajax.php",
        dataType: "json",
        data: {
            action: "showAll"
        },
        success: function (response) {
            console.log(response.message);
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
            $(".offers-layout-cards").html(Mustache.render($(".offers-layout-cards").html(), {"offers": response.message}));
            $(".offers-layout-cards").show();
        }
    });
    $("#skills option").click(function() {
        if(!$("span[value="+$("#skills option:selected").val()+"]").length) {
            $("<span/>", {
                value: $("#skills option:selected").val(),
                html: $("#skills option:selected").text(),
                click: function(){
                    $(this).remove();
                }
            }).appendTo("#selected_skills");
        }
    });
    function load() {
        var test = [];
        $('#selected_skills span').each(function() {
            test.push($(this).attr("value"));
        });
        console.log(test);
        $(".offers-layout-cards").hide();
        $.ajax({
            type: "POST",
            url: "./model/offers_ajax.php",
            dataType: "json",
            data: {
                action: "showFiltered",
                skills: test
            },
            success: function (response) {
                console.log(response.message);
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
                $(".offers-layout-cards").html(Mustache.render($(".offers-layout-cards").html(), {"offers": response.message}));
                $(".offers-layout-cards").show();
            }
        });
    }
</script>

<?php
include('footer.html');
?>
</body>
</html>