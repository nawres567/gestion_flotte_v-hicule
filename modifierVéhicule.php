<?php
// Include the database connection file
include 'config.php';

// Check if the id parameter is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to fetch vehicle details by id
    $sql = "SELECT * FROM llx_product WHERE rowid='$id'";
    $result = $conn->query($sql);

    // Check if a record is found
    if ($result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();
    } else {
        echo "Véhicule non trouvé.";
        exit();
    }
} else {
    echo "ID non spécifié.";
    exit();
}

// Process form data for updating vehicle details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
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

    // Chemin de fichier pour la photo du véhicule
    $target_file_photo = $vehicle['photo_path'];
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
    $vehicle_documents = explode(',', $vehicle['document_paths']);
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

    // Requête SQL pour mettre à jour les données dans la table llx_product
    $sql = "UPDATE llx_product SET 
            label=?, 
            datec=?, 
            price=?, 
            note=?, 
            description=?, 
            photo_path=?, 
            document_paths=?, 
            color=?, 
            manufacturer=?, 
            vehicle_type=?, 
            department=?, 
            fuel_type=?
            WHERE rowid=?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Erreur : " . $conn->error;
        exit();
    }

    $stmt->bind_param("ssssssssssssi", $vehicle_name, $vehicle_year, $vehicle_price, $vehicle_model, $vehicle_description, $target_file_photo, $documents_str, $vehicle_color, $vehicle_manufacturer, $vehicle_type, $vehicle_department, $vehicle_fuel_type, $id);

    if ($stmt->execute()) {
        // Redirection vers vehicule.php après mise à jour réussie
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
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
                    <h1>Véhicules</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Véhicules</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="vehicule.php">Modifier Véhicule</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Modifier Véhicule</h3>
                    </div>
                    <form id="editVehicleForm" action="modifierVéhicule.php?id=<?php echo $vehicle['rowid']; ?>" method="POST" enctype="multipart/form-data">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <label for="vehicle_name">Nom du véhicule:</label>
                                        <input type="text" name="vehicle_name" value="<?php echo $vehicle['label']; ?>" required>
                                        <label for="vehicle_year">Année:</label>
                                        <input type="text" name="vehicle_year" value="<?php echo $vehicle['datec']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="vehicle_description">Description:</label>
                                        <input type="text" name="vehicle_description" value="<?php echo $vehicle['description']; ?>">
                                        <label for="vehicle_price">Prix:</label>
                                        <input type="text" name="vehicle_price" value="<?php echo $vehicle['price']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="vehicle_model">Modèle:</label>
                                        <input type="text" name="vehicle_model" value="<?php echo $vehicle['note']; ?>">
                                        <label for="vehicle_color">Couleur:</label>
                                        <input type="text" name="vehicle_color" value="<?php echo $vehicle['color']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="vehicle_manufacturer">Fabricant:</label>
                                        <input type="text" name="vehicle_manufacturer" value="<?php echo $vehicle['manufacturer']; ?>">
                                        <label for="vehicle_type">Type de véhicule:</label>
                                        <input type="text" name="vehicle_type" value="<?php echo $vehicle['vehicle_type']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="vehicle_department">Département:</label>
                                        <input type="text" name="vehicle_department" value="<?php echo $vehicle['department']; ?>">
                                        <label for="vehicle_fuel_type">Type de carburant:</label>
                                        <input type="text" name="vehicle_fuel_type" value="<?php echo $vehicle['fuel_type']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="vehicle_photo">Photo:</label>
                                        <input type="file" name="vehicle_photo">
                                        <?php if (!empty($vehicle['photo_path'])): ?>
                                            <img src="<?php echo $vehicle['photo_path']; ?>" alt="Vehicle Photo" style="max-width: 200px;">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="vehicle_documents">Documents:</label>
                                        <input type="file" name="vehicle_documents[]" multiple>
                                        <?php
                                        if (!empty($vehicle['document_paths'])) {
                                            $docs = explode(',', $vehicle['document_paths']);
                                            foreach ($docs as $doc) {
                                                echo "<a href='$doc'>Document</a><br>";
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button type="submit">Mettre à jour</button>
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
</body>
</html>
