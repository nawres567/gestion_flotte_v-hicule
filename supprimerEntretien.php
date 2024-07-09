<?php
// Include the database connection file
include 'config.php';

// Check if an ID is provided to delete the maintenance record
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute deletion query
    $sql_delete = "DELETE FROM llx_product_extrafields WHERE rowid = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to maintenance list page after successful deletion
        header("Location: listEntretien.php");
        exit();
    } else {
        // Error handling if deletion fails
        echo "Erreur lors de la suppression de l'entretien: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to maintenance list page if no ID is provided
    header("Location: listEntretien.php");
    exit();
}
?>
