<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: signup.php");
    die();
}

echo "Hello " . $_SESSION["user_id"] . "</br>";


?>
