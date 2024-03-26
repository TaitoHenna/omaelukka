<?php

// Linkitys tietokantaan
include("dbcon.php");

session_start();

// Lemmikin poistaminen

if(isset($_POST['pet_id'])) {
    $pet_id = $_POST['pet_id'];

    // Poistetaan lemmikki tietokannasta
    $delete_query = "DELETE FROM pets WHERE pet_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $pet_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Lemmikki poistettu onnistuneesti!";
    } else {
        $_SESSION['error_message'] = "Virhe lemmikin poistamisessa: " . $conn->error;
    }
}

// Ohjataan käyttäjä takaisin userhome.php-sivulle
header("Location: userhome.php");
exit;
?>
