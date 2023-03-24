<?php
require_once "./model/session_model.php";
require_once "./model/database.php";
require_once "./model/offers_model.php";
initialise_session();
if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    if(isset($_GET["id_offer"]) && !empty($_GET["id_offer"])) {
        $content = fillOffer($base, $_GET["id_offer"]);
        if(is_null($content)) {
            require_once('./view/error404.html');
        }
        else {
            require_once("./view/single-offer-view.php");
        }
    }
    else {
        $skills = $base->executeQuery("SELECT id_ability, name FROM Ability", return_option:PDO::FETCH_OBJ);
        $skills_options = "";
        foreach($skills as $skill) {
            $skills_options.="<option value=".htmlspecialchars($skill->id_ability).">".htmlspecialchars($skill->name)."</option>";
        }
        $promotions = $base->executeQuery("SELECT id_group, name FROM Year_group", return_option:PDO::FETCH_OBJ);
        $promotions_options = "";
        foreach($promotions as $promotion) {
            $promotions_options.="<option value=".htmlspecialchars($promotion->id_group).">".htmlspecialchars($promotion->name)."</option>";
        }
        if(in_array($_SESSION["login"]->isType($base)[0],["Student","Admin"])) {
            $favorites = true;
        }
        else {
            $favorites = false;
        }
        require_once "./view/offers-view.php";
    }
}
else {
    require_once "login.php";
}