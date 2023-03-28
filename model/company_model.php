<?php
require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");
require_once ("database.php");

class Company extends Database{
    // Mustache templating egine
    private $m;
    //Offer ID
    private int $id_company;
    //Offer Infos
    public string $name;
    public int $cesi_interns;
    public string $email;
    public string $field;
    public array $locations;
    public float $grade;
    

    public function __construct(int $id) {
        parent::__construct();
        $this->id_company = $id;
        Mustache_Autoloader::register();
        $this->m = new Mustache_Engine;
    }
    public function fillCompany() {
        $company = $this->executeQuery("SELECT id_company,Company.name, cesi_interns, email, Field.name AS field FROM Company JOIN Field ON Company.id_field=Field.id_field WHERE visible=1 AND id_company=:id LIMIT 1;",["id" => $this->id_company], return_option: PDO::FETCH_OBJ);
        if(array_key_exists(0,$company)) {
            $company = $company[0];
            $this->locations = $this->executeQuery("SELECT City.id_city,City.name AS city,City.postcode FROM is_located_in JOIN City ON is_located_in.id_city=City.id_city WHERE id_company=:id",["id" => $this->id_company]);
            $this->grade = (float) $this->executeQuery("SELECT TRUNCATE(AVG(grade),1) FROM teacher_evaluates WHERE grade <= 5 AND id_company=:id",["id" => $this->id_company], return_option:PDO::FETCH_NUM)[0][0]; 
            $this->name = $company->name;
            $this->cesi_interns = $company->cesi_interns;
            $this->email = $company->email;
            $this->field = $company->field;
            return true;
        }
        else {
            return false;
        }
    }
    public function fillTemplate() {
        return($this->m->render(file_get_contents("view/templates-mustache/company-view.mustache"),$this));
    }
    public function companyExists() {
        $company = $this->executeQuery("SELECT Company.id_company FROM Company WHERE visible=1 AND id_company=:id LIMIT 1",["id" => $this->id_company]);
        if(array_key_exists(0,$company)) {
            return true;
        }
        else {
            return false;
        }
    }
    public function deleteCompany() {
        if($this->companyExists()) {
            $this->executeQuery("UPDATE Company SET visible=0 WHERE id_company=:id_company",["id_company" => $this->id_company]);
            return true;
        }
        return false;
    }
}