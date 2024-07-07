<?php
// Include the database connection file
include 'config.php';

// Check if ID parameter is set and is a valid integer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to delete a driver by ID from llx_user table
    $sql = "DELETE FROM llx_user WHERE rowid=$id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to listConducteurs.php after successful deletion
        header("Location: listConducteurs.php");
        exit();
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "ID invalide ou non spécifié.";
    exit();
}

// Close the database connection
$conn->close();
?>
