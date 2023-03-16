<?php
require_once "./model/login_model.php";
$warning = "";
if((isset($_POST["username"]) && !empty($_POST["username"])) && (isset($_POST["password"]) && !empty($_POST["password"]))) {
    $hash = userHash($_POST["username"],$base);
    if (!is_null($hash) && checkHash($hash,$_POST["password"])) {
        initialise_session();
        allow_login($_POST["username"]);
        header("Location: index.php");
        die();
    }
    else {
        $warning = "<p>Wrong password or username!</p>";
    }
}
require_once "./view/login_view.php";