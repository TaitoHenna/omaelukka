<?php
session_start();


// Linkitys tietokantaan
include("dbcon.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}


// Tarkistetaan, onko pet_id:tä ja onko käyttäjällä oikeudet muokata kyseistä lemmikkiä
if (!isset($_GET["pet_id"])) {
    // Jos pet_id:tä ei ole, ohjataan käyttäjä takaisin userhome.php
    header("Location: userhome.php");
    exit;
}

// Tarkistetaan, onko lemmikkiä kyseisellä id:llä ja kuuluuko se käyttäjälle
$pet_id = $_GET["pet_id"];
$user_id = $_SESSION["user_id"];
$query = "SELECT * FROM pets WHERE pet_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $pet_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    // Jos lemmikkiä ei löydy tai se ei kuulu käyttäjälle, ohjataan käyttäjä takaisin etusivulle
    header("Location: userhome.php");
    exit;
}

// Hakee lemmikin tiedot tietokannasta
$row = $result->fetch_assoc();
$nimi = $row["nimi"];
$rotu = $row["rotu"];
$sukupuoli = $row["sukupuoli"];
$syntymäaika = $row["syntymäaika"];
$paino = $row["paino"];
$muistiinpanot = $row["muistiinpanot"];

// Alustetaan error
$error = "";

// Käsittelee muokkaus -lomakkeen lähetyksen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tarkistetaan, että kaikki tarvittavat tiedot ovat saatavilla
    if (isset($_POST["nimi"], $_POST["rotu"], $_POST["sukupuoli"], $_POST["syntymäaika"], $_POST["paino"], $_POST["muistiinpanot"])) {

    // Päivittää lemmikin tiedot tietokantaan
        $nimi = $_POST["nimi"];
        $rotu = $_POST["rotu"];
        $sukupuoli = $_POST["sukupuoli"];
        $syntymäaika = $_POST["syntymäaika"];
        $paino = $_POST["paino"];
        $muistiinpanot = $_POST["muistiinpanot"];

        $update_query = "UPDATE pets SET nimi = ?, rotu = ?, sukupuoli = ?, syntymäaika = ?, paino = ?, muistiinpanot = ? WHERE pet_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssdsi", $nimi, $rotu, $sukupuoli, $syntymäaika, $paino, $muistiinpanot, $pet_id);
        $stmt->execute();

        // Ohjataan käyttäjä takaisin etusivulle
        header("Location: userhome.php");
        exit;
    } 
    else {
        // Virheilmoitus
        $error = "Virhe muokkauksessa, tarkista täyttämäsi tiedot!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("usernav.php"); ?>
    <meta charset="UTF-8">
    <title>OmaElukka - Muokkaa lemmikkiä</title>
</head>
<body style="text-align: center; background-color: #f0f0f0;">
<br>
<a href="userhome.php" style="margin-left: auto;"><<< Palaa takaisin</a>
<!-- Muokkauslomake lemmikille -->
<h2>Muokkaa lemmikkiä</h2>
<br><br>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?pet_id=" . $pet_id; ?>">
    <label for="nimi">Nimi:</label><br>
    <input type="text" id="nimi" name="nimi" value="<?php echo $nimi; ?>" required><br>
    <label for="rotu">Rotu:</label><br>
    <input type="text" id="rotu" name="rotu" value="<?php echo $rotu; ?>" required><br>
    <label for="sukupuoli">Sukupuoli:</label><br>
    <input type="text" id="sukupuoli" name="sukupuoli" value="<?php echo $sukupuoli; ?>" required><br>
    <label for="syntymäaika">Syntymäaika:</label><br>
    <input type="date" id="syntymäaika" name="syntymäaika" value="<?php echo $syntymäaika; ?>" required><br>
    <label for="paino">Paino (kg):</label><br>
    <input type="number" id="paino" name="paino" step="0.01" value="<?php echo $paino; ?>" required><br>
    <label for="muistiinpanot">Muistiinpanot:</label><br>
    <textarea style="width: 300px; height: 150px;" placeholder="Kerro lyhyesti esim. rokotuksista, terveydestä jne." id="muistiinpanot" name="muistiinpanot"><?php echo $muistiinpanot; ?></textarea><br><br>
    <input type="submit" value="Tallenna muutokset">
</form>

</body>
</html>
<?php
// Footer
include("footer.php");
?>

