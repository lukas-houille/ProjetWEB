<?php
require_once "./model/session_model.php";
initialise_session();
require_once "./model/dashboard_model.php";
if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    $type = $_SESSION["login"]->isType($base);
    if ($type[0] == "Admin" || $type[0] == "Tutor") {
        if ($type[0] == "Admin") {
            $student_table = studentDashboardAdmin($base);
        }
        else {
            $student_table = studentDashboardTutor($base, $type[1]);
        }
        require_once "view/dashboard-view.php";
    } else {
        require_once "view/forbidden-view.html";
    }
}
else {
    require_once "login.php";
}