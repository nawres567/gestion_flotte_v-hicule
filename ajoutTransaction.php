<?php
// Inclure le fichier de connexion à la base de données
include 'config.php';

// Traiter les données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_id = $_POST['vehicle_id'];
    $driver_id = $_POST['driver_id'];
    $transaction_date = $_POST['transaction_date'];
    $fuel_quantity = $_POST['fuel_quantity'];
    $mileage = $_POST['mileage'];
    $unit_price = $_POST['unit_price'];
    $fuel_type = $_POST['fuel_type']; // Champ nouveau pour le type de carburant

    // Calculer le total
    $total = $fuel_quantity * $unit_price;

    // Insérer les données dans la table llx_product_fournisseur_price
    $sql = "INSERT INTO llx_product_fournisseur_price (fk_product, datec, quantity, tms, unitprice, driver_id, fuel_type, mileage, total) 
            VALUES ('$vehicle_id', '$transaction_date', '$fuel_quantity', NOW(), '$unit_price', '$driver_id', '$fuel_type', '$mileage', '$total')";

    if ($conn->query($sql) === TRUE) {
        // Rediriger vers carburant.php après une insertion réussie
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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Mon CSS -->
    <link rel="stylesheet" href="css/ajoutC.css">
    <title>AutoFlotte</title>
    <style>
        .form-row {
            display: flex;
            margin-bottom: 10px;
        }

        .form-group {
            margin-right: 20px;
        }

        .form-group:last-child {
            margin-right: 0;
        }

        .form-group label {
            width: 220px;
            display: inline-block;
        }

        .form-group input,
        .form-group select {
            width: 250px;
            height: 35px;
            padding: 5px;
            box-sizing: border-box;
        }

        .btn-submit {
            width: 200px;
            height: 40px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 35%;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-car'></i>
            <span class="text">AutoFlotte</span>
        </a>
        <ul class="side-menu top">
            <li >
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
              <a href="inspections.php"> <!-- Ajout du lien pour les inspections -->
            <i class='bx bxs-check-shield'></i>
            <span class="text">Inspections</span>
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
        <!-- BARRE DE NAVIGATION -->
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Catégories</a>
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
            <a href="profil.php" class="profile">
                <img src="img/people.webp">
            </a>
        </nav>
        <!-- BARRE DE NAVIGATION -->

        <!-- PRINCIPAL -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Transactions de carburant</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Transactions de carburant</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="listConducteurs.php">Liste des transactions de carburant</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Ajouter une transaction de carburant</h3>
                    </div>

                    <form id="addTransactionForm" action="ajoutTransaction.php" method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="vehicle_id">Véhicule :</label>
                                <select name="vehicle_id" id="vehicle_id" required>
                                    <?php
                                    // Inclure le fichier de connexion à la base de données
                                    include 'config.php';

                                    // Récupérer les véhicules depuis la table llx_product
                                    $sql = "SELECT rowid, label FROM llx_product";
                                    $result = $conn->query($sql);

                                    // Vérifier s'il y a des enregistrements
                                    if ($result->num_rows > 0) {
                                        // Afficher les options du select
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . htmlspecialchars($row['rowid']) . "'>" . htmlspecialchars($row['label']) . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>Aucun véhicule trouvé</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="driver_id">Nom du Conducteur :</label>
                                <select name="driver_id" id="driver_id" required>
                                    <?php
                                    // Inclure le fichier de connexion à la base de données
                                    include 'config.php';

                                    // Récupérer les conducteurs depuis la table llx_user où entity = 2 (par exemple)
                                    $sql = "SELECT rowid, lastname FROM llx_user WHERE entity = 2";
                                    $result = $conn->query($sql);

                                    // Vérifier s'il y a des enregistrements
                                    if ($result->num_rows > 0) {
                                        // Afficher les options du select
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . htmlspecialchars($row['rowid']) . "'>" . htmlspecialchars($row['lastname']) . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>Aucun conducteur trouvé</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="transaction_date">Date de transaction :</label>
                                <input type="date" name="transaction_date" id="transaction_date" required>
                            </div>
                            <div class="form-group">
                                <label for="fuel_type">Type de carburant :</label>
                                <select name="fuel_type" id="fuel_type" required>
                                    <option value="Essence">Essence</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Électrique">Électrique</option>
                                    <option value="Hybride">Hybride</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fuel_quantity">Quantité de carburant :</label>
                                <input type="number" name="fuel_quantity" id="fuel_quantity" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="mileage">Kilométrage :</label>
                                <input type="number" name="mileage" id="mileage" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="unit_price">Prix unitaire :</label>
                                <input type="number" name="unit_price" id="unit_price" step="0.01" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn-submit">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </section>
    <script src="script.js"></script>
</body>
</html>
