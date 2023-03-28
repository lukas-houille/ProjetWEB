<?php
require_once "./model/session_model.php";
require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");

initialise_session();
if(isset($_GET["disconnect"])) {
    erase_session();
}
if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    $type = $_SESSION["login"]->isType();
    // show all the cards in favorite with mustache template
    $cards = "";

    // call to database
    $db = new Database();
    // get id student from session
    if ($type[0] == "Student") {
        $id = $db->executeQuery("SELECT id_student FROM Student WHERE login = :login",["login" => $_SESSION["login"]->GetLogin() ],return_option: PDO::FETCH_OBJ);
        $data = $db->executeQuery("SELECT description, Company.name as company, City.name as location, Internship_offer.id_offer FROM Internship_offer INNER JOIN Company ON Internship_offer.id_company=Company.id_company INNER JOIN City ON Internship_offer.id_city=City.id_city INNER JOIN wishes ON wishes.id_offer=Internship_offer.id_offer INNER JOIN Student ON Student.id_student=Wishes.id_student WHERE Student.id_student = :id;",["id" => $id[0]->id_student],return_option: PDO::FETCH_OBJ);
    } elseif($type[0] == "Admin"){
        $id = $db->executeQuery("SELECT id_admin FROM Admin WHERE login = :login",["login" => $_SESSION["login"]->GetLogin() ],return_option: PDO::FETCH_OBJ);
        $data = $db->executeQuery("SELECT description, Company.name as company, City.name as location, Internship_offer.id_offer FROM Internship_offer INNER JOIN Company ON Internship_offer.id_company=Company.id_company INNER JOIN City ON Internship_offer.id_city=City.id_city INNER JOIN admin_wishes ON admin_wishes.id_offer=Internship_offer.id_offer INNER JOIN Admin ON Admin.id_admin=admin_wishes.id_admin WHERE Admin.id_admin = :id;",["id" => $id[0]->id_admin],return_option: PDO::FETCH_OBJ);
    }// show all the cards in favorite with mustache template
    $cards = "";
    Mustache_Autoloader::register();
    $m = new Mustache_Engine;
    $cards = $m->render(file_get_contents("view/templates-mustache/favorite-cards.mustache"), ["offers" => $data]);
    require_once "view/member-view.php";
}
else {
    require_once "login.php";
}