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

    public function __construct(int $id=0) {
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
        if($this->id_offer != 0) {
            $offer = $this->executeQuery("SELECT Internship_offer.id_offer FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1 AND Internship_offer.id_offer=:id LIMIT 1",["id" => $this->id_offer]);
            if(array_key_exists(0,$offer)) {
                return true;
            }
        }
        return false;
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
    public function createOffer(array $values, int $id_company) {
        $needed_values = ["description","id_city","date","duration","places","salary"];
        var_dump($values);
        foreach($needed_values AS $value) { // Checks to see if the given array has all of the necessary information
            if(!array_key_exists($value,$values)) {
                return false;
            }
        }
        if($values["date"] <= date("Y-m-d")) { // Check to see if the given date isn't prior to the current date
            return false;
        }
        if($values["salary"] < 1) { // Check to see if the given salary is superior to 0
            return false;
        }
        $this->executeQuery("INSERT INTO Internship_offer (duration, salary, date, places, description, visible, id_company, id_city) VALUES (:duration,:salary,:date,:places,:description,true,:id_company,:id_city)",["duration" => $values["duration"], "salary" => $values["salary"], "date" => $values["date"], "places" => $values["places"], "description" => $values["description"], "id_company" => $id_company, "id_city" => $values["id_city"]]);
        $this->id_offer = $this->executeQuery("SELECT LAST_INSERT_ID()",return_option:PDO::FETCH_NUM)[0][0];
        $possible_arrays = [["skills","requires", ["id_ability","id_offer"]],["promotions","concerns", ["id_group","id_offer"]]];
        foreach($possible_arrays AS $array) {
            $this->executeQuery("DELETE FROM ".$array[1]." WHERE id_offer=:id",["id" => $this->id_offer]);
            foreach($values[$array[0]] AS $value) {
                $this->executeQuery("INSERT INTO ".$array[1]."(".$array[2][0].",".$array[2][1].") VALUES (:id_value,:id)",["id_value" => $value, "id" => $this->id_offer]);
            }
        }
        return true;
    }
    public function studentAppliesTo(int $id_student) {
        $this->executeQuery("INSERT INTO applies_for (id_offer,id_student,id_state) VALUES (:id_offer,:id_student,1)", ["id_student" => $id_student, "id_offer" => $this->id_offer]);
    }
    public function adminAppliesTo(int $id_admin) {
        $this->executeQuery("INSERT INTO admin_applies_for (id_admin,id_offer,id_state) VALUES (:id_admin,:id_offer,1)", ["id_admin" => $id_admin, "id_offer" => $this->id_offer]);
    }
    public function wishingAmount() {
        return((int) $this->executeQuery("SELECT COUNT(*) FROM wishes;", return_option:PDO::FETCH_NUM)[0][0] + (int) $this->executeQuery("SELECT COUNT(*) FROM admin_wishes;", return_option:PDO::FETCH_NUM)[0][0]);
    }
    public function isApplyingFor() {
        return(["admins" => $this->executeQuery("SELECT admin_applies_for.id_admin,admin_applies_for.id_offer,Admin.first_name, Admin.last_name, State.id_state, State.name FROM web_test.admin_applies_for JOIN State ON admin_applies_for.id_state=State.id_state JOIN Admin ON admin_applies_for.id_admin=Admin.id_admin WHERE id_offer=:id",["id"=>$this->id_offer], return_option:PDO::FETCH_OBJ),"students" => $this->executeQuery("SELECT applies_for.id_student,applies_for.id_offer,Student.first_name,Student.last_name,State.name, State.id_state FROM applies_for JOIN State ON applies_for.id_state=State.id_state JOIN Student ON applies_for.id_student=Student.id_student WHERE id_offer=:id",["id"=>$this->id_offer], return_option:PDO::FETCH_OBJ)]);
    }
    public function adminApplied(int $id_admin) {
        // Checks to see if the given admin applied to the offer and haven't got an answer yet
        $result = $this->executeQuery("SELECT id_admin FROM admin_applies_for WHERE id_offer=:id AND id_admin=:id_admin LIMIT 1", ["id" => $this->id_offer, "id_admin" => $id_admin], return_option:PDO::FETCH_OBJ);
        if(array_key_exists(0,$result)) { // If query result isn't empty
            if($result[0]->id_state == 1) { // If the application is still on pending state
                return true;
            }
        }
        return false;
    }
    public function studentApplied(int $id_student) {
        // Checks to see if the given student applied to the offer and haven't got an answer yet
        $result = $this->executeQuery("SELECT id_state FROM applies_for WHERE id_offer=:id AND id_student=:id_student LIMIT 1", ["id" => $this->id_offer, "id_student" => $id_student], return_option:PDO::FETCH_OBJ);
        if(array_key_exists(0,$result)) { // If query result isn't empty
            if($result[0]->id_state == 1) { // If the application is still on pending state
                return true;
            }
        }
        return false;
    }
    public function modifyStateAdmin(int $id_admin, int $id_state) {
        $this->executeQuery("UPDATE admin_applies_for SET id_state=:id_state WHERE id_offer=:id AND id_admin=:id_admin", ["id" => $this->id_offer, "id_admin" => $id_admin, "id_state" => $id_state]);
        if($id_state == 2) {
            $this->updateCompanyStats();
        }
    }
    public function modifyStateStudent(int $id_student, int $id_state) {
        $this->executeQuery("UPDATE applies_for SET id_state=:id_state WHERE id_offer=:id AND id_student=:id_student", ["id" => $this->id_offer, "id_student" => $id_student, "id_state" => $id_state]);
        if($id_state == 2) {
            $this->updateCompanyStats();
        }
    }
    public function updateCompanyStats() {
        $this->executeQuery("UPDATE Internship_offer SET places=places-1 WHERE id_offer=:id",["id" => $this->id_offer]);
        $this->executeQuery("UPDATE Company SET cesi_interns=cesi_interns+1 WHERE id_company=:id_company",["id_company" => $this->id_company]);
    }
    public function getID() {
        return($this->id_offer);
    }
}