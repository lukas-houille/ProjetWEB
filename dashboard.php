<?php
require_once "./model/session_model.php";
initialise_session();
if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    if ($type == "Tutor" || $type == "Admin") {
        require_once "dashboard-view.php";
    } else {
        require_once ""; // TODO: Add the forbidden page
    }
}
else {
    require_once "login.php";
}