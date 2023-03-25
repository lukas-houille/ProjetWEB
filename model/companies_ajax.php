<?php
require_once 'database.php';
require_once 'session_model.php';
initialise_session();

$base = new Database();

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'])=="XMLHTTPREQUEST") {
    if(isset($_POST["action"]) && isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
        if($_POST["action"] == "showAll") {
            $result = $base->executeQuery("SELECT Company.id_company,Company.name AS company_name,Company.cesi_interns,Company.email,Field.name AS field_name FROM Company JOIN Field ON Company.id_field=Field.id_field WHERE Company.visible=1", return_option:PDO::FETCH_OBJ);
            returnResults($result, $base);
        }
        /*elseif($_POST["action"] == "showFiltered") {
            $sql = "SELECT Internship_offer.id_offer,duration,salary,date,places,description,Company.id_company,Company.name,City.name as city, postcode FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Internship_offer.id_offer IN (SELECT Distinct(Internship_offer.id_offer) FROM Internship_offer JOIN requires ON Internship_offer.id_offer=requires.id_offer JOIN concerns ON Internship_offer.id_offer=concerns.id_offer WHERE visible=1";
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
        }*/
    }
}

function returnResults(array $result, Database $base) {
    if(empty($result)) {
        answer(404,["no offers found"]);
    }
    else {
        foreach($result as $company) {
            $company->localisations = $base->executeQuery("SELECT name,postcode FROM is_located_in JOIN City ON is_located_in.id_city=City.id_city WHERE id_company=:company",["company" => $company->id_company], return_option:PDO::FETCH_OBJ);
            $company->grade = $base->executeQuery("SELECT TRUNCATE(AVG(grade),1) FROM teacher_evaluates WHERE grade <= 5 AND id_company=:company",["company" => $company->id_company], return_option:PDO::FETCH_NUM)[0][0]; 
        }
        answer(200,$result);
    }
}

function answer(int $status_code,array $message) {
    header("Content-Type: application/json");
    http_response_code($status_code);
    echo json_encode(["response_code" => $status_code, "message" => $message]);
}