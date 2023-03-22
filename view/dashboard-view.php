<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
require_once('view/navbar-view.php');
?>

<div class="content">
    <div class="title-filter">
        <h1>Dashboard</h1>
        <div class="filter">
            <button class="primary btn-filter-popup">
                <span class="text"> Etudiants </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
            <button class="primary btn-filter-popup">
                <span class="text"> Entreprises </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
            <button class="primary btn-filter-popup">
                <span class="text"> Stages </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
            <?php
            if ($type == "Admin" || $type == "Tutor") { // Todo: Remplacer par nouvelle variable
                echo '<button class="outlined btn-filter-popup">
                <span class="text"> Pilotes </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>';
            }
            ?>
        </div>
        <div class="dashboard-options">
            <button class="outlined btn-filter-popup">
                <span class="text"> Nouvelle entrée </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
            <label id="dashboard-search">
                <input type="text" placeholder="Rechercher">
                <span class="material-symbols-rounded"> search </span>
            </label>
        </div>
    </div>
    <div class="content-layout">
        <!-- Tableau html avec data et mustache -->
        <div id="dashboard-table-student">
            <?php
            require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");
            Mustache_Autoloader::register();
            $m = new Mustache_Engine;
            $template = file_get_contents('view/templates-mustache/dashboard-table-student.html');
            echo $m->render($template, ["person" => $base->executeQuery("SELECT id_student,first_name,last_name,Center.name AS center,Year_group.name AS promotion FROM Student JOIN Center ON Student.id_center=Center.id_center JOIN Year_group ON Student.id_group=Year_group.id_group;")]);
            ?>
        </div>
        <div id="dashboard-table-business">
            <?php
            require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");
            Mustache_Autoloader::register();
            $m = new Mustache_Engine;
            $template = file_get_contents('view/templates-mustache/dashboard-table-internship.html');
            echo $m->render($template, ["internship" => $base->executeQuery("")]);
            ?>
        </div>
        <div id="dashboard-table-internship">
            <?php
            require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");
            Mustache_Autoloader::register();
            $m = new Mustache_Engine;
            $template = file_get_contents('view/templates-mustache/dashboard-table-internship.html');
            echo $m->render($template, ["internship" => $base->executeQuery("SELECT Internship_offer.id_offer,duration,salary,date,places,description,Company.name,City.name as city FROM Internship_offer JOIN Company ON Internship_offer.id_company=Company.id_company JOIN City ON Internship_offer.id_city=City.id_city WHERE Internship_offer.visible=1 AND Company.visible=1")]);
            ?>
        </div>

        <div id="dashboard-table-student">
            <?php
            require_once ("vendor/mustache/mustache/src/Mustache/Autoloader.php");
            Mustache_Autoloader::register();
            $m = new Mustache_Engine;
            $template = file_get_contents('view/templates-mustache/dashboard-table-internship.html');
            echo $m->render($template, ["internship" => $base->executeQuery("")]);
            ?>
        </div>
    </div>
</div>

<?php
require_once('view/footer-view.html');
?>
</body>
</html>
