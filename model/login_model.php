<?php
require_once "database.php";
require_once "session_model.php";

class Login extends Database {
    private $username = null;

    public function __construct(string $username) {
        parent::__construct();
        $this->username = $username;
    }

    public function checkPassword(string $password) {
        if(!is_null($this->username)) {
            $result = $this->executeQuery("SELECT password FROM Login WHERE login=:username LIMIT 1",["username" => $this->username], return_option:PDO::FETCH_OBJ);
            if(!empty($result)) {
                $hash = $result[0]->password;
                if (password_verify($password, $hash)) {
                    return true;
                }
            }
        }
        return false;
    }
}