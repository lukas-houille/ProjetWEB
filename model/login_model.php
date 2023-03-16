<?php
require_once "database.php";
require_once "session_model.php";

function userHash(string $username, Database $base) {
    $result = $base->executeQuery("SELECT password FROM Login WHERE login=:username LIMIT 1",["username" => $_POST["username"]]);
    if (!empty($result)) {
        return $result[0]["password"];
    }
    return null;
}

function checkHash(string $hash, string $password) {
    if (password_verify($password, $hash)) {
        return true;
    }
    return false;
}
