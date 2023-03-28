<!DOCTYPE html>
<html lang="en">
<head>
    <title>Entreprise</title>
    <script type="text/javascript" src="./resources/jquery.js"></script>
    <script type="text/javascript" src="./resources/mustache.js"></script>
    <link rel="stylesheet" type="text/css" href="resources/styles/company.css">
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
include('navbar-view.php');
?>

<div class="postulatebody content">
<?= $content ?>
</div>


<?php
include('footer-view.html');
?>
<script>
    const regex = new RegExp("(?=^.{0,5}$)[0-9]{5}");
	$("#postcode").on("input", async() => {
		$("#cities").empty();
		if(regex.test($("#postcode").val())) {
			$.ajax({
				type:"POST",
				url:"http://localhost/data/Projet/model/city_ajax.php",
				data: {
					postcode: $("#postcode").val()
				},
				dataType: "json",
				success: function(response) {
                    let opt = document.createElement("option");
					opt.classList.add("placeholder");
					opt.text = "Villes";
                    document.getElementById("cities").add(opt, null);
					for (let i = 0; i < response.message.length; i++) {
						let opt = document.createElement("option");
						opt.value = response.message[i].id_city;
						opt.text = response.message[i].name;
						document.getElementById("cities").add(opt, null);
					}
                    $(".placeholder").hide();
				}
			});
		}
	});
    $("#cities").change(function() {
        if(!$("#selected_cities span[value="+$("#cities option:selected").val()+"]").length) {
            $("<span/>", {
                value: $("#cities option:selected").val(),
                html: $("#cities option:selected").text()+", "+$("#postcode").val()+" <input type=\"hidden\" name=\"cities[]\" value=\""+$("#cities option:selected").val()+"\"><span class=\"material-symbols-rounded popup-close\"> close </span>",
                click: function(){
                    $(this).remove();
                }
            }).appendTo("#selected_cities");
        }
        $("#skills").val(0);
    });
    $("#selected_cities > span").click(function() {
        $(this).remove();
    });
</script>
</body>
</html>