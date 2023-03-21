<?php
require_once "./model/session_model.php";
initialise_session();
if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    require_once "view/member-view.php";
}
else {
    require_once "login.php";
}