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
        elseif($business->fillCompany()) {
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
            elseif(isset($_GET["evaluate"]) && $_GET["evaluate"]) {
                if(!empty($_POST) && isset($_POST["grade"]) && isset($_POST["details"])) {
                    if($type[0] == "Student") {
                        $business->studentEvaluates($type[1],$_POST["grade"],$_POST["details"]);
                    }
                    elseif($type[0] == "Tutor") {
                        $business->tutorEvaluates($type[1],$_POST["grade"],$_POST["details"]);
                    }
                    elseif($type[0] == "Admin") {
                        $business->adminEvaluates($type[1],$_POST["grade"],$_POST["details"]);
                    }
                }
                else {
                    $content = $business->FillTemplateEvaluate();
                }
            }
            else {
                $content = $business->fillTemplateView();
                $content.= '<div class="button-layout"><button type="button" onclick="window.location.href=\'business.php?id_business='.$_GET["id_business"].'&evaluate=1\'">
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
        }
        else {
            require_once('./view/error404.html');
        }
        require_once("./view/single-business-view.php");
    }
    else {    
        require_once "view/businesses-view.php";
    }
}
else {
    require_once "login.php";
}