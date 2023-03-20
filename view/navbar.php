<nav id="nav-bar">
    <a href="index.php" id="logo">
        <img src="resources/images/LogoBig.svg" alt="Logo">
    </a>
    <div id="nav-links">
        <a href="offers.php">
            <p>Stages</p>
        </a>
        <a href="business.php">
            <p>Entreprises</p>
        </a>
        <a href="member.php">
            <?php
            echo "<p>". $_SESSION["name"] ."</p>";
            ?>
        </a>
    </div>
</nav>