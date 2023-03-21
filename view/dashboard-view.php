<!DOCTYPE html>
<html lang="en">
<head>
    <title>Member</title>
    <?php
    include('header.php');
    ?>
</head>
<body>
<?php
require_once('view/navbar.php');
?>

<div class="content">
    <div class="title-filter">
        <h1>Stages</h1>
        <div class="filter">
            <button class="outlined btn-filter-popup">
                <span class="text"> Etudiants </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
            <button class="outlined btn-filter-popup">
                <span class="text"> Entreprises </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
            <button class="outlined btn-filter-popup">
                <span class="text"> Stages </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
            <button class="outlined btn-filter-popup">
                <span class="text"> Pilotes </span>
                <span class="material-symbols-rounded"> filter_list </span>
            </button>
        </div>
    </div>
</div>

<?php
require_once('view/footer.html');
?>
</body>
</html>
