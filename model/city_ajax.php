<?php
require_once 'database.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'])=="XMLHTTPREQUEST") {
    if(isset($_POST["postcode"]) && preg_match("/(?=^.{0,5}$)[0-9]{5}/", $_POST["postcode"])) { // Check to see if the postcode sent is the right size
        $base = new Database();
        $result = $base->executeQuery("SELECT id_city, name FROM City WHERE postcode=:postcode", ["postcode" => $_POST["postcode"]]);
        if(empty($result)) {
            answer(404,["no cities found"]);
        }
        else {
            answer(200,$result);
        }
    }
    else {
        answer(400,["postcode not sent or wrong format"]);
    }
}

function answer(int $status_code,array $message) {
    header("Content-Type: application/json");
    http_response_code($status_code);
    echo json_encode(["response_code" => $status_code, "message" => $message]);
}