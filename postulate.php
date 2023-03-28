<?php
require_once "./model/login_model.php";
require_once "./model/offers_model.php";
require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");
initialise_session();
if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    $type = $_SESSION["login"]->isType();
    if(in_array($type[0],["Student","Admin"]) && isset($_GET["id_offer"]) && strlen($_GET["id_offer"]) > 0) {
        $offer = new Offer($_GET["id_offer"]);
        if ($offer->fillOffer()) {
            if(isset($_POST["sent"]) && $_POST["sent"]) {
                if($type[0] == "Student") {
                    $offer->studentAppliesTo($type[1]);
                }
                if($type[0] == "Admin") {
                    $offer->adminAppliesTo($type[1]);
                }
                header("Location: offers.php");
                die();
            }
            Mustache_Autoloader::register();
            $m = new Mustache_Engine;
            $panel = $m->render(file_get_contents("view/templates-mustache/postulate-view.mustache"),[$offer, "id_offer" => $_GET["id_offer"]]);
            require_once "./view/postulate-view.php";
        }
        else {
            header("Location: offers.php");
            die();
        }
    }
    else {
        header("Location: offers.php");
        die();
    }
}
else {
    require_once "login.php";
}