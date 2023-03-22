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
            if($type == "Admin" || $type == "Tutor") { // Todo: Remplacer par nouvelle variable
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
        <table>
            <thead>
            <tr>
                <th> ID </th>
                <th> Nom </th>
                <th> Prénom </th>
                <th> Promo </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td> 156 </td>
                <td> dedazazazaza </td>
                <td> szazazazazaz </td>
                <td> szazazazazaz </td>
                <td> <span class="material-symbols-rounded" id={{}}}> Edit_Note </span> </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once('view/footer-view.html');
?>
</body>
</html>
