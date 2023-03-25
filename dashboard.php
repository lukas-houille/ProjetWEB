<?php
require_once "./model/session_model.php";
initialise_session();
require_once "./model/dashboard_model.php";

DEFINE("per_page",10); // Amount of maximum lines per page 

if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    $type = ($_SESSION["login"]->isType());
    if(in_array($type[0],["Admin","Tutor"])) {
        if(isset($_GET["page"]) && !empty($_GET["page"]) && $_GET["page"]>0) {
            $page = $_GET["page"];
        }
        else {
            $page = 1;
        }
        $dashboard = new Dashboard($page,per_page);
        if(isset($_GET["view"]) && $_GET["view"] != "student") {
            if($_GET["view"] == "company") {
                $dashboard->companyDashboard();
            }
            elseif($_GET["view"] == "internship") {
                $dashboard->internshipDashboard();
            }
            elseif($_GET["view"] == "tutor") {
                $dashboard->tutorDashboard();
            }
        }
        // Default view type is student
        else {
            if ($type[0] == "Admin" || $type[0] == "Tutor") {
                if ($type[0] == "Admin") {
                    $dashboard->studentDashboardAdmin();
                }
                else {
                    $dashboard->studentDashboardTutor($type[1]);
                }
            }
        }
        $table = $dashboard->applyTemplate();
        require_once "view/dashboard-view.php";
    }
    else {
        require_once "view/forbidden-view.html";
    }
}
else {
    require_once "login.php";
}