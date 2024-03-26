<?php
include("dbcon.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Salasanan palauttaminen</title>
        <!--Navigointi-->
        <nav style="padding: 12px; display: flex; justify-content: space-between; background-color: #DCE9E8;">
        <a href="login.php" style="font-size: 20px">OmaElukka</a>
    </nav>
</head>
<body style="background-color: #f0f0f0; text-align: center;">
<br>
<a href="login.php" style="margin-left: auto;"><<< Palaa takaisin </a>
<br>
<h2 style="text-align: center;" >Unohtuiko salasana?</h2>
<p style="text-align: center;" >Syötä sähköpostiosoitteesi, niin lähetämme sinulle linkin salasanan palauttamista varten.</p>
<form style="text-align: center;" method="post" action="reset_password.php"> <!-- Lähettäisi sähköpostiosoitteen reset_password.php-tiedostoon -->
    <label for="email">Sähköpostiosoite:</label><br>
    <input type="email" id="email" name="email"><br><br>
    <input type="submit" value="Lähetä palautuslinkki">
</form>

</body>
</html>
<?php
// Footer
include("footer.php");
?>

