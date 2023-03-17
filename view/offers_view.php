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
<div class="offers">
    {{#offers}}
    <form method="get" action="#id_offer={{id_offer}}" class="card">
    <img src='resources/images/Logo small.svg' alt='logo test' class='logosmall'>
    <div class="offer-info">
        <h3>{{description}}</h3>
        <p>{{name}}</p>
        <p>{{city}}</p>
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
            <button>Postuler</button>
        </div>
    </div>
    </form>
    {{/offers}}
</div> 
<script>
    $(".offers").hide();
	$.ajax({
		type:"POST",
		url:"./model/offers_ajax.php",
		dataType: "json",
		success: function(response) {
            console.log(response.message);
            format = [["groups","concerns",20],["abilities","required",10]];
            for(let f = 0; f < format.length; f++) {
                for(let y = 0; y < response.message.length; y++) {
                    response.message[y][format[f][1]] = "";
                    for(let i = 0; i < response.message[y][format[f][0]].length; i++) {
                        if((response.message[y][format[f][1]]+response.message[y][format[f][0]][i].name).length>=format[f][2]) {
                            response.message[y][format[f][1]]+="...";
                            break
                        }
                        else {
                            if(i != 0 && i != response.message[y][format[f][0]].length-1 || i==1) {
                                response.message[y][format[f][1]] +=",";
                            }
                            response.message[y][format[f][1]]+=response.message[y][format[f][0]][i].name;
                        }
                    }
                }
            }
            $(".offers").html(Mustache.render($(".offers").html(), {"offers" : response.message}));
            $(".offers").show();
		}
	});
</script>
<?php
include('footer.html');
?>
</body>
</html>