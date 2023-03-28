<?php
require_once "./model/session_model.php";
require_once "./model/company_model.php";

initialise_session();

if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    $type = $_SESSION["login"]->isType();
    if(isset($_GET["id_business"]) && !empty($_GET["id_business"])) {
        $business = new Company($_GET["id_business"]);
        if(isset($_GET["delete"]) && $_GET["delete"] && in_array($type[0],["Tutor","Admin"])) {
            $business->deleteCompany();
            header("Location: business.php");
            die();
        }
        if($business->fillCompany()) {
            if(isset($_GET["modify"]) && $_GET["modify"] && in_array($type[0],["Tutor","Admin"])) {
                if(!empty($_POST)) {
                    $business->modifyCompany($_POST);
                    header("Refresh:0");
                    die();
                }
                else {
                    $content = $business->fillTemplateModify();
                }
            }
            else {
                $content = $business->fillTemplateView();
                $content.= '<div class="button-layout"><button type="button" onclick="">
                    <span class="text">Noter</span>
                    </button>';
                if ($type[0] == "Tutor" || $type[0] == "Admin") {
                    $content.= '<button type="button" onclick="window.location.href=\'business.php?id_business='.$_GET["id_business"].'&modify=1\'">
                    <span class="text">Modifier</span>
                    </button>
                    <button type="button" onclick="window.location.href=\'business.php?id_business='.$_GET["id_business"].'&delete=1\'">
                    <span class="text">Supprimer</span>
                    </button>
                    </div></div>';
                }
            }
            require_once("./view/single-business-view.php");
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