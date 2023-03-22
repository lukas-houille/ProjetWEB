<?php
require "./model/login_model.php";
$warning = "";
if((isset($_POST["username"]) && !empty($_POST["username"])) && (isset($_POST["password"]) && !empty($_POST["password"]))) {
    $hash = userHash($_POST["username"],$base);
    if (!is_null($hash) && checkHash($hash,$_POST["password"])) {
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