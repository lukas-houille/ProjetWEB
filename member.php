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
    $id = $db->executeQuery("SELECT id_student FROM student WHERE login = :login",["login" => $_SESSION["login"]->GetLogin() ],return_option: PDO::FETCH_OBJ);
    $data = $db->executeQuery("SELECT description, company.name as companyName, city.name as location FROM internship_offer INNER JOIN company ON internship_offer.id_company=company.id_company INNER JOIN city ON internship_offer.id_city=city.id_city INNER JOIN wishes ON wishes.id_offer=internship_offer.id_offer INNER JOIN student ON student.id_:type=wishes.id_:type WHERE student.id_:type = :id;",["id" => $id, "type" => $_SESSION["login"]->isType()[0]],return_option: PDO::FETCH_OBJ);

    // show all the cards in favorite with mustache template
    $cards = "";
    Mustache_Autoloader::register();
    $m = new Mustache_Engine;
    $cards = $m->render(file_get_contents("view/templates-mustache/favorite-cards.mustache"), $data);
    require_once "view/member-view.php";
}
else {
    require_once "login.php";
}