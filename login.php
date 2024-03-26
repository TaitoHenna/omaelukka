<?php
// Istunnon aloitus
session_start();

// Linkitys tietokantaan
include("dbcon.php");

// Kirjautuminen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $check_query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Tarkistetaan, onko käyttäjätunnus olemassa ja täsmääkö salasana
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["username"] = $row["username"];
            header("Location: userhome.php");
            exit;
        } 
        
        else {
            // Errorit
            $error = "Väärä käyttäjätunnus tai salasana, yritä uudelleen!";
        }
    } 
    else {

        $error = "Väärä käyttäjätunnus tai salasana, yritä uudelleen!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OmaElukka - Kirjaudu sisään</title>
    <!--Navigointi-->
    <nav style="padding: 12px; display: flex; justify-content: space-between; background-color: #DCE9E8;">
        <a href="login.php" style="font-size: 20px">OmaElukka</a>
    </nav>
</head>
<body style="text-align: center; background-color: #f0f0f0;" >
<br>
<h1 style="font-style: italic;">Tervetuloa OmaElukkaan!</h1><br>

<!-- Tässä kohdassa virheilmoitus -->
<?php
if (!empty($error)) {
    echo "<p style='color: red; font-weight: bold;'>$error</p>";
}
?>
<!-- Onnistunut käyttäjätilin poisto -viesti -->
<?php if(isset($_GET['account_deleted']) && $_GET['account_deleted'] == 'true'): ?>
        <p style="color: green; font-weight: bold;">Käyttäjätilisi poisto onnistui, olet tervetullut takaisin koska vain!</p>
    <?php endif; ?>
    
<br>
<!-- Kirjautumislomake -->
<h2>Kirjaudu sisään</h2><br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Käyttäjätunnus:</label><br>
    <input type="text" id="username" name="username"><br><br>
    <label for="password">Salasana:</label><br>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Kirjaudu sisään">
</form>
<br>
<br>
<p>Uusi käyttäjä? <a href="signup.php">Rekisteröidy tästä!</a></p> <!-- Linkki rekisteröitymiseen -->
<p>Unohtuiko salasana? <a href="forgot_password.php">Vaihda tästä!</a></p> <!-- Linkki salasanan vaihtoon -->

</body>
</html>
<?php
// Footer
include("footer.php");
?>
