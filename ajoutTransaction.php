<?php
// Inclure le fichier de connexion à la base de données
include 'config.php';

// Traiter les données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_id = $_POST['vehicle_id'];
    $transaction_date = $_POST['transaction_date'];
    $amount = $_POST['amount'];
    $fuel_quantity = $_POST['fuel_quantity'];
    $mileage = $_POST['mileage'];

    // Insérer les données dans la table llx_product_fournisseur_price
    $sql = "INSERT INTO llx_product_fournisseur_price (fk_product, datec, price, quantity, tms, unitprice) 
            VALUES ('$vehicle_id', '$transaction_date', '$amount', '$fuel_quantity', NOW(), '$mileage')";

    if ($conn->query($sql) === TRUE) {
        // Rediriger vers listTransactions.php après une insertion réussie
        header("Location: carburant.php");
        exit();
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

    // Fermer la connexion à la base de données
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
            <li class="active">
                <a href="listConducteurs.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Conducteurs</span>
                </a>
            </li>
            <li>
                <a href="carburant.php">
                    <i class='bx bxs-gas-pump'></i>
                    <span class="text">Suivi du carburant</span>
                </a>
            </li>
            <li>
                <a href="#">
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
                <div class="form-input">
                    
                    
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">8</span>
            </a>
            <a href="#" class="profile">
                <img src="img/people.webp">
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>transactions de carburant</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">transactions de carburant</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="listConducteurs.php">List transactions de carburant</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Ajouter transactions de carburant</h3>
                    </div>
                    <form id="addTransactionForm" action="ajoutTransaction.php" method="POST">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <label for="vehicle_id">Nom du Véhicule:</label>
                                        <select  style=" padding: 12px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
  
    transition: border-color 0.3s, box-shadow 0.3s;
    width: 200px;
    height: 40px;" name="vehicle_id" required>
                                            <?php
                                            // Inclusion du fichier de connexion à la base de données
                                            include 'config.php';

                                            // Sélectionner tous les véhicules depuis la table llx_product
                                            $sql = "SELECT rowid, label FROM llx_product";
                                            $result = $conn->query($sql);

                                            // Vérifier s'il y a des enregistrements
                                            if ($result->num_rows > 0) {
                                                // Afficher les données de chaque ligne
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<option value='" . $row['rowid'] . "'>" . $row['label'] . "</option>";
                                                }
                                            } else {
                                                echo "<option disabled>Aucun véhicule disponible</option>";
                                            }

                                            // Fermer la connexion à la base de données
                                            $conn->close();
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="transaction_date">Date de Transaction:</label>
                                        <input type="date" name="transaction_date" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="amount">Montant:</label>
                                        <input type="number" step="0.01" name="amount" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="fuel_quantity">Quantité de Carburant:</label>
                                        <input type="number" step="0.01" name="fuel_quantity" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="mileage">Kilométrage:</label>
                                        <input type="number" name="mileage" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button  style="padding: 10px 20px; width:190px; color: white;" type="submit">Ajouter Transaction</button>
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
