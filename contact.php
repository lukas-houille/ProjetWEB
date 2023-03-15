<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact</title>
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
include('navbar.php');
?>

<div id="contact">
    <form method="post">
        <div class="horizontal-align">
            <label for="nom">
                <span>Nom</span>
                <input type="text" id="nom">
            </label>

            <label for="prenom">
                <span>Prénom</span>
                <input type="text" id="prenom">
            </label>
        </div>
        <div class="horizontal-align">
            <label for="email">
                <span>E-mail</span>
                <input type="email" id="email">
            </label>
            <label for="select">
                <span>Service concerné</span>
                <select id="select">
                    <option value="service1">Service 1</option>
                    <option value="service2">Service 2</option>
                    <option value="service3">Service 3</option>
                </select>
            </label>
        </div>
        <label for="objet" id="objet">
            <span>Objet de la demande</span>
            <input type="text" id="objet_demande">
        </label>
        <label for="message" id="message">
            <span>Message</span>
            <textarea id="message"></textarea>
        </label>

        <button type="submit"> <span class="text">Envoyer</span> <span class="material-symbols-rounded"> chevron_right </span></button>
    </form>
</div>


<?php
include('footer.html');
?>
</body>
</html>