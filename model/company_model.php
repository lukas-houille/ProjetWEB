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
    public int $id_field;
    public string $field;
    public array $locations;
    public float $tutor_grade;
    public int $tutor_evaluations;
    public float $student_grade;
    public int $student_evaluations;
    

    public function __construct(int $id) {
        parent::__construct();
        $this->id_company = $id;
        Mustache_Autoloader::register();
        $this->m = new Mustache_Engine;
    }
    public function fillCompany() {
        $company = $this->executeQuery("SELECT id_company,Company.name, cesi_interns, email, Field.name AS field, Field.id_field, Company.link FROM Company JOIN Field ON Company.id_field=Field.id_field WHERE visible=1 AND id_company=:id LIMIT 1;",["id" => $this->id_company], return_option: PDO::FETCH_OBJ);
        if(array_key_exists(0,$company)) {
            $company = $company[0];
            $this->locations = $this->executeQuery("SELECT City.id_city,City.name AS city,City.postcode FROM is_located_in JOIN City ON is_located_in.id_city=City.id_city WHERE id_company=:id",["id" => $this->id_company]);
            $this->tutor_grade = (float) $this->executeQuery("SELECT TRUNCATE(AVG(grade),1) FROM teacher_evaluates WHERE grade <= 5 AND id_company=:id",["id" => $this->id_company], return_option:PDO::FETCH_NUM)[0][0];
            $this->tutor_evaluations = (int) $this->executeQuery("SELECT COUNT(grade) FROM teacher_evaluates WHERE grade <= 5 AND id_company=:id",["id" => $this->id_company], return_option:PDO::FETCH_NUM)[0][0];
            $this->student_grade = (float) $this->executeQuery("SELECT TRUNCATE(AVG(grade),1) FROM student_evaluates WHERE grade <= 5 AND id_company=:id",["id" => $this->id_company], return_option:PDO::FETCH_NUM)[0][0];
            $this->student_evaluations = (int) $this->executeQuery("SELECT COUNT(grade) FROM student_evaluates WHERE grade <= 5 AND id_company=:id",["id" => $this->id_company], return_option:PDO::FETCH_NUM)[0][0];
            $this->name = $company->name;
            $this->cesi_interns = $company->cesi_interns;
            $this->email = $company->email;
            $this->field = $company->field;
            $this->id_field = $company->id_field;
            $this->link = $company->link;
            return true;
        }
        else {
            return false;
        }
    }
    public function fillTemplateView() {
        return($this->m->render(file_get_contents("view/templates-mustache/company-view.mustache"),$this));
    }
    public function FillTemplateEvaluate() {
        return($this->m->render(file_get_contents("view/templates-mustache/company-evaluate.mustache"),$this));
    }
    public function fillTemplateModify() {
        $existing_fields = $this->executeQuery("SELECT id_field,name FROM Field", return_option:PDO::FETCH_OBJ);
        return($this->m->render(file_get_contents("view/templates-mustache/company-update.mustache"),["company" => $this,"existing_fields" => $existing_fields]));
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
    public function modifyCompany(array $values) {
        if($this->companyExists()) {
            $possible_values = ["name","email","cesi_interns","id_field","link"];
            foreach($possible_values AS $value) {
                if(array_key_exists($value,$values)) {
                    if($this->{$value}!=$values[$value]) {
                        $this->executeQuery("UPDATE Company SET ".$value."=:value WHERE id_company=:id",["modified_value" => $value, "value" => $values[$value], "id" => $this->id_company]);
                    }
                }
            }
            $possible_arrays = [["cities","is_located_in", ["id_city","id_company"]]];
            foreach($possible_arrays AS $array) {
                $this->executeQuery("DELETE FROM ".$array[1]." WHERE id_company=:id",["id" => $this->id_company]);
                foreach($values[$array[0]] AS $value) {
                    $this->executeQuery("INSERT INTO ".$array[1]."(".$array[2][0].",".$array[2][1].") VALUES (:id_value,:id)",["id_value" => $value, "id" => $this->id_company]);
                }
            }
        }
        else {
            return false;
        }
    }
    public function studentEvaluates(int $id_student, int $grade, string $details) {
        if($grade >= 0 && $grade <= 5 && strlen($details) <= 800) {
            $this->executeQuery("DELETE FROM student_evaluates WHERE id_company=:id AND id_student=:id_student",["id" => $this->id_company, "id_student" => $id_student]);
            $this->executeQuery("INSERT INTO student_evaluates (id_company,id_student,grade,details) VALUES (:id,:id_student,:grade,:details)", ["id" => $this->id_company, "id_student" => $id_student, "grade" => $grade, "details" => $details]);
        }
    }
    public function tutorEvaluates(int $id_tutor, int $grade, string $details) {
        if($grade >= 0 && $grade <= 5 && strlen($details) <= 800) {
            $this->executeQuery("DELETE FROM teacher_evaluates WHERE id_company=:id AND id_tutor=:id_tutor",["id" => $this->id_company, "id_tutor" => $id_tutor]);
            $this->executeQuery("INSERT INTO teacher_evaluates (id_company,id_tutor,grade,details) VALUES (:id,:id_tutor,:grade,:details)", ["id" => $this->id_company, "id_tutor" => $id_tutor, "grade" => $grade, "details" => $details]);
        }
    }
    public function adminEvaluates(int $id_admin, int $grade, string $details) {
        if($grade >= 0 && $grade <= 5 && strlen($details) <= 800) {
            $this->executeQuery("DELETE FROM admin_evaluates WHERE id_company=:id AND id_admin=:id_admin",["id" => $this->id_company, "id_admin" => $id_admin]);
            $this->executeQuery("INSERT INTO admin_evaluates (id_company,id_admin,grade,details) VALUES (:id,:id_admin,:grade,:details)", ["id" => $this->id_company, "id_admin" => $id_admin, "grade" => $grade, "details" => $details]);
        }
    }
}