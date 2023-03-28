<?php
require "./model/login_model.php";
$warning = "";
if((isset($_POST["username"]) && !empty($_POST["username"])) && (isset($_POST["password"]) && !empty($_POST["password"]))) {
    $login = new Login($_POST["username"]);
    if ($login->checkPassword($_POST["password"])) {
        initialise_session();
        $_SESSION["login"] = new Session($_POST["username"]);
        if(basename($_SERVER["SCRIPT_FILENAME"]) == "login.php") {
            header("Location: index.php");
        }
        else {
            header("Refresh:0");
        }
        die();
    }
    else {
        $warning = "<p class='warning'>Wrong username or password !</p>";
    }
}
require "./view/login-view.php";