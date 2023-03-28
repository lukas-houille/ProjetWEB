<?php
require_once "./model/session_model.php";
require_once "./model/database.php";
require_once "./model/offers_model.php";

initialise_session();

if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    if(isset($_GET["id_offer"]) && !empty($_GET["id_offer"])) {
        $offer = new Offer($_GET["id_offer"]);
        if(isset($_GET["delete"]) && $_GET["delete"]) {
            $offer->deleteOffer();
            header("Location: offers.php");
            die();
        }
        elseif($offer->fillOffer()) {
            if(isset($_GET["modify"]) && $_GET["modify"]) {
                if(!empty($_POST)) {
                    $offer->modifyOffer($_POST);
                    header("Refresh:0");
                    die();
                }
                else {
                    $content = $offer->fillTemplateModify();
                }
            }
            else {
                $content = $offer->fillTemplateView();
                $type = $_SESSION["login"]->isType()[0];
                if ($type == "Admin" || $type == "Student") {
                    $content.= '<button type="button" onclick="window.location.href=\'postulate.php?id_offer='.$_GET["id_offer"].'\'">
                    <span class="text">Postulez !</span>
                    </button>';
                }
                if ($type == "Tutor" || $type = "Admin") {
                    $content.= '<button type="button" onclick="window.location.href=\'offers.php?id_offer='.$_GET["id_offer"].'&modify=1\'">
                    <span class="text">Modifier</span>
                    </button>';
                    $content.='<button type="button" onclick="window.location.href=\'offers.php?id_offer='.$_GET["id_offer"].'&delete=1\'">
                    <span class="text">Supprimer</span>
                    </button>';
                }
            }
            require_once("./view/single-offer-view.php");
        }
        else {
            require_once('./view/error404.html');
        }
    }
    else {
        /*Filling in the filtering/sorting section*/
        $base = new Database();
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
        if(in_array($_SESSION["login"]->isType()[0],["Student","Admin"])) {
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