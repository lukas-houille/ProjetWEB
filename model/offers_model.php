<?php
require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");
require_once ("database.php");

class Offer extends Database{
    // Mustache templating egine
    private $m;
    //Offer ID
    private int $id_offer;
    //Offer Infos
    public float $duration;
    public float $salary;
    public string $date;
    public float $places;
    public string $description;
    public int $id_company;
    public string $company_name;
    public string $email;
    public string $city;
    public string $postcode;
    public array $skills;
    public array $concerns;

    public function __construct(int $id) {
        parent::__construct();
        $this->id_offer = $id;
        Mustache_Autoloader::register();
        $this->m = new Mustache_Engine;
    }
    public function fillOffer() {
        $offer = $this->executeQuery("SELECT Internship_offer.id_offer,duration,salary,date,places,description,Company.id_company,Company.name,email,City.id_city,City.name as city, postcode FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1 AND Internship_offer.id_offer=:id LIMIT 1",["id" => $this->id_offer], return_option: PDO::FETCH_OBJ);
        if(array_key_exists(0,$offer)) {
            $offer = $offer[0];
            $this->skills = $this->executeQuery("SELECT Ability.id_ability,name FROM requires JOIN Ability ON requires.id_ability=Ability.id_ability WHERE id_offer=:id",["id" => $this->id_offer]);
            $this->concerns = $this->executeQuery("SELECT Year_group.id_group,Year_group.name FROM concerns JOIN Internship_offer ON concerns.id_offer=Internship_offer.id_offer JOIN Year_group ON Year_group.id_group=concerns.id_group WHERE Internship_offer.id_offer=:id",["id" => $this->id_offer]);
            $this->duration = $offer->duration;
            $this->salary = $offer->salary;
            $this->date = $offer->date;
            $this->places = $offer->places;
            $this->description = $offer->description;
            $this->id_company = $offer->id_company;
            $this->email = $offer->email;
            $this->company_name = $offer->name;
            $this->id_city = $offer->id_city;
            $this->city = $offer->city;
            $this->postcode = $offer->postcode;
            return true;
        }
        else {
            return false;
        }
    }
    public function fillTemplateView() {
        return($this->m->render(file_get_contents("view/templates-mustache/single-offer-view.mustache"),$this));
    }
    public function fillTemplateModify() {
        $existing_skills = $this->executeQuery("SELECT id_ability, name FROM Ability", return_option:PDO::FETCH_OBJ);
        $existing_promotions = $this->executeQuery("SELECT id_group, name FROM Year_group", return_option:PDO::FETCH_OBJ);
        return($this->m->render(file_get_contents("view/templates-mustache/offer-update.mustache"),["offer" => $this,"existing_skills" => $existing_skills, "existing_promotions" => $existing_promotions]));
    }
    public function offerExists() {
        $offer = $this->executeQuery("SELECT Internship_offer.id_offer FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1 AND Internship_offer.id_offer=:id LIMIT 1",["id" => $this->id_offer]);
        if(array_key_exists(0,$offer)) {
            return true;
        }
        else {
            return false;
        }
    }
    public function deleteOffer() {
        if($this->offerExists()) {
            $this->executeQuery("UPDATE Internship_offer SET visible=0 WHERE id_offer=:id_offer",["id_offer" => $this->id_offer]);
            return true;
        }
        return false;
    }
    public function modifyOffer(array $values) {
        if($this->offerExists()) {
            $possible_values = ["description","id_city","date","duration","places","salary"];
            foreach($possible_values AS $value) {
                if(array_key_exists($value,$values)) {
                    if($this->{$value}!=$values[$value]) {
                        $this->executeQuery("UPDATE Internship_offer SET ".$value."=:value WHERE id_offer=:id",["modified_value" => $value, "value" => $values[$value], "id" => $this->id_offer]);
                    }
                }
            }
            $possible_arrays = [["skills","requires", ["id_ability","id_offer"]],["promotions","concerns", ["id_group","id_offer"]]];
            foreach($possible_arrays AS $array) {
                $this->executeQuery("DELETE FROM ".$array[1]." WHERE id_offer=:id",["id" => $this->id_offer]);
                foreach($values[$array[0]] AS $value) {
                    $this->executeQuery("INSERT INTO ".$array[1]."(".$array[2][0].",".$array[2][1].") VALUES (:id_value,:id)",["id_value" => $value, "id" => $this->id_offer]);
                }
            }
        }
        else {
            return false;
        }
    }
    public function studentAppliesTo(int $id_student) {
        $this->executeQuery("INSERT INTO applies_for (id_offer,id_student) VALUES (:id_offer,:id_student)", ["id_student" => $id_student, "id_offer" => $this->id_offer]);
    }
    public function adminAppliesTo(int $id_admin) {
        $this->executeQuery("INSERT INTO admin_applies_for (id_admin,id_offer) VALUES (:id_admin,:id_offer)", ["id_admin" => $id_admin, "id_offer" => $this->id_offer]);
    }
}