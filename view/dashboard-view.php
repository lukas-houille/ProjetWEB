<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="resources/styles/dashboard.css">
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
            <button class="primary" id="btn-table-student" onclick="window.location.href='?view=student'">
                <span class="text"> Etudiants </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
            <button class="primary" id="btn-table-compagny" onclick="window.location.href='?view=company'">
                <span class="text"> Entreprises </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
            <button class="primary" id="btn-table-internship" onclick="window.location.href='?view=internship'">
                <span class="text"> Stages </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
            <?php
            if ($type[0] == "Admin" || $type[0] == "Tutor") {
                echo
                '<button class="primary" id="btn-table-tutor" onclick="window.location.href=\'?view=tutor\'">
                <span class="text"> Pilotes </span>
                <span class="material-symbols-rounded"> filter_list </span>
                </button>';
            }
            ?>
        </div>
        <div class="dashboard-options">
            <button class="outlined btn-filter-popup" onclick="openPopUp('dashboard-popup-newEntry')">
                <span class="text"> Nouvelle entrée </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
            <label id="dashboard-search">
                <input type="text" placeholder="Rechercher">
                <span class="material-symbols-rounded"> search </span>
            </label>
        </div>
    </div>

    <div class="popup" id="dashboard-popup-newEntry">
        <!-- Filter Pop up form -->
        <form>
            <div class="close">
                <span class="material-symbols-rounded popup-close"> close </span>
            </div>
            Mettre template mustache
        </form>
    </div>

    <div class="dashboard-content-layout">
        <!-- Tableau html avec data et mustache -->
        <?= $table ?>
    </div>
</div>

<?php
require_once('view/footer-view.html');
?>
</body>
</html>
