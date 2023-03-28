<?php
require_once "./model/session_model.php";
require_once "./model/company_model.php";
initialise_session();

if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    if(isset($_GET["id_business"]) && !empty($_GET["id_business"])) {
        $business = new Company($_GET["id_business"]);
        if(isset($_GET["delete"]) && $_GET["delete"]) {
            $business->deleteCompany();
            header("Location: business.php");
            die();
        }
        if($business->fillCompany()) {
            $content = $business->fillTemplate();
            $content.= '<div class="button-layout"><button type="button" onclick="">
                <span class="text">Noter</span>
                </button>';
            $type = $_SESSION["login"]->isType()[0];
            if ($type == "Tutor" || $type = "Admin") {
                $content.= '<button type="button" onclick="">
                <span class="text">Modifier</span>
                </button>
                <button type="button" onclick="window.location.href=\'business.php?id_business='.$_GET["id_business"].'&delete=1\'">
                <span class="text">Supprimer</span>
                </button>
                </div></div>';
            }
            require_once("./view/single-offer-view.php");
        }
        else {
            require_once('./view/error404.html');
        }
    }
    else {    
        require_once "view/businesses-view.php";
    }
}
else {
    require_once "login.php";
}