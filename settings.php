<?php
session_start();

// Linkitys tietokantaan
include("dbcon.php");

// Onko käyttäjä kirjautunut sisään
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Ilmoituksien alustus (error ja onnistuminen)
$error = "";
$success = "";

// Käyttäjänimen vaihto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["new_username"])) {
        $new_username = $_POST["new_username"];
        
        // Katsotaan, onko uusi käyttäjänimi saatavilla
        $check_query = "SELECT * FROM users WHERE username = ? AND user_id != ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("si", $new_username, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Valitsemasi käyttäjätunnus on jo käytössä!";
        } 
        
        else {
            // Päivitetään käyttäjätunnus tietokantaan
            $update_query = "UPDATE users SET username = ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $new_username, $_SESSION['user_id']);
            
            if ($stmt->execute()) {
                $success = "Käyttäjänimi päivitetty onnistuneesti!";
            } 
            
            else {
                $error = "Virhe käyttäjänimen päivityksessä: " . $conn->error;
            }
        }
        $stmt->close();
    }
    
    // Käyttäjä haluaa vaihtaaa salasanan
    if (isset($_POST["new_password"])) {
        $new_password = $_POST["new_password"];
        
        // Uuden salasanan suolaus
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Päivitetään suolattu salasana tietokantaan
        $update_query = "UPDATE users SET password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $success = "Salasana päivitetty onnistuneesti!";
        }
        else {
            $error = "Virhe salasanan päivityksessä: " . $conn->error;
        }
        
        // Sulku
        $stmt->close();
    }

    // Käyttäjätilin poisto
    if(isset($_POST["delete_account"])) {
        // Ohjaa käyttäjätilin poistamiseen delete_account.php -tiedostoon
        include("delete_account.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmaElukka - Asetukset</title>
</head>
<body style="text-align: center; background-color: #f0f0f0;">

<!--Käyttäjän navigointi-->
<?php include("usernav.php"); ?>
<br>
<a href="userhome.php" style="margin-left: auto;"><<< Palaa takaisin</a>
<br> <br>
<h2>Asetukset</h2>
<br>
<?php if (!empty($error)) : ?>
    <p style="color: red; text-align: center; "><?php echo $error; ?></p>
<?php endif; ?>

<?php if (!empty($success)) : ?>
    <p style="color: green; font-weight: bold; text-align: center;"><?php echo $success; ?></p>
<?php endif; ?>

<p style="text-align: center;">Täällä voit muuttaa käyttäjätilisi asetuksia:</p><br>

<!--Käyttäjätunnuksen vaihto-->
<form style="text-align: center;"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<h3>Vaihda käyttäjätunnus:</h3>
    <input type="text" name="new_username" placeholder="Uusi käyttäjätunnus" required>
    <button type="submit">Vaihda käyttäjätunnus</button>
</form>
<br>

<!--Salasanan vaihto-->
<form style="text-align: center;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h3>Vaihda salasana:</h3>
    <input type="password" name="new_password" placeholder="Uusi salasana" required>
    <button type="submit">Vaihda salasana</button>
</form>
<br><br><br><br>
<!-- Käyttäjätilin poisto -->
<p style="font-weight: bold; color: blue;">Haluatko poistaa käyttäjätilisi palvelustamme?</p>
<br>
<form style="text-align: center;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <button type="submit" name="delete_account" onclick="return confirm('Haluatko varmasti poistaa käyttäjätilin palvelusta?')">Poista käyttäjätilini</button>
</form>

<?php
// Footer
include("footer.php");
?>
</body>
</html>
