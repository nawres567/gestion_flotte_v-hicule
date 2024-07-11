<?php
// Inclure le fichier de connexion à la base de données
include 'config.php';

// Vérifier si l'ID du conducteur est défini dans l'URL
if (isset($_GET['id'])) {
    $driver_id = $_GET['id'];

    // Requête SQL pour récupérer les détails du conducteur et du véhicule lié
    $sql = "SELECT *, p.datec AS vehicle_year, p.description AS vehicle_description, p.price AS vehicle_price, p.note AS vehicle_model 
            FROM llx_user u
            LEFT JOIN llx_product p ON u.personal_email = p.label
            WHERE u.rowid='$driver_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- Boxicons -->
            <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
            <!-- Bootstrap CSS -->
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            <!-- Custom CSS -->
            <link rel="stylesheet" href="css/ajoutC.css">
            <title>Détails Conducteur</title>
            <style>
                .details-container {
                    display: flex;
                    justify-content: space-between;
                    margin: 20px 0;
                }

                .details-card {
                    width: 48%;
                }

                .photo-card {
                    text-align: center;
                }

                .photo-card img {
                    max-width: 100%;
                    height: auto;
                    border-radius: 8px;
                }
            </style>
        </head>

        <body>
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
                <!-- MAIN -->
                <main>
                    <div class="head-title">
                        <div class="left">
                            <h1>Détails du Conducteur</h1>
                            <ul class="breadcrumb">
                                <li><a href="#">Conducteurs</a></li>
                                <li><i class='bx bx-chevron-right'></i></li>
                                <li><a class="active" href="listConducteurs.php">Détails</a></li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <div>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li><img style='width: 300px; height: 250px;  ' src="uploads/<?php echo isset($row['photo']) ? $row['photo'] : 'default.jpg'; ?>" alt="Photo du Conducteur"></li>
                                        <li class="list-group-item"><strong>Nom:</strong> <?php echo $row['lastname']; ?>
                                        </li>
                                        <li class="list-group-item"><strong>Prénom:</strong>
                                            <?php echo $row['firstname']; ?>
                                        </li>
                                        <li class="list-group-item"><strong>Genre:</strong>
                                            <?php echo ($row['gender'] == 'M') ? 'Masculin' : 'Féminin'; ?></li>
                                        <li class="list-group-item"><strong>Date de Naissance:</strong>
                                            <?php echo isset($row['birth']) ? $row['birth'] : 'N/A'; ?></li>
                                        <li class="list-group-item"><strong>Adresse:</strong>
                                            <?php echo isset($row['address']) ? $row['address'] : 'N/A'; ?></li>
                                        <li class="list-group-item"><strong>Ville:</strong>
                                            <?php echo isset($row['lang']) ? $row['lang'] : 'N/A'; ?></li>
                                        <li class="list-group-item"><strong>Code Postal:</strong>
                                            <?php echo isset($row['zip']) ? $row['zip'] : 'N/A'; ?></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Département:</strong>
                                            <?php echo isset($row['signature']) ? $row['signature'] : 'N/A'; ?></li>
                                        <li class="list-group-item"><strong>Poste:</strong>
                                            <?php echo isset($row['job']) ? $row['job'] : 'N/A'; ?></li>
                                        <li class="list-group-item"><strong>Mobile:</strong>
                                            <?php echo isset($row['user_mobile']) ? $row['user_mobile'] : 'N/A'; ?></li>
                                        <li class="list-group-item"><strong>Email:</strong>
                                            <?php echo isset($row['email']) ? $row['email'] : 'N/A'; ?></li>
                                    </ul>
                                    <br>
                                    <br>
                                    <h5 class="card-title">Détails du Véhicule:</h5>

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Date:</strong>
                                            <?php echo isset($row['vehicle_year']) ? date('Y-m-d', strtotime($row['vehicle_year'])) : 'N/A'; ?>
                                        </li>

                                        <li class="list-group-item"><strong>Description:</strong>
                                            <?php echo isset($row['vehicle_description']) ? $row['vehicle_description'] : 'N/A'; ?>
                                        </li>
                                        <li class="list-group-item"><strong>Prix:</strong>
                                            <?php echo isset($row['vehicle_price']) ? number_format($row['vehicle_price'], 2, ',', ' ') : 'N/A'; ?>
                                        </li>

                                        <li class="list-group-item"><strong>Modèle:</strong>
                                            <?php echo isset($row['vehicle_model']) ? $row['vehicle_model'] : 'N/A'; ?></li>
                                    </ul>
                                </div>
                            </div>


                        </div>



                    </div>




                    </div>
                    </div>
                    </div>
                </main>
            </section>
        </body>

        </html>

<?php
    } else {
        echo "Aucun conducteur trouvé avec cet identifiant.";
    }
} else {
    echo "Identifiant du conducteur non spécifié.";
}

// Fermer la connexion à la base de données
$conn->close();
?>