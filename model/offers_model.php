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
        $offer = $this->executeQuery("SELECT Internship_offer.id_offer,duration,salary,date,places,description,Company.id_company,Company.name,email,City.name as city, postcode FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1 AND Internship_offer.id_offer=:id LIMIT 1",["id" => $this->id_offer], return_option: PDO::FETCH_OBJ);
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
            $this->city = $offer->city;
            $this->postcode = $offer->postcode;
            return true;
        }
        else {
            return false;
        }
    }
    public function fillTemplate() {
        return($this->m->render(file_get_contents("view/templates-mustache/single-offer-view.mustache"),$this));
    }
    public function offerExists() {
        $offer = $this->executeQuery("SELECT Internship_offer.id_offer FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1 AND Internship_offer.id_offer=:id LIMIT 1",["id" => $this->id]);
        if(array_key_exists(0,$offer)) {
            return true;
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