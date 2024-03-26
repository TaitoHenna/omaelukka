<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmaElukka - Hakutulokset</title>
</head>
<body style="text-align: center; background-color: #f0f0f0;">
    <?php
    // Linkitykset
    include("usernav.php");
    include("dbcon.php");
    
    session_start();

    // Tarkistus, onko käyttäjä kirjautunut sisään
    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    ?>

    <h2>Hakutulokset</h2>
    <p><a href="userhome.php"> <<< Palaa takaisin</a></p>
    <br>

    <?php
    // Hakusana
    $query = $_GET["query"];

    // Käyttäjän id istunnosta
    $user_id = $_SESSION['user_id'];

    // Kysely parametreillä
    $sql = "SELECT * FROM pets WHERE user_id = ? AND (nimi LIKE ? OR rotu LIKE ? OR sukupuoli LIKE ? OR syntymäaika LIKE ? OR paino LIKE ?)";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$query%";
    $stmt->bind_param("isssss", $user_id, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);

    // Tehdään kysely
    $stmt->execute();
    $result = $stmt->get_result();

    // Hakutulokset taulukossa
    if ($result->num_rows > 0) {
        echo "<table border='2' style='margin: auto; margin-bottom: 90px; padding: 20px; background-color: white;'>";
        echo "<tr style='background-color: #e5e5e5;'><th>Nimi</th><th>Rotu</th><th>Sukupuoli</th><th>Syntymäaika</th><th>Paino</th><th>Muistiinpanot</th><th>Muokkaa</th><th>Poista</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["nimi"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["rotu"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["sukupuoli"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["syntymäaika"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["paino"]) . "</td>";
            echo "<td><button onclick=\"showNotes('" . htmlspecialchars($row["muistiinpanot"]) . "')\">Avaa luettavaksi</button></td>";
            echo "<td><form action='edit_pet.php' method='get' style='display: inline;'><input type='hidden' name='pet_id' value='" . $row['pet_id'] . "'><button type='submit'>Muokkaa</button></form></td>";
            echo "<td><form action='delete_pet.php' method='post' onsubmit=\"return confirm('Haluatko varmasti poistaa tämän lemmikin?');\" style='display: inline;'><input type='hidden' name='pet_id' value='" . $row['pet_id'] . "'><button type='submit'>Poista</button></form></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else {
        echo "<p>Ei hakutuloksia!</p>";
    }
    $stmt->close();
    $conn->close();
    ?>

    <script>
    // Funktio, joka näyttää lemmikin muistiinpanot
    function showNotes(notes) {
        alert(notes);
    }
    </script>
    <br>
<?php
// Footer
include("footer.php");
?>
</body>
</html>

