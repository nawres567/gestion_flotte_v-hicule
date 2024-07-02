<?php
// Include the database connection file
include 'config.php';

// Check if ID parameter is set and is a valid integer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to delete a vehicle by ID from llx_product table
    $sql = "DELETE FROM llx_product WHERE rowid=$id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to vehicule.php after successful deletion
        header("Location: vehicule.php");
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
