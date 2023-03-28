<?php
require_once "./model/session_model.php";
require_once "./model/database.php";
require_once "./model/offers_model.php";

initialise_session();

if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    $type = $_SESSION["login"]->isType();
    if(isset($_GET["id_offer"]) && !empty($_GET["id_offer"])) {
        $offer = new Offer($_GET["id_offer"]);
        if(isset($_GET["delete"]) && $_GET["delete"] && in_array($type[0],["Tutor","Admin"])) {
            $offer->deleteOffer();
            header("Location: offers.php");
            die();
        }
        elseif($offer->fillOffer()) {
            if(isset($_GET["modify"]) && $_GET["modify"] && in_array($type[0],["Tutor","Admin"])) {
                if(!empty($_POST)) {
                    $offer->modifyOffer($_POST);
                    header("Refresh:0");
                    die();
                }
                else {
                    $content = $offer->fillTemplateModify();
                }
            }
            elseif(isset($_GET["stats"]) && $_GET["stats"] && in_array($type[0],["Tutor","Admin"])) {
                Mustache_Autoloader::register();
                $m = new Mustache_Engine;
                $content = $m->render(file_get_contents("view/templates-mustache/offer-stats.mustache"),array_merge(["wishlists" => $offer->wishingAmount()],$offer->isApplyingFor()));
            }
            else {
                $content = $offer->fillTemplateView();
                $content.= '<div class="button-layout">';
                if ($type[0] == "Admin" || $type[0] == "Student") {
                    $content.= '<button type="button" onclick="window.location.href=\'postulate.php?id_offer='.$_GET["id_offer"].'\'">
                    <span class="text">Postulez !</span>
                    </button>';
                }
                if ($type[0] == "Tutor" || $type[0] == "Admin") {
                    $content.= '<button type="button" onclick="window.location.href=\'offers.php?id_offer='.$_GET["id_offer"].'&modify=1\'">
                    <span class="text">Modifier</span>
                    </button>';
                    $content.='<button type="button" onclick="window.location.href=\'offers.php?id_offer='.$_GET["id_offer"].'&delete=1\'">
                    <span class="text">Supprimer</span>
                    </button>';
                    $content.='<button type="button" onclick="window.location.href=\'offers.php?id_offer='.$_GET["id_offer"].'&stats=1\'">
                    <span class="text">Statistiques</span>
                    </button>';
                }
                $content.= '</div>';
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