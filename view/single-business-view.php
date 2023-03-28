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
				url:"http://localhost/data/ProjetWEB-main/model/city_ajax.php",
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
    $("#skills").change(function() {
        if(!$("#selected_skills span[value="+$("#skills option:selected").val()+"]").length) {
            $("<span/>", {
                value: $("#skills option:selected").val(),
                html: $("#skills option:selected").text()+" <input type=\"hidden\" name=\"skills[]\" value=\""+$("#skills option:selected").val()+"\"><span class=\"material-symbols-rounded popup-close\"> close </span>",
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
                html: $("#promotions option:selected").text()+" <input type=\"hidden\" name=\"promotions[]\" value=\""+$("#promotions option:selected").val()+"\"><span class=\"material-symbols-rounded popup-close\"> close </span>",
                click: function(){
                    $(this).remove();
                }
            }).appendTo("#selected_promotions");
        }
        $("#promotions").val(0);
    });
    $("#selected_skills > span, #selected_promotions > span, #selected_cities > span").click(function() {
        $(this).remove();
    });
    $(".placeholder").hide();
</script>
</body>
</html>