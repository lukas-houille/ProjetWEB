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
require_once('navbar-view.php');
?>

<div class="content">
    <div id="contact">
        <form method="post">
            <div class="horizontal-align">
                <label for="nom">
                    <span>Nom</span>
                    <input type="text" id="nom"  required pattern="[a-zA-Z]+" minlength="2" maxlength="21">
                </label>

                <label for="prenom">
                    <span>Prénom</span>
                    <input type="text" id="prenom"  required pattern="[a-zA-Z]+" minlength="2" maxlength="20">
                </label>
            </div>
            <div class="horizontal-align">
                <label for="email">
                    <span>E-mail</span>
                    <input type="email" id="email"required>
                </label>
                <label for="select">
                    <span>Service concerné</span>
                    <select id="select" required>
                        <option value="service1">Service 1</option>
                        <option value="service2">Service 2</option>
                        <option value="service3">Service 3</option>
                    </select>
                </label>
            </div>
            <label for="objet" id="objet">
                <span>Objet de la demande</span>
                <input type="text" id="objet_demande"  required pattern="[a-zA-Z]+" minlength="2" maxlength="200">
            </label>
            <label for="message" id="message">
                <span>Message</span>
                <textarea id="message"  required pattern="[a-zA-Z]+" minlength="2" maxlength="800"></textarea>
            </label>
            <button type="submit">
                <span class="text">Envoyer</span>
                <span class="material-symbols-rounded">chevron_right</span>
            </button>
        </form>
    </div>
</div>

<?php
require_once('footer-view.html');
?>
</body>
</html>