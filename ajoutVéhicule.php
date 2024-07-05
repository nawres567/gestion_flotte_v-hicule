<?php
// Include the database connection file
include 'config.php';

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $vehicle_name = $_POST['vehicle_name'];
    $vehicle_year = $_POST['vehicle_year'];
    $vehicle_description = $_POST['vehicle_description'];
    $vehicle_price = $_POST['vehicle_price'];
    $vehicle_model = $_POST['vehicle_model'];

    // Chemin de fichier pour la photo du véhicule
    $target_file_photo = '';
    if (isset($_FILES['vehicle_photo']) && $_FILES['vehicle_photo']['error'] == UPLOAD_ERR_OK) {
        $photo = basename($_FILES['vehicle_photo']['name']);
        $target_dir = "uploads/";
        $target_file_photo = $target_dir . $photo;
        if (!move_uploaded_file($_FILES['vehicle_photo']['tmp_name'], $target_file_photo)) {
            echo "Erreur lors du téléchargement de la photo.";
            exit();
        }
    }

    // Gestion des fichiers pour les documents du véhicule
    $vehicle_documents = [];
    foreach ($_FILES['vehicle_documents']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['vehicle_documents']['name'][$key];
        $file_tmp = $_FILES['vehicle_documents']['tmp_name'][$key];
        $file_type = $_FILES['vehicle_documents']['type'][$key];
        $file_size = $_FILES['vehicle_documents']['size'][$key];
        $file_error = $_FILES['vehicle_documents']['error'][$key];

        $target_file_doc = $target_dir . basename($file_name);
        if (!move_uploaded_file($file_tmp, $target_file_doc)) {
            echo "Erreur lors du téléchargement des documents.";
            exit();
        }
        $vehicle_documents[] = $target_file_doc;
    }

    // Générer une référence unique
    $ref = uniqid('VEH', true);

    // Requête SQL pour insérer les données dans la table llx_product
    $sql = "INSERT INTO llx_product (ref, label, datec, price, note, description, lifo, fifo) 
            VALUES ('$ref', '$vehicle_name', '$vehicle_year', '$vehicle_price', '$vehicle_model', '$vehicle_description', '$target_file_photo', '" . implode(',', $vehicle_documents) . "')";

    if ($conn->query($sql) === TRUE) {
        // Redirection vers vehicule.php après insertion réussie
        header("Location: vehicule.php");
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
                            <a class="active" href="vehicule.php">List Véhicules</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Ajouter Véhicule</h3>
                    </div>
                    <form id="addVehicleForm" action="ajoutVéhicule.php" method="POST">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <label for="username">Nom:</label>
                                        <input type="text" name="vehicle_name" placeholder="Nom du véhicule" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="username">Date:</label>
                                        <input type="date" name="vehicle_year" placeholder="Année du véhicule" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="username">Description:</label>
                                        <input type="textarea" style="height:80px" name="vehicle_description" placeholder="Description du véhicule"></input>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="username">Prix:</label>
                                        <input type="number" name="vehicle_price" placeholder="Prix du véhicule" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="username">Modèle:</label>
                                        <input type="text" name="vehicle_model" placeholder="Modèle du véhicule" required>
                                    </td>
                                </tr>
                                <tr>
    <td>
        <label for="vehicle_photo">Photo du véhicule:</label>
        <input type="file" name="vehicle_photo" accept="image/*">
    </td>
</tr>
<tr>
    <td>
        <label for="vehicle_documents">Documents:</label>
        <input type="file" name="vehicle_documents[]" multiple accept=".pdf,.doc,.docx">
    </td>
</tr>

                                <tr>
                                    <td>
                                        <button style="padding: 10px 20px; width:170px; color: white;" type="submit">Ajouter Véhicule</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="script.js"></script>
</body>
</html>
