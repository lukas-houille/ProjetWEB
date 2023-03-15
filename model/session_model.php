<?php
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
    function allow_login(string &$login) {
        $_SESSION["login"]=$login;
    }
?>