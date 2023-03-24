<?php
require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");

Mustache_Autoloader::register();
$m = new Mustache_Engine;

function fillOffer(Database $base, int $id) {
    global $m;
    $offer = $base->executeQuery("SELECT Internship_offer.id_offer,duration,salary,date,places,description,Company.id_company,Company.name,City.name as city, postcode FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1 AND Internship_offer.id_offer=:id LIMIT 1",["id" => $id]);
    if(array_key_exists(0,$offer)) {
        $offer = $offer[0];
        $offer["skills"] = $base->executeQuery("SELECT Ability.id_ability,name FROM requires JOIN Ability ON requires.id_ability=Ability.id_ability WHERE id_offer=:id",["id" => $id]);
        $offer["concerns"] = $base->executeQuery("SELECT Year_group.id_group,Year_group.name FROM concerns JOIN Internship_offer ON concerns.id_offer=Internship_offer.id_offer JOIN Year_group ON Year_group.id_group=concerns.id_group WHERE Internship_offer.id_offer=:id",["id" => $id]);
        $type = $_SESSION["login"]->isType($base)[0];
        if($type == "Student"){
            $offer["action"] = "Postulez !";
        }
        elseif($type == "Admin" || $type == "Tutor") {
            $offer["action"] = "Modify";
        }
        return($m->render(file_get_contents("view/templates-mustache/postulate-view.mustache"),$offer));
    } 
    else {
        return null;
    }
}

function offerExists(Database $base, int $id) {
    global $m;
    $offer = $base->executeQuery("SELECT Internship_offer.id_offer FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1 AND Internship_offer.id_offer=:id LIMIT 1",["id" => $id]);
    if(array_key_exists(0,$offer)) {
        return true;
    }
    else {
        return false;
    }
}