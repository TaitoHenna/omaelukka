<?php
session_start();

// Tyhjentää ja tuhoaa edellisen istunnon
$_SESSION = array();
session_destroy();

// Poistaa mahdolliset tallennetut käyttäjätiedot
unset($_SESSION["user_id"]);

// Ohjataan käyttäjä kirjautumissivulle
header("Location: login.php");
exit;
?>
