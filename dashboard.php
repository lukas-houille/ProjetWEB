<?php
require_once "./model/session_model.php";
initialise_session();
require_once "./model/dashboard_model.php";

DEFINE("per_page",10); // Amount of maximum lines per page 

if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    if(isset($_GET["page"]) && !empty($_GET["page"]) && $_GET["page"]>0) {
        $page = $_GET["page"];
    }
    else {
        $page = 1;
    }
    $type = ($_SESSION["login"]->isType($base));
    if(isset($_GET["view"]) && in_array($_GET["view"],["company","internship","tutor"])) {
        if($_GET["view"] == "company") {
            $table = companyDashboard($base, $page, per_page);
        }
        elseif($_GET["view"] == "internship") {
            $table = internshipDashboard($base, $page, per_page);
        }
        elseif($_GET["view"] == "tutor") {
            $table = tutorDashboard($base, $page, per_page);
        }
        require_once "view/dashboard-view.php";
    }
    else {
        if ($type[0] == "Admin" || $type[0] == "Tutor") {
            if ($type[0] == "Admin") {
                $table = studentDashboardAdmin($base, $page, per_page);
            }
            else {
                $table = studentDashboardTutor($base, $type[1], $page, per_page);
            }
            require_once "view/dashboard-view.php";
        } else {
            require_once "view/forbidden-view.html";
        }
    }
}
else {
    require_once "login.php";
}