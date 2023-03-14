<?php
$settings =
[PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //On s'assure que l'on travaille en UTF-8
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, /*On récupère les erreurs de BDD (développement)*/
PDO::ATTR_EMULATE_PREPARES => false /*Prepared SQL queries aren't emulated, we only execute preparated queries for security measures (preventing SQL injections)*/];
$user = "leo"; // Database's user
$password = ""; // User's password
$address = "mysql:host=localhost;dbname=web_test"; // Databse type followed by address and database used