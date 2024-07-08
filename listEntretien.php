<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/ajoutC.css">
   
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
            <li >
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
            <li class="active">
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
                    <h1>Maintenance</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Maintenance</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="listEntretien.php">List Entretiens</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Liste Entretients </h3>
                        <button class="btn-add-vehicle" id="btn-add-vehicle"
                            style="padding: 10px 20px; width:170px; color: white;background-color:#007bff;"><a
                                href="ajoutEntretien.php" style="color: white;">Ajouter Entretien</a></button>
        </div>
        <table>
                        <thead>
                            <tr>
                                <th>Nom véhicule</th>
                                
                                <th>Date de Transaction</th>
                                <th>Montant</th>
                                <th>Quantité de Carburant</th>
                                <th>Kilométrage</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Include the database connection file
                            include 'config.php';

                            // Select all transactions from llx_product_fournisseur_price table
                            $sql = "SELECT pf.rowid AS pf_rowid, p.label AS vehicle_name, pf.price, pf.quantity, pf.unitprice, DATE(pf.datec) AS transaction_date 
        FROM llx_product_fournisseur_price pf 
        INNER JOIN llx_product p ON pf.fk_product = p.rowid";
$result = $conn->query($sql);
?>


    
    
        <?php
        // Check if there are any records
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['vehicle_name']) . "</td>";
                //echo "<td>" . htmlspecialchars($row['pf_rowid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['transaction_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($row['unitprice']) . "</td>";
                echo "<td>";
                // echo "<button style='padding: 10px 20px; width:120px; margin-right: 10px; background-color: orange;'>
                //         <a style='color: white;' href='modifierTransaction.php?id=" . htmlspecialchars($row['pf_rowid']) . "' class='btn-action'>Modifier</a>
                //       </button>";

                echo " <a style='color: red;' href='supprimerTransaction.php?id=" . htmlspecialchars($row['pf_rowid']) . "' class='btn-action' onclick='return confirmDelete()'><i class='bx bx-trash'></i> </a>";



                    //   echo "<button style='padding: 10px 20px; width:120px; background-color: red;'>
                    //   <a style='color: white;' href='supprimerTransaction.php?id=" . htmlspecialchars($row['pf_rowid']) . "' class='btn-action' onclick='return confirmDelete()'>Supprimer</a>
                    // </button>";

                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>Aucune transaction trouvée.</td></tr>";
                            }

                            // Close the database connection
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