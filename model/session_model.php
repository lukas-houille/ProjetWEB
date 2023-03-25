<?php
require_once("database.php");
    class Session {
        private $login = null;
        public function __construct(string &$login) {
            $this->login=$login;
        }
        public function checkLogin() {
            if(isset($this->login) && !empty($this->login)) {
                return true;
            }
            return false;
        }
        /*The following methods all instantiate a Database. The session class couldn't have been made a child of Database since it uses PDO,
        which cannot be serialised, which we need the Session class to be in order for it to be set a a $_SESSION variable.*/
        public function name() {
            if($this->checkLogin()) {
                $base = new Database();
                return($base->executeQuery("SELECT first_name,last_name FROM ((SELECT Login.login,first_name,last_name FROM Login JOIN Student ON Login.login=Student.login) UNION (SELECT Login.login,first_name,last_name FROM Login JOIN Tutor ON Login.login=Tutor.login) UNION (SELECT Login.login,first_name,last_name FROM Login JOIN Admin ON Login.login=Admin.login)) AS logins WHERE login=:login LIMIT 1;",["login"=>$this->login], return_option:PDO::FETCH_OBJ)[0]);  
            }
        }
        public function isType() {
            if($this->checkLogin()) {
                $base = new Database();
                $tables = ["Student","Tutor","Admin"];
                foreach($tables AS $table) {
                    $result = $base->executeQuery("SELECT id_".strtolower($table)." FROM ".$table." WHERE login=:login LIMIT 1",["login"=>$this->login], return_option:PDO::FETCH_NUM);
                    if(!empty($result)) {
                        return([$table,$result[0][0]]);
                    }
                }
            }
            return(null);
        }
        public function getLogin() {
            return($this->login);
        } 
    }
    function initialise_session() { // Function used to initialise/resume a session
        if(!session_id()) { // Only creates a function if one isn't already opened
            session_start();
            session_regenerate_id();
        }
    }
    function erase_session() { // Function used to erase a session, in order for the user to disconnect if he wants to
        session_unset();
        session_destroy();
    }