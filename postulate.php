<?php
require_once "./model/session_model.php";
initialise_session();
if(check_login()) {
    require_once "./view/postulate-view.php";
}
else {
    require_once "login.php";
}