<?php
require_once "./model/session_model.php";
require_once "./model/database.php";
initialise_session();
if(check_login()) {
    $skills = $base->executeQuery("SELECT id_ability, name FROM Ability", return_option:PDO::FETCH_OBJ);
    $options = "";
    foreach($skills as $skill) {
        $options.="<option value=".htmlspecialchars($skill->id_ability).">".htmlspecialchars($skill->name)."</option>";
    }
    require_once "./view/offers_view.php";
}
else {
    require_once "login.php";
}