<?php
require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");
require_once ("database.php");

class Login extends Database {
    // Mustache templating egine
    public $m;
    //Login
    private string $login;

    public function __construct(string $login="") {
        parent::__construct();
        $this->login = $login;
        Mustache_Autoloader::register();
        $this->m = new Mustache_Engine;
    }
    public function createPerson(string $login, string $password) {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $this->login = $this->executeQuery("CALL createlogin(:login,:password)",["login" => $login, "password" => $password], return_option: PDO::FETCH_NUM)[0][0];
        return($this->login);
    }
    public function setLogin(string $login) {
        $this->login = $login;
    }
    public function getLogin() {
        return($this->login);
    }
}

class Student extends Login {
    //Student
    private int $id_student;

    public int $id_center;
    public int $id_group;
    public string $first_name;
    public string $last_name;

    public function __construct(int $id_student=0) {
        $this->id_student=$id_student;
        parent::__construct();
        $this->fillStudent();
    }
    public function fillStudent() {
        $result = $this->executeQuery("SELECT first_name,last_name,id_center,id_group,login FROM Student WHERE id_student=:id", ["id" => $this->id_student], return_option: PDO::FETCH_OBJ);
        if(array_key_exists(0,$result)) {
            $result = $result[0];
            $this->id_center = $result->id_center;
            $this->id_group = $result->id_group;
            $this->first_name = $result->first_name;
            $this->last_name = $result->last_name;
            $this->setLogin($result->login);    
            return($result);
        }
        return false;
    }
    public function modifyStudent(array $values) {
        $possible_values = ["first_name","last_name","id_center","id_group"];
        foreach($possible_values AS $value) {
            if(array_key_exists($value,$values)) {
                if($this->{$value}!=$values[$value]) {
                    $this->executeQuery("UPDATE Student SET ".$value."=:value WHERE id_student=:id",["modified_value" => $value, "value" => $values[$value], "id" => $this->id_student]);
                }
            }
        }
        if(isset($values["password"]) && $values["password"] != "") {
            $this->executeQuery("UPDATE Login SET password=:password WHERE login=:login",["password" => password_hash($values["password"], PASSWORD_BCRYPT), "login" => $this->getLogin()]);
        }
    }
    public function createStudent(string $first_name, string $last_name, string $id_center, string $id_group, string $password) {
        $login = $this->createPerson(($last_name.".".$first_name),$password);
        $this->executeQuery("INSERT INTO Student (first_name,last_name,id_center,id_group,login) VALUES (:first_name,:last_name,:id_center,:id_group,:login)",["first_name" => $first_name, "last_name" => $last_name, "id_center" => $id_center, "id_group" => $id_group, "login" => $login]);
        $this->id_student = $this->executeQuery("SELECT LAST_INSERT_ID()",return_option:PDO::FETCH_NUM)[0][0];
    }
    public function fillTemplateModify(string $template_location) {
        $centers = $this->executeQuery("SELECT id_center,name FROM Center",return_option: PDO::FETCH_OBJ);
        $promotions = $this->executeQuery("SELECT id_group,name FROM Year_group",return_option: PDO::FETCH_OBJ);
        return($this->m->render(file_get_contents($template_location),["centers" => $centers, "promotions" => $promotions, "first_name" => $this->first_name, "last_name" => $this->last_name]));
    }
}