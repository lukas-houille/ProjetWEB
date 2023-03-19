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
    <!-- mettre le contenu ici -->

    <!-- Print all the SESSION information -->
    <?php

// Print all the SESSION information
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';

    ?>

</div>

<?php
require_once('view/footer.html');
?>
</body>
</html>
