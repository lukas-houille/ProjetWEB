<!DOCTYPE html>
<html lang="en">
<head>
    <title>Offers</title>
    <script type="text/javascript" src="./resources/jquery.js"></script>
    <script type="text/javascript" src="./resources/mustache.js"></script>
    <link rel="stylesheet" href="style/postulate.scss">
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
include('navbar-view.php');
?>

<div class="postulatebody"
<h1>{{jobTitle}}</h1>
<div class="postulatedetailsbody">
    <h3>{{companyName}}</h3>
    <p>{{location}}</p>
    <p>Date de début:<span> {{startingDate}}</span></p>
    <p>Date de fin:<span> {{endingDate}}</span></p>
    <p>Profile recherché:<span> {{profile}}</span></p>
    <p>Gratification:<span> {{money}}</span></p>
    <p>Horaires:<span>{{workhours}}</span></p>
</div>
<div class="details">
    <h1>Détail de l'offre</h1>
    <p><span>{{description}}</span></p>
</div>
<div class="skills">
    <h1>Compétences attendues</h1>
    <p><span>{{skill}}</span></p>
</div>
<button type="button" onclick="mailto:...">
    <span class="text"> Postulez !</span>
</button>
</div>


<?php
include('footer-view.html');
?>
</body>
</html>