<?php
require_once "./model/session_model.php";
require_once "./model/database.php";
initialise_session();
if(isset($_SESSION["login"]) && $_SESSION["login"]->checkLogin()) {
    $skills = $base->executeQuery("SELECT id_ability, name FROM Ability", return_option:PDO::FETCH_OBJ);
    $skills_options = "";
    foreach($skills as $skill) {
        $skills_options.="<option value=".htmlspecialchars($skill->id_ability).">".htmlspecialchars($skill->name)."</option>";
    }
    $promotions = $base->executeQuery("SELECT id_group, name FROM Year_group", return_option:PDO::FETCH_OBJ);
    $promotions_options = "";
    foreach($promotions as $promotion) {
        $promotions_options.="<option value=".htmlspecialchars($promotion->id_group).">".htmlspecialchars($promotion->name)."</option>";
    }
    if(in_array($_SESSION["login"]->isType($base)[0],["Student","Admin"])) {
        $favorites = true;
    }
    else {
        $favorites = false;
    }
    require_once "./view/offers-view.php";
}
else {
    require_once "login.php";
}