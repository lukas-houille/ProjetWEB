<?php
require_once 'database.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'])=="XMLHTTPREQUEST") {
    if(isset($_POST["action"])) {
        if($_POST["action"] == "showAll") {
            $result = $base->executeQuery("SELECT Internship_offer.id_offer,duration,salary,date,places,description,Company.id_company,Company.name,City.name as city, postcode FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1", return_option:PDO::FETCH_OBJ);
            returnResults($result, $base);
        }
        elseif($_POST["action"] == "showFiltered") {
            $sql = "SELECT Internship_offer.id_offer,duration,salary,date,places,description,Company.id_company,Company.name,City.name as city, postcode FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Internship_offer.id_offer IN (SELECT Distinct(Internship_offer.id_offer) FROM Internship_offer JOIN requires ON Internship_offer.id_offer=requires.id_offer JOIN concerns ON Internship_offer.id_offer=concerns.id_offer WHERE visible=1";
            $values = [];
            if(isset($_POST["skills"])) {
                $sql .= " AND id_ability IN (".implode(',', array_fill(0, count($_POST["skills"]), '?')).")";
                $values = array_merge($values,$_POST["skills"]);
            }
            $sql .= ")";
            $result = $base->executeQueryUnNamed($sql,$values,return_option:PDO::FETCH_OBJ);
            returnResults($result, $base);
        }
    }
}

function returnResults(array $result, Database $base) {
    if(empty($result)) {
        answer(404,["no offers found"]);
    }
    else {
        foreach($result as $offer) {
            $offer->abilities = $base->executeQuery("SELECT Ability.id_ability,name FROM requires JOIN Ability ON requires.id_ability=Ability.id_ability WHERE id_offer=:offer",["offer" => $offer->id_offer], return_option:PDO::FETCH_OBJ);
            $offer->groups = $base->executeQuery("SELECT Year_group.id_group,Year_group.name FROM concerns JOIN Internship_offer ON concerns.id_offer=Internship_offer.id_offer JOIN Year_group ON Year_group.id_group=concerns.id_group WHERE Internship_offer.id_offer=:offer",["offer" => $offer->id_offer], return_option:PDO::FETCH_OBJ);
        }
        answer(200,$result);
    }
}

function answer(int $status_code,array $message) {
    header("Content-Type: application/json");
    http_response_code($status_code);
    echo json_encode(["response_code" => $status_code, "message" => $message]);
}