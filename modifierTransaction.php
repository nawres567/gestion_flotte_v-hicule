<?php
// Include the database connection file
// Include the database connection file
include 'config.php';

// Verify if the transaction ID is passed as a parameter
if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];

    // Select transaction details based on the ID
    $sql = "SELECT pf.rowid, pf.fk_product AS vehicle_id, pf.datec AS transaction_date, pf.price AS amount, pf.quantity AS fuel_quantity, pf.unitprice AS mileage, p.label AS vehicle_name 
            FROM llx_product_fournisseur_price pf 
            INNER JOIN llx_product p ON pf.fk_product = p.rowid 
            WHERE pf.rowid = $transaction_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the transaction data
        $row = $result->fetch_assoc();
        $vehicle_id = $row['vehicle_id'];
        $transaction_date = date('Y-m-d', strtotime($row['transaction_date']));
        $amount = $row['amount'];
        $fuel_quantity = $row['fuel_quantity'];
        $mileage = $row['mileage'];
        $vehicle_name = $row['vehicle_name'];
    } else {
        echo "Aucune transaction trouvée avec cet ID.";
        exit();
    }
} else {
    echo "ID de transaction non spécifié.";
    exit();
}

// Handle form data submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $new_vehicle_id = $_POST['vehicle_id'];
    $transaction_date = $_POST['transaction_date'];
    $new_amount = $_POST['amount'];
    $new_fuel_quantity = $_POST['fuel_quantity'];
    $new_mileage = $_POST['mileage'];

    // Update data in the llx_product_fournisseur_price table
    $update_sql = "UPDATE llx_product_fournisseur_price 
                   SET fk_product = '$new_vehicle_id', 
                       datec = '$transaction_date', 
                       price = '$new_amount', 
                       quantity = '$new_fuel_quantity',
                       unitprice = '$new_mileage'
                   WHERE rowid = $transaction_id";

    if ($conn->query($update_sql) === TRUE) {
        // Redirect to the transaction list after successful update
        header("Location: carburant.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour : " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
    <title>AdminHub</title>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-car'></i>
            <span class="text">AutoFlotte</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="dashbord.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="vehicule.php">
                    <i class='bx bxs-car'></i>
                    <span class="text">Véhicules</span>
                </a>
            </li>
            <li>
                <a href="listConducteurs.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Conducteurs</span>
                </a>
            </li>
            <li class="active">
                <a href="carburant.php">
                    <i class='bx bxs-gas-pump'></i>
                    <span class="text">Suivi du carburant</span>
                </a>
            </li>
            <li>
                <a href="listEntretien.php">
                    <i class='bx bxs-wrench'></i>
                    <span class="text">Maintenance</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-map'></i>
                    <span class="text">Localisation</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="#">
                <div class="form-input"></div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num"></span>
            </a>
            <a href="profil.php" class="profile">
                <img src="img/people.webp">
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Transactions du carburant</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Transactions </a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="carburant.php">Modifier Transaction</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Modifier Transaction</h3>
                    </div>
                    <form id="editTransactionForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $transaction_id; ?>" method="POST">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <label for="vehicle_id">Nom du Véhicule:</label>
                                        <select style=" padding: 12px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
  
    transition: border-color 0.3s, box-shadow 0.3s;
    width: 200px;
    height: 40px;" name="vehicle_id" required>
                                            <?php
                                            // Sélectionner tous les véhicules depuis la table llx_product
                                            $sql = "SELECT rowid, label FROM llx_product";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    // Pré-sélectionner le véhicule correspondant à la transaction
                                                    $selected = ($row['rowid'] == $vehicle_id) ? 'selected' : '';
                                                    echo "<option value='" . $row['rowid'] . "' $selected>" . $row['label'] . "</option>";
                                                }
                                            } else {
                                                echo "<option disabled>Aucun véhicule disponible</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="transaction_date">Date de Transaction:</label>
                                        <input type="date" name="transaction_date" value="<?php echo $transaction_date; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="amount">Montant:</label>
                                        <input type="number" step="0.01" name="amount" value="<?php echo $amount; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="fuel_quantity">Quantité de Carburant:</label>
                                        <input type="number" step="0.01" name="fuel_quantity" value="<?php echo $fuel_quantity; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="mileage">Kilométrage:</label>
                                        <input type="number" name="mileage" value="<?php echo $mileage; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button  style="padding: 10px 20px; width:190px; type="submit">Modifier Transaction</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </main>
    </section>
    <script src="script.js"></script>
</body>
</html>
