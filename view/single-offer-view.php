<!DOCTYPE html>
<html lang="en">
<head>
    <title>Company</title>
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

<div class="postulatebody content">
<?= $content ?>
</div>


<?php
include('footer-view.html');
?>
<script>
    $(".applystate[value!=1] button").hide();
    const regex = new RegExp("(?=^.{0,5}$)[0-9]{5}");
	$("#postcode").on("input", async() => {
		$("#cities").empty();
		if(regex.test($("#postcode").val())) {
			$.ajax({
				type:"POST",
				url:"https://cesitonstage.com/model/city_ajax.php",
				data: {
					postcode: $("#postcode").val()
				},
				dataType: "json",
				success: function(response) {
					for (let i = 0; i < response.message.length; i++) {
						let opt = document.createElement("option");
						opt.value = response.message[i].id_city;
						opt.text = response.message[i].name;
						document.getElementById("cities").add(opt, null);
					}
				}
			});
		}
	});
    $(".placeholder").hide();
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
    $("#selected_skills > span, #selected_promotions > span").click(function() {
        $(this).remove();
    });
</script>
</body>
</html>