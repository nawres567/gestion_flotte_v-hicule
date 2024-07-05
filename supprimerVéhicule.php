<?php
// Include the database connection file
include 'config.php';

// Check if ID parameter is set and is a valid integer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // First delete related entries in llx_product_fournisseur_price
    $delete_prices_sql = "DELETE FROM llx_product_fournisseur_price WHERE fk_product = $id";
    
    if ($conn->query($delete_prices_sql) === TRUE) {
        // Now delete the vehicle from llx_product table
        $delete_vehicle_sql = "DELETE FROM llx_product WHERE rowid = $id";

        if ($conn->query($delete_vehicle_sql) === TRUE) {
            // Redirect to vehicule.php after successful deletion
            header("Location: vehicule.php");
            exit();
        } else {
            echo "Erreur lors de la suppression du véhicule : " . $conn->error;
        }
    } else {
        echo "Erreur lors de la suppression des prix associés au véhicule : " . $conn->error;
    }
} else {
    echo "ID invalide ou non spécifié.";
    exit();
}

// Close the database connection
$conn->close();
?>
