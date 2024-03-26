<?php
// Linkitys tietokantaan
include("dbcon.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    
    // Tarkistaa, ettei käyttäjätunnus tai salasana -kentät ole tyhjiä (Testauksen jälkeinen korjaus)
    if (empty($username) || empty($password) || empty($email)) {
        $error = "Käyttäjätunnus, salasana ja sähköpostiosoite ovat pakollisia kenttiä.";
    } 
    
    // Onko käyttäjätunnus saatavila
    elseif (usernameFree($username, $conn)) {
        $error = "Käyttäjätunnus on jo käytössä!";
    } 
    
    else {  // Suolaus
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Lisätään käyttäjä tietokantaan
        $insert_query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sss", $username, $hashed_password, $email);
        
        if ($stmt->execute()) {
            $success = "Rekisteröityminen onnistui, voit nyt kirjautua sisään!";
        } 
        
        else {
            $error = "Virhe rekisteröitymisessä: " . $conn->error;
        }
    }
}

// Funktio käyttäjätunnuksen tarkistamiseen
function usernameFree($username, $conn) {
    $check_query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rekisteröidy</title>
    <!--Navigointi-->
    <nav style="padding: 12px; display: flex; justify-content: space-between; background-color: #DCE9E8;">
        <a href="login.php" style="font-size: 20px">OmaElukka</a>
    </nav>

</head>
<body style=" background-color: #f0f0f0; text-align: center;">
    <br>
    <!--Linkki takaisin -->
    <a href="login.php" style="margin-left: auto;"><<< Palaa takaisin </a>
    <br>

    <h2 style="text-align: center;">Rekisteröidy</h2>
    <form style="text-align: center;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Käyttäjätunnus:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Salasana:</label><br>
        <input type="password" id="password" name="password"><br>
        <label for="email">Sähköpostiosoite:</label><br>
        <input type="email" id="email" name="email"><br><br>
        <input type="submit" value="Rekisteröidy">
    </form>

    <?php
    // Virhe- tai onnistumisviesti
    if (isset($error)) {
        echo "<p style='color: red; font-weight: bold; text-align: center'>$error</p>";
    }
    if (isset($success)) {
        echo "<p style='color: green; font-weight: bold; text-align: center'>$success</p>";
    }
    ?>

</body>
</html>
<?php
// Footer
include("footer.php");
?>
