<!DOCTYPE html>
<html lang="en">
<head>
    <title>Company</title>
    <script type="text/javascript" src="./resources/jquery.js"></script>
    <script type="text/javascript" src="./resources/mustache.js"></script>
    <link rel="stylesheet" type="text/css" href="resources/styles/showcompany.css">
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
include('navbar.php');
?>
<div class="showcompanybody">

    <div class="showdetailsbody">
        <h1>{{company_name}}</h1>
        <p>{{cities}}</p>
        <p>Secteur d'activité:<span> {{field_name}}</span></p>
        <p>Étudiants CESI déjà pris:<span> {{cesi_interns}}</span></p>
        <div class="details">
            <h1>Description de l'entreprise:</h1>
            <p><span>{{description}}</span></p>
        </div>
        <div class="header-info">
            <h1>Confiance des tuteurs:</h1>
            <p><span>{{grade}}</span></p>
        </div>
        <div class="submit">
            <button type="submit" formaction="https://cesitonstage.com/business.php">Retrouver la page entreprise</button>
        </div>
    </div>
</div>

<?php
include('footer.html');
?>
</body>
</html>