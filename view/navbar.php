<?php
require_once("./model/session_model.php");
?>
<nav id="nav-bar">
    <a href="index.php" id="logo">
        <img src="resources/images/LogoBig.svg" alt="Logo">
    </a>
    <div id="nav-links">
        <a href="offers.php">
            <p>Stages</p>
        </a>
        <a href="business.php">
            <p>Entreprises</p>
        </a>
        <a href="member.php">
            <?php
            if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
                $name = $_SESSION["login"]->name($base);
                echo "<p>". $name->first_name." ".$name->last_name ."</p>";
            }
            else {
                echo "Se connecter";
            }
            ?>
        </a>
    </div>
</nav>