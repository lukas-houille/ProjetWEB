<?php
require_once "./model/session_model.php";
require_once "./model/person_model.php";

initialise_session();

if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    $type = $_SESSION["login"]->isType();
    if(isset($_GET["newEntry"]) && $_GET["newEntry"] == 1 && in_array($type[0],["Student","Admin"])) {
        if(isset($_GET["type"])) {
            if($_GET["type"] == "student") {
                $student = new Student();
                if(empty($_POST)) {
                    $content = $student->fillTemplateModify("view/templates-mustache/create-student.mustache");
                }
                else {
                    if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["id_center"]) && isset($_POST["id_group"]) && isset($_POST["password"])) {
                        if($student->createStudent($_POST["first_name"],$_POST["last_name"],$_POST["id_center"],$_POST["id_group"],$_POST["password"]) != false) {
                            header("Location: person.php");
                            die();
                        }
                    }
                }
            }
        }
    }
    elseif(isset($_GET["modify"]) && $_GET["modify"] == 1 && in_array($type[0],["Student","Admin"]) && isset($_GET["id_student"])) {
        $student = new Student($_GET["id_student"]);
        if($student->fillStudent()) {
            if(!empty($_POST)) {
                $student->modifyStudent($_POST);
                $student->fillStudent();
            }
            $content = $student->fillTemplateModify("view/templates-mustache/update-student.mustache");
        }
        else {
            header('Location: ./view/error404.html');
            die();
        }
    }
    require_once "./view/person-update-view.php";
}
else {
    header("Location: login.php");
    die();
}