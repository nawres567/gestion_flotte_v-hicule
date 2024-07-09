<?php
// Include the database connection file
include 'config.php';
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
                <div class="form-input"></div>
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
                            <a class="active" href="#">Accueil</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="calendrier.php">Calendrier</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Liste des entretiens</h3>
                        <button class="btn-add-driver" id="btn-add-driver" style="padding: 10px 20px; width:200px; color: white;background-color:#007bff;">
                            <a href="ajoutEntretien.php" style="color: white;">Ajouter Entretien</a>
                        </button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Véhicule</th>
                                <th>Conducteur</th>
                                <th>État</th>
                                <th>Tâche</th>
                                <th>Date</th>
                                <th>Technicien</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Sélectionnez tous les entretiens depuis la table llx_product_extrafields
                        $sql = "SELECT * FROM llx_product_extrafields";
                        $result = $conn->query($sql);

                        // Vérifiez s'il y a des enregistrements
                        if ($result->num_rows > 0) {
                            // Affichez les données de chaque ligne
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['nom'] . "</td>";
                                echo "<td>" . $row['conducteur'] . "</td>";
                                echo "<td>" . $row['tache'] . "</td>";
                                echo "<td>" . $row['etat'] . "</td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['technicien'] . "</td>";
                                echo "<td>";
                                echo " <a style='color:jaune;' href='modifierEntretien.php?id=" . $row['rowid'] . "' class='btn-action'><i class='bx bx-edit'></i> </a>  ";
                                echo " <a style='color: red;' href='supprimerEntretien.php?id=" . $row['rowid'] . "' class='btn-action' onclick='return confirmDelete()'><i class='bx bx-trash'></i> </a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Aucun entretien trouvé.</td></tr>";
                        }

                        // Fermez la connexion à la base de données
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
        return confirm("Êtes-vous sûr de vouloir supprimer cet entretien ?");
    }
    </script>

    <script src="script.js"></script>
</body>
</html>
