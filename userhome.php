<?php
// Aloitetaan istunto
session_start();

// Linkitys tietokantaan
include("dbcon.php");

// Tarkistetaan, onko käyttäjän id asetettu sessioon
if (!isset($_SESSION["user_id"])) {
    // Jos käyttäjän id:tä ei ole istunnossa, ohjataan käyttäjä kirjautumissivulle
    header("Location: login.php");
    exit;
}

// Otetaan käyttäjän id ja kysely käyttäjän lemmikeistä
$user_id = $_SESSION["user_id"];
$query = "SELECT * FROM pets WHERE user_id = $user_id";
$result = $conn->query($query);
if (!$result) {
    die("Kysely epäonnistui: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OmaElukka - Omat lemmikit</title>
    <!--Käyttäjän navigointi-->
    <?php include("usernav.php"); ?>
</head>
<body style=" background-color: #f0f0f0;">
<br>

<h1 style="text-align: center;" >Omat lemmikit</h1>
<br>
<?php
include("search.php");
?>
<br>

<!-- Lisää lemmikki -painike -->
<div style="text-align: center;">
    <form action="add_pet.php" method="get" style="display: inline;">
        <button type="submit" style="padding: 8px 12px; background-color: #d4e8ec; color: black border: none; border-radius: 3px; cursor: pointer; text-decoration: none;">Lisää lemmikki</button>
    </form>
</div>

<br><br>

<?php
// Onnistumisviesti lemmikin poistoon
if(isset($_SESSION['success_message'])) {
    echo "<p style='color: green; font-weight: bold; text-align: center;'>" . $_SESSION['success_message'] . "</p>";
    // Poistaa viestin niin, ettei se näy enää sivun päivityksen jälkeen
    unset($_SESSION['success_message']);
}

// Virheilmoitus lemmikin poistossa
if(isset($_SESSION['error_message'])) {
    echo "<p style='color: red; font-weight: bold; text-align: center;'>" . $_SESSION['error_message'] . "</p>";
    // Poista viesti niin, ettei se näy enää sivun päivityksen jälkeen
    unset($_SESSION['error_message']);
}

// Jos käyttäjällä ei vielä ole lemmikkejä, näytetään viesti
if ($result->num_rows == 0) {
echo "<div style='text-align: center;'>";
echo "<p style='color: blue; font-size: 30px;'>Tervetuloa!</p>";
echo "<p style='color: blue; font-size: 20px;'>Aloitetaan lisäämällä ensimmäinen lemmikkisi!</p>";
echo "</div>";
    exit;
}

?>
<!-- Taulukko käyttäjän lemmikeistä -->
<div style="text-align: center;">
    <table border="2" style="margin: auto; padding: 20px; background-color: white;">
    <tr style="background-color: #e5e5e5;">
        <th>Nimi</th>
        <th>Rotu</th>
        <th>Sukupuoli</th>
        <th>Syntymäaika</th>
        <th>Paino (kg)</th>
        <th>Muistiinpanot</th>
        <th>Muokkaa</th>
        <th>Poista</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row["nimi"]; ?></td>
            <td><?php echo $row["rotu"]; ?></td>
            <td><?php echo $row["sukupuoli"]; ?></td>
            <td><?php echo $row["syntymäaika"]; ?></td>
            <td><?php echo $row["paino"]; ?></td>
            <td><button onclick="showNotes('<?php echo $row['muistiinpanot'];?>')">Avaa luettavaksi</button></td>
            <td>
                <form action="edit_pet.php" method="get" style="display: inline;">
                    <input type="hidden" name="pet_id" value="<?php echo $row['pet_id']; ?>">
                    <button type="submit">Muokkaa</button>
                </form>
                <td>
                <form action="delete_pet.php" method="post" onsubmit="return confirm('Haluatko varmasti poistaa tämän lemmikin?');" style="display: inline;">
                    <input type="hidden" name="pet_id" value="<?php echo $row['pet_id']; ?>">
                    <button type="submit">Poista</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<!-- Funktio lemmikin muistiinpanoille -->
<script>
function showNotes(notes) {
    alert(notes);
}
</script>
</body>
</html>
<?php
// Footer
include("footer.php");
?>