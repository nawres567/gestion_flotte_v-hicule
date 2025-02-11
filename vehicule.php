<!DOCTYPE html>
<html lang="en">

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
            <li >
                <a href="dashbord.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="active">
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

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Véhicules</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Véhicules</a>
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
                        <h3>Liste des véhicules</h3>


                        <button class="btn-add-vehicle" id="btn-add-vehicle"
                            style="padding: 10px 20px; width:170px; color: white;background-color:#007bff;"><a
                                href="ajoutVéhicule.php" style="color: white;">Ajouter Véhicule</a></button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                
                                <th>Description</th>
                                <th>Image</th>
                                <th>Prix</th>
                                <th>Modèle</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Include the database connection file
                            include 'config.php';

                            // Select all vehicles from llx_product table
                            $sql = "SELECT rowid, label, DATE(datec) AS datec, description, price, note, photo_path FROM llx_product";

                            $result = $conn->query($sql);

                            // Check if there are any records
                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['label'] . "</td>";
                                    echo "<td>" . $row['description'] . "</td>";
                                    echo "<td><img src='" . $row['photo_path'] . "' style='width: 80px; height: 60px; border-radius: 80%; object-fit: cover;' /></td>"; // Affichage de l'image
                                    echo "<td>" . number_format($row['price'], 2, ',', ' ') . "</td>";
                                    echo "<td>" . $row['note'] . "</td>";

                                    echo "<td>";

                                    echo "<a style='color: jaune;' href='modifierVéhicule.php?id=" . $row['rowid'] . "' class='btn-action'><i class='bx bx-edit'></i> </a> ";

                                    echo " <a style='color: red;' href='supprimerVéhicule.php?id=" . $row['rowid'] . "' class='btn-action' onclick='return confirmDelete()'><i class='bx bx-trash'></i> </a>";
                                    
                                    echo " <a style='color: green;' href='detailsVéhicule.php?id=" . $row['rowid'] . "' class='btn-action'><i class='bx bx-info-circle'> </i> </a>";





                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>Aucun véhicule trouvé.</td></tr>";
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
        return confirm("Êtes-vous sûr de vouloir supprimer ce véhicule ?");
    }
    </script>

    <script src="script.js"></script>
</body>

</html>