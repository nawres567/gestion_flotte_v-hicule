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
    <title>Détails Véhicule</title>
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
            <li>
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
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <!-- Titre de la page et fil d'Ariane -->
            </div>
            <div class="details-container">
                <div class="details-card">
                    <?php
                    // Include the database connection file
                    include 'config.php';

                    // Check if ID parameter is set
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];

                        // Select vehicle details from llx_product table
                        $sql = "SELECT label, DATE(datec) AS datec, description, price, note, photo_path, document_paths FROM llx_product WHERE rowid = $id";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                    ?>
                    <h5 class="card-title">Détails du Véhicule: <?php echo $row['label']; ?></h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Date:</strong> <?php echo $row['datec']; ?></li>
                                <li class="list-group-item"><strong>Description:</strong>
                                    <?php echo $row['description']; ?></li>
                                <li class="list-group-item"><strong>Prix:</strong>
                                    <?php echo number_format($row['price'], 2, ',', ' '); ?></li>
                                <li class="list-group-item"><strong>Modèle:</strong> <?php echo $row['note']; ?></li>
                            </ul>
                            <?php if (!empty($row['document_paths'])) : ?>
                            <h5>Documents:</h5>
                            <ul>
                                <?php
                                            $documents = explode(',', $row['document_paths']);
                                            foreach ($documents as $document) {
                                                echo "<li><a href='$document' download>" . basename($document) . "</a></li>";
                                            }
                                            ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 photo-card">
                            <?php if (!empty($row['photo_path'])) : ?>
                            <img src="<?php echo $row['photo_path']; ?>" alt="Vehicle Photo">
                            <?php else : ?>
                            <p>Aucune image disponible</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                        } else {
                            echo "<p>Aucun véhicule trouvé avec cet identifiant.</p>";
                        }
                    } else {
                        echo "<p>Identifiant du véhicule non spécifié.</p>";
                    }

                    // Close the database connection
                    $conn->close();
                    ?>
                </div>
            </div>
        </main>
    </section>
</body>

</html>