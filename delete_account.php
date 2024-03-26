<?php
session_start();

// Linkitys tietokantaan
include("dbcon.php");

// Tarkistetaan käyttäjän kirjautuminen
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Alustetaan virheilmoitus
$error = "";

// Jos käyttäjä vahvistaa tilin poiston
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Poistetaan käyttäjän lemmikit tietokannasta
    $user_id = $_SESSION['user_id'];
    $delete_pets_query = "DELETE FROM pets WHERE user_id = ?";
    $stmt_pets = $conn->prepare($delete_pets_query);
    $stmt_pets->bind_param("i", $user_id);
    
    if ($stmt_pets->execute()) {
        // Poistetaan käyttäjä tietokannasta
        $delete_user_query = "DELETE FROM users WHERE user_id = ?";
        $stmt_user = $conn->prepare($delete_user_query);
        $stmt_user->bind_param("i", $user_id);

        if ($stmt_user->execute()) {
            // Tyhjentää istunnon ja ohjaa käyttäjän kirjautumissivulle
            session_unset();
            session_destroy();
            header("Location: login.php?account_deleted=true");
            exit();
        } 
        
        else {
            $error = "Virhe käyttäjätilin poistamisessa: " . $conn->error;
        }
    } 
    
    else {
        $error = "Virhe käyttäjän lemmikkien poistamisessa: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Käyttäjätilin poisto</title>
</head>
<body>
    <p style="font-weight: bold;">Haluatko poistaa käyttäjätilisi palvelusta?</p>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <button type="submit" onclick="return confirm('Haluatko varmasti poistaa käyttäjätilin palvelusta?')">Poista käyttäjätilini</button>
    </form>
    <?php if(isset($_GET['account_deleted']) && $_GET['account_deleted'] == 'true'): ?>
        <p style="color: green;">Käyttäjätilisi poisto onnistui, olet tervetullut takaisin koska vain!</p>
    <?php endif; ?>
</body>
</html>
