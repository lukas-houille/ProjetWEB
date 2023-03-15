<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <?php
    include('header.php');
    ?>
</head>
<body>

<div id="connexion">
    <form method="post">
        <img src="resources/images/LogoBig.svg" alt="logo">
        <label for="username">
            <span>Username</span>
            <input type="text" name="username" id="username">
        </label>
        <label for="password">
            <span>Password</span>
            <input type="password" name="password" id="password">
        </label>
        <button type="submit" id="btn-login">Sign in</button>
    </form>
</div>

<?php
include('footer.html');
?>
</body>
</html>