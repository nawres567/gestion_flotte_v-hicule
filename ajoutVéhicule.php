<?php
// Include the database connection file
include 'config.php';
// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $ref_ext = htmlspecialchars($_POST['ref_ext']);
    $vehicle_name = htmlspecialchars($_POST['vehicle_name']);
    $vehicle_year = htmlspecialchars($_POST['vehicle_year']);
    $vehicle_description = htmlspecialchars($_POST['vehicle_description']);
    $vehicle_price = htmlspecialchars($_POST['vehicle_price']);
    $vehicle_model = htmlspecialchars($_POST['vehicle_model']);
    $vehicle_color = htmlspecialchars($_POST['vehicle_color']);
    $vehicle_manufacturer = htmlspecialchars($_POST['vehicle_manufacturer']);
    $vehicle_type = htmlspecialchars($_POST['vehicle_type']);
    $vehicle_department = htmlspecialchars($_POST['vehicle_department']);
    $vehicle_fuel_type = htmlspecialchars($_POST['vehicle_fuel_type']);

    // Générer une référence unique
    $ref = uniqid('VEH', true);

    // Nouvelle référence pour ref_ext
    

    // Chemin de fichier pour la photo du véhicule
    $target_file_photo = '';
    $target_dir = "uploads/";

    if (isset($_FILES['vehicle_photo']) && $_FILES['vehicle_photo']['error'] == UPLOAD_ERR_OK) {
        $photo = basename($_FILES['vehicle_photo']['name']);
        $target_file_photo = $target_dir . uniqid() . '_' . $photo; // Avoid overwriting files
        if (!move_uploaded_file($_FILES['vehicle_photo']['tmp_name'], $target_file_photo)) {
            echo "Erreur lors du téléchargement de la photo.";
            exit();
        }
    }

    // Gestion des fichiers pour les documents du véhicule
    $vehicle_documents = [];
    if (isset($_FILES['vehicle_documents'])) {
        foreach ($_FILES['vehicle_documents']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['vehicle_documents']['error'][$key] == UPLOAD_ERR_OK) {
                $file_name = basename($_FILES['vehicle_documents']['name'][$key]);
                $target_file_doc = $target_dir . uniqid() . '_' . $file_name; // Avoid overwriting files
                if (move_uploaded_file($tmp_name, $target_file_doc)) {
                    $vehicle_documents[] = $target_file_doc;
                } else {
                    echo "Erreur lors du téléchargement des documents.";
                    exit();
                }
            }
        }
    }

    // Convert documents array to a string
    $documents_str = implode(',', $vehicle_documents);

    // Requête SQL pour insérer les données dans la table llx_product
    $sql = "INSERT INTO llx_product (ref,  ref_ext,label,  price, note, description, photo_path, document_paths, color, manufacturer, vehicle_type, department, fuel_type, annee) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Erreur : " . $conn->error;
        exit();
    }

    $stmt->bind_param("ssssssssssssss", $ref,$ref_ext, $vehicle_name,  $vehicle_price, $vehicle_model, $vehicle_description, $target_file_photo, $documents_str, $vehicle_color, $vehicle_manufacturer, $vehicle_type, $vehicle_department, $vehicle_fuel_type, $vehicle_year);

    if ($stmt->execute()) {
        // Redirection vers vehicule.php après insertion réussie
        header("Location: vehicule.php");
        exit();
    } else {
        echo "Erreur : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/dashboard.css">
    <title>AdminHub</title>
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
        margin-left:35%;
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
                    <form id="addVehicleForm" action="ajoutVéhicule.php" method="POST" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group">
            <label for="ref_ext">Référence du véhicule:</label>
            <input type="text" name="ref_ext" placeholder="Référence du véhicule" required>
        </div>
        <div class="form-group">
            <label for="vehicle_name">Nom du véhicule:</label>
            <input type="text" name="vehicle_name" placeholder="Nom du véhicule" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="vehicle_year">Année:</label>
            <input type="year" name="vehicle_year" placeholder="Année de production">
        </div>
        <div class="form-group">
            <label for="vehicle_description">Description:</label>
            <input type="text" name="vehicle_description" placeholder="Description du véhicule">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="vehicle_price">Prix:</label>
            <input type="number" name="vehicle_price" placeholder="Prix du véhicule">
        </div>
        <div class="form-group">
            <label for="vehicle_model">Modèle:</label>
            <input type="text" name="vehicle_model" placeholder="Modèle du véhicule" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="vehicle_color">Couleur:</label>
            <input type="text" name="vehicle_color" placeholder="Couleur du véhicule">
        </div>
        <div class="form-group">
            <label for="vehicle_manufacturer">Fabricant:</label>
            <input type="text" name="vehicle_manufacturer" placeholder="Fabricant du véhicule">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="vehicle_type">Type:</label>
            <input type="text" name="vehicle_type" placeholder="Type du véhicule">
        </div>
        <div class="form-group">
            <label for="vehicle_department">Département:</label>
            <input type="text" name="vehicle_department" placeholder="Département">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="vehicle_fuel_type">Type de carburant:</label>
            <input type="text" name="vehicle_fuel_type" placeholder="Type de carburant">
        </div>
        <div class="form-group">
            <label for="vehicle_photo">Photo:</label>
            <input type="file" name="vehicle_photo" accept="image/*">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="vehicle_documents">Documents:</label>
            <input type="file" name="vehicle_documents[]" accept="application/pdf" multiple>
        </div>
    </div>
    <div class="form-row">
        <input type="submit" class="btn-submit" value="Ajouter Véhicule">
    </div>
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