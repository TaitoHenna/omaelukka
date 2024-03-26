<?php
// Istunnon aloitus
session_start();

// Linkitys tietokantaan
include("dbcon.php");

// Tarkistetaan käyttäjän kirjautuminen
if (!isset($_SESSION["user_id"])) {
    // Ohjaa käyttäjän kirjautumissivulle, jos ei ole kirjautunut sisään
    header("Location: login.php");
    exit;
}

// Lomakkeen lähetys
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nimi = $_POST["nimi"];
    $rotu = $_POST["rotu"];
    $sukupuoli = $_POST["sukupuoli"];
    $syntymäaika = $_POST["syntymäaika"];
    $paino = $_POST["paino"];
    $muistiinpanot = $_POST["muistiinpanot"];
    

    // Haetaan käyttäjän id sessiosta
    $user_id = $_SESSION["user_id"];
    // Lemmikin lisäys tietokantaan / pets -tauluun
    $insert_query = "INSERT INTO pets (nimi, rotu, sukupuoli, syntymäaika, paino, muistiinpanot, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssssssi", $nimi, $rotu, $sukupuoli, $syntymäaika, $paino, $muistiinpanot, $user_id);
    
    if ($stmt->execute()) {
        $success = "Lemmikki lisätty onnistuneesti!";
    } 
    
    else {
        $error = "Virhe lemmikin lisäämisessä: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>OmaElukka - Lisää lemmikki</title>
    <?php include("usernav.php"); ?> <!--Navigointi-->
</head>
<body style="text-align: center; background-color: #f0f0f0; ">
    <br>
    <a href="userhome.php" style="margin-left: auto;"><<< Palaa takaisin</a> <!--Takaisin -linkki-->
    <h2>Lisää lemmikki</h2>
    <br>
    <?php
    // Näytetään virhe- tai onnistumisviesti
    if (isset($error)) {
        echo "<p style='color: red; font-weight: bold; text-align: center;'>$error</p>";
    }
    if (isset($success)) {
        echo "<p style='color: green; font-weight: bold; text-align: center;'>$success</p>";
    }
    ?>
<!--Lisää lemmikki -lomake-->
    <form  style="text-align: center;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nimi">Nimi:</label><br>
        <input type="text" id="nimi" name="nimi" required><br>
        <label for="rotu">Rotu:</label><br>
        <input type="text" id="rotu" name="rotu"><br>
        <label for="sukupuoli">Sukupuoli:</label><br>
        <input type="text" id="sukupuoli" name="sukupuoli"><br>
        <label for="syntymäaika">Syntymäaika:</label><br>
        <input type="date" id="syntymäaika" name="syntymäaika"><br>
        <label for="paino">Paino (kg):</label><br>
        <input type="number" id="paino" name="paino"><br>
        <label for="muistiinpanot">Muistiinpanot:</label><br>
        <textarea id="muistiinpanot" name="muistiinpanot" style="width: 300px; height: 150px;" placeholder="Kerro lyhyesti esim. rokotuksista, terveydestä jne."></textarea>
        <br><br>
        <input type="submit" value="Lisää lemmikki">
    </form>
<br>
<?php
// Footer
include("footer.php");
?>

</body>
</html>
