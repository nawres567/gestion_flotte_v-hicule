<?php
// Include the database connection file
include 'config.php';

// Verify if the transaction ID is passed as a parameter
if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];

    // SQL to delete a record from llx_product_fournisseur_price table based on the rowid
    $delete_sql = "DELETE FROM llx_product_fournisseur_price WHERE rowid = $transaction_id";

    if ($conn->query($delete_sql) === TRUE) {
        // Redirect to the transaction list after successful delete
        header("Location: carburant.php");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "ID de transaction non spécifié.";
    exit();
}
?>
