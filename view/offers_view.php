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
    <form method="get" class="card">
    <img src='resources/images/Logo small.svg' alt='logo test' class='logosmall'>
    <div class="offer-info">
        <h3>{{description}}</h3>
        <p>{{name}}</p>
        <p>{{city}}</p>
        <div class="description">
            <div class="vertical-align">
                <p>Profile:<span> {{required}}</span></p>
                <p>Durée:<span> {{duration}} mois</span></p>
            </div>
            <div class="vertical-align">
                <!--<p>Publication:<span> dd/mm/aaaa</span></p>--><!-- Not in DB for now -->
                <p>Debut:<span> {{date}}</span></p>
            </div>
        </div>
        <div class="submit" value="AHAHA">
            <button>Postuler</button>
        </div>
    </div>
    </form>
    {{/offers}}
</div> 
<script>
	$.ajax({
		type:"POST",
		url:"http://cesitonstage.com/model/offers_ajax.php",
		dataType: "json",
		success: function(response) {
            for(let y = 0; y < response.message.length; y++) {
                response.message[y].required = "";
                for(let i = 0; i < response.message[y].abilities.length; i++) {
                    if((response.message[y].required+response.message[y].abilities[i].name).length>=20) {
                        response.message[y].required+="...";
                        break
                    }
                    else {
                        if(i != 0 && i != response.message[y].abilities.length-1 || i==1) {
                            response.message[y].required +=",";
                        }
                        response.message[y].required+=response.message[y].abilities[i].name;
                    }
                }
            }
            console.log(response.message);
            $(".offers").html(Mustache.render($(".offers").html(), {"offers" : response.message}));
		}
	});
</script>
<?php
include('footer.html');
?>
</body>
</html>
