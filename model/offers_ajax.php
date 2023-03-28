<?php
require_once 'database.php';
require_once 'session_model.php';
initialise_session();

$base = new Database();

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'])=="XMLHTTPREQUEST") {
    if(isset($_POST["action"]) && isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
        if($_POST["action"] == "showAll") {
            $result = $base->executeQuery("SELECT Internship_offer.id_offer,duration,salary,date,places,description,Company.id_company,Company.name,City.name as city, postcode, Company.link FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1", return_option:PDO::FETCH_OBJ);
            returnResults($result, $base);
        }
        elseif($_POST["action"] == "showFiltered") {
            $sql = "SELECT Internship_offer.id_offer,duration,salary,date,places,description,Company.id_company,Company.name,City.name as city, postcode, Company.link FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1 AND Internship_offer.id_offer IN (SELECT Distinct(Internship_offer.id_offer) FROM Internship_offer JOIN requires ON Internship_offer.id_offer=requires.id_offer JOIN concerns ON Internship_offer.id_offer=concerns.id_offer WHERE visible=1";
            $values = [];
            if(isset($_POST["skills"]) && !empty($_POST["skills"])) {
                $sql .= " AND id_ability IN (".implode(',', array_fill(0, count($_POST["skills"]), '?')).")";
                $values = array_merge($values,$_POST["skills"]);
            }
            if(isset($_POST["promotions"]) && !empty($_POST["promotions"])) {
                $sql .= " AND id_group IN (".implode(',', array_fill(0, count($_POST["promotions"]), '?')).")";
                $values = array_merge($values,$_POST["promotions"]);
            }
            $sql .= ")";
            $sorting_options = ["placesasc" => "places ASC","placesdesc" => "places DESC",
                "durationasc" => "duration ASC", "durationdesc" => "duration DESC",
                "salaryasc" => "salary ASC", "salarydesc" => "salary DESC",
                "dateasc" => "date ASC", "datedesc" => "date DESC"];
            if(isset($_POST["sort"]) && array_key_exists($_POST["sort"],$sorting_options)) {
                $sql .= " ORDER BY ".$sorting_options[$_POST["sort"]];
            }
            $result = $base->executeQueryUnNamed($sql,$values,return_option:PDO::FETCH_OBJ);
            returnResults($result, $base);
        }
        elseif($_POST["action"] == "setFavorite" && isset($_POST["id_offer"]) && !empty($_POST["id_offer"]) && isset($_POST["create"]) && !empty($_POST["create"])) {
            $type = $_SESSION["login"]->isType($base);
            if($type[0] == "Admin") {
                if($_POST["create"] == "true") { // jQuery AJAX sends booleans as strings
                    $base->executeQuery("INSERT INTO admin_wishes VALUES(:offer,:id)",["offer" => $_POST["id_offer"], "id" => $type[1]]);
                }
                else {
                    $base->executeQuery("DELETE FROM admin_wishes WHERE id_offer=:offer AND id_admin=:id",["offer" => $_POST["id_offer"], "id" => $type[1]]);
                }
            }
            elseif($type[0] == "Student") {
                if($_POST["create"] == "true") { // jQuery AJAX sends booleans as strings
                    $base->executeQuery("INSERT INTO wishes VALUES(:offer,:id)",["offer" => $_POST["id_offer"], "id" => $type[1]]);
                }
                else {
                    $base->executeQuery("DELETE FROM wishes WHERE id_offer=:offer AND id_student=:id",["offer" => $_POST["id_offer"], "id" => $type[1]]);
                }
            } 
        }
    }
}

function returnResults($result, Database $base) {
    if(!is_array($result) || empty($result)) {
        answer(404,["no offers found"]);
    }
    else {
        foreach($result as $offer) {
            if($offer->link === "") {
                $offer->link = "resources/images/Logo small.svg";
            }
            $offer->abilities = $base->executeQuery("SELECT Ability.id_ability,name FROM requires JOIN Ability ON requires.id_ability=Ability.id_ability WHERE id_offer=:offer",["offer" => $offer->id_offer], return_option:PDO::FETCH_OBJ);
            $offer->groups = $base->executeQuery("SELECT Year_group.id_group,Year_group.name FROM concerns JOIN Internship_offer ON concerns.id_offer=Internship_offer.id_offer JOIN Year_group ON Year_group.id_group=concerns.id_group WHERE Internship_offer.id_offer=:offer",["offer" => $offer->id_offer], return_option:PDO::FETCH_OBJ);
            $type = $_SESSION["login"]->isType($base);
            if($type[0] == "Admin") {
                if(!empty($base->executeQuery("SELECT id_offer FROM admin_wishes WHERE id_offer=:offer AND id_admin=:id LIMIT 1",["offer" => $offer->id_offer, "id" => $type[1]], return_option:PDO::FETCH_OBJ))) {
                    $offer->favorite = "checked";
                }
            }
            elseif($type[0] == "Student") {
                if(!empty($base->executeQuery("SELECT id_offer FROM wishes WHERE id_offer=:offer AND id_student=:id LIMIT 1",["offer" => $offer->id_offer, "id" => $type[1]], return_option:PDO::FETCH_OBJ))) {
                    $offer->favorite = "checked";
                } 
            } 
        }
        answer(200,$result);
    }
}

function answer(int $status_code,array $message) {
    header("Content-Type: application/json");
    http_response_code($status_code);
    echo json_encode(["response_code" => $status_code, "message" => $message]);
}