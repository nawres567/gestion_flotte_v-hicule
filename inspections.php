
<?php
// Inclure le fichier de connexion à la base de données
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
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
            <li>
                <a href="carburant.php">
                    <i class='bx bxs-gas-pump'></i>
                    <span class="text">Suivi du carburant</span>
                </a>
            </li>
            <li  >
                <a href="listEntretien.php">
                    <i class='bx bxs-wrench'></i>
                    <span class="text">Maintenance</span>
                </a>
            </li>
			<li class="active">
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
            <a href="profil.php" class="profile">
                <img src="img/people.webp">
            </a>
        </nav>
        <!-- NAVBAR -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Inspections</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Inspections</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Accueil</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                    <h3>Liste des Inspections</h3>
                        <button class="btn-add-driver" id="btn-add-driver" style="padding: 10px 20px; width:200px; color: white;background-color:#007bff;">
                            <a href="ajoutInspections.php" style="color: white;">Ajouter Inspection</a>
                        </button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Véhicule</th>
                                <th>Conducteur</th>
                                <th>État</th>
                                <th>Formulaire</th>
                                <th>Date</th>
                               
                               
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Select all inspections from llx_printing table and join with llx_product table to get the ref_ext
                        $sql = "SELECT p.rowid, p.nom_vehicule, p.formulaire_inspection, p.date_inspection, p.etat, p.nom_conducteur, pr.ref_ext
        FROM llx_printing p
        LEFT JOIN llx_product pr ON p.nom_vehicule = pr.label";

                        $result = $conn->query($sql);

                        // Check if there are any records
                        if ($result->num_rows > 0) {
                            // Display data for each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['ref_ext']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nom_vehicule']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nom_conducteur']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['etat']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['formulaire_inspection']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date_inspection']) . "</td>";
                                echo "<td>";
                                echo "<a style='color: red;' href='supprimerInspection.php?id=" . $row['rowid'] . "' class='btn-action' onclick='return confirmDelete()'><i class='bx bx-trash'></i></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Aucune inspection trouvée.</td></tr>";
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

    <!-- JavaScript for confirmation dialog -->
    <script>
    function confirmDelete() {
        return confirm("Êtes-vous sûr de vouloir supprimer cette inspection ?");
    }
    </script>
</body>
</html>