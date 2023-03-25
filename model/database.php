<?php
require "database-config.php";

class Database extends DatabaseConfiguration {
    private $PDO;
    private $connected = false;
    public function __construct() {
        try {
            $this->PDO = new PDO($this->address, $this->user, $this->password, $this->settings);
            $this->connected = true;
        }
        catch(PDOException $pe) {
            echo "not working";
        }
    }
    public function executeQuery(string $query_template, array $fields=[], int $return_option=PDO::FETCH_ASSOC) {
        if($this->connected) {
            try { // Try preparing the query
                $query = $this->PDO->prepare($query_template);
            }
            catch(PDOException){ // If it fails (for example if two queries were sent), return null
                return(null);
            }
            foreach($fields as $field_key => $field_value) { // Bind all fields sent as parameters
                try { // Try to apply a field to the query
                    $query->bindValue($field_key, $field_value);
                } // If the field doesn't apply (for example, an extra useless field was sent), just get to the next one
                catch(PDOException) {
                }
            }
            if(in_array($return_option,[PDO::FETCH_ASSOC, PDO::FETCH_NUM, PDO::FETCH_OBJ])) { // Check to see if the return option given is within our list, if so, apply it
                $query->setFetchMode($return_option);   
            }
            else { // Else, apply the default mode, which is FETCH_ASSOC
                $query->setFetchMode(PDO::FETCH_ASSOC);
            }
            try { // Try to execute the prepared query
                $query->execute();
                return($query->fetchall()); // If the query managed to execute, return all its results
            }
            catch(PDOException){ // Otherwise, return null
                return(null);
            }
        }
    }
    public function executeQueryUnNamed(string $query_template, array $fields, int $return_option=PDO::FETCH_ASSOC) {
        if($this->connected) {
            try { // Try preparing the query
                $query = $this->PDO->prepare($query_template);
            }
            catch(PDOException){ // If it fails (for example if two queries were sent), return null
                return(null);
            }
            if(in_array($return_option,[PDO::FETCH_ASSOC, PDO::FETCH_NUM, PDO::FETCH_OBJ])) { // Check to see if the return option given is within our list, if so, apply it
                $query->setFetchMode($return_option);   
            }
            else { // Else, apply the default mode, which is FETCH_ASSOC
                $query->setFetchMode(PDO::FETCH_ASSOC);
            }
            try { // Try to execute the prepared query
                $query->execute($fields);
                return($query->fetchall()); // If the query managed to execute, return all its results
            }
            catch(PDOException){ // Otherwise, return null
                return(null);
            }            
        }
    }
}