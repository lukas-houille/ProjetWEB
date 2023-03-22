<?php
require_once "./model/session_model.php";
initialise_session();
require_once "./model/dashboard_model.php";
if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    $type = ($_SESSION["login"]->isType($base));
    if(isset($_GET["view"]) && in_array($_GET["view"],["company","internship","tutor"])) {
        if($_GET["view"] == "company") {
            $table = companyDashboard($base);
        }
        elseif($_GET["view"] == "internship") {
            $table = internshipDashboard($base);
        }
        elseif($_GET["view"] == "tutor") {
            $table = tutorDashboard($base);
        }
        require_once "view/dashboard-view.php";
    }
    else {
        if ($type[0] == "Admin" || $type[0] == "Tutor") {
            if ($type[0] == "Admin") {
                $table = studentDashboardAdmin($base);
            }
            else {
                $table = studentDashboardTutor($base, $type[1]);
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