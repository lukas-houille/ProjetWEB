<?php
require_once('view/showcompany-view.php');
require_once "./model/session_model.php";
initialise_session();
if(isset($_GET["disconnect"])) {
    erase_session();
}
if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    $type = $_SESSION["login"]->isType($base);
    require_once "view/member-view.php";
}
else {
    require_once "login.php";
}