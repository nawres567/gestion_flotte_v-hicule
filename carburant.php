<?php
// Inclure le fichier de connexion à la base de données
include 'config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
    <title>AutoFlotte</title>
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
                    <span class="text">Paramètres</span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Déconnexion</span>
                </a>
            </li>
        </ul>
    </section>

    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Catégories</a>
            <form action="#">
                <div class="form-input"></div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
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
                    <h1>Transactions de Carburant</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Transactions de Carburant</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Liste des Transactions</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Liste des Transactions de Carburant</h3>
                        <button class="btn-add-transaction" style="padding: 10px 20px; width:190px; color: white; background-color: #007bff">
                            <a href="ajoutTransaction.php" style="color: white;">Ajouter Transaction</a>
                        </button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nom du Véhicule</th>
                                <th>Date de Transaction</th>
                                <th>Type de Carburant</th>
                                <th>Quantité de Carburant</th>
                                <th>Kilométrage</th>
                                <th>Coût Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Inclure le fichier de connexion à la base de données
                            include 'config.php';

                            // Sélectionner toutes les transactions depuis la table llx_product_fournisseur_price
                            $sql = "SELECT pf.rowid AS pf_rowid, p.label AS vehicle_name, pf.quantity, pf.mileage,pf.total ,pf.unitprice, pf.fuel_type, pf.fk_product, DATE(pf.datec) AS transaction_date 
        FROM llx_product_fournisseur_price pf 
        INNER JOIN llx_product p ON pf.fk_product = p.rowid";

                            $result = $conn->query($sql);

                            // Vérifier s'il y a des enregistrements
                            if ($result->num_rows > 0) {
                                // Afficher les données de chaque ligne
                                while ($row = $result->fetch_assoc()) {
                                    $total_cost = $row['quantity'] * $row['unitprice'];
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['vehicle_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['transaction_date']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['fuel_type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['mileage']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['total'])  . "</td>";
                                    echo "<td>";
                                    echo " <a style='color: red;' href='supprimerTransaction.php?id=" . htmlspecialchars($row['pf_rowid']) . "' class='btn-action' onclick='return confirmDelete()'><i class='bx bx-trash'></i> </a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Aucune transaction trouvée.</td></tr>";
                            }

                            // Fermer la connexion à la base de données
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    <script>
    function confirmDelete() {
        return confirm("Êtes-vous sûr de vouloir supprimer cette transaction ?");
    }
    </script>

    <script src="script.js"></script>
</body>
</html>
