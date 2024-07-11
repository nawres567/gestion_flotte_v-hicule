<?php
// Inclure le fichier de connexion à la base de données
include 'config.php';

// Traiter les données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = $_POST['gender'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $uk_user_login = $lastname . $firstname; // Combiner lastname et firstname pour créer le login
    $address = $_POST['address'];
    $user_mobile = $_POST['user_mobile'];
    $email = $_POST['email'];
    $vehicle_label = $_POST['vehicle_label']; // Récupérer le label du véhicule sélectionné
    $department = $_POST['department'];
    $job = $_POST['job'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $birthdate = $_POST['birthdate'];

    // Traiter la photo
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $photo = basename($_FILES['photo']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $photo;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // Fichier téléchargé avec succès
        } else {
            echo "Erreur lors du téléchargement de la photo.";
            exit();
        }
    }

    // Requête SQL pour vérifier l'existence de l'email
    $check_sql = "SELECT * FROM llx_user WHERE email='$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Si l'email existe déjà, afficher un message d'erreur
        echo "Erreur : L'email '$email' existe déjà.";
    } else {
        // Récupérer le label du véhicule sélectionné
        $vehicle_sql = "SELECT label FROM llx_product WHERE rowid='$vehicle_label'";
        $vehicle_result = $conn->query($vehicle_sql);

        if ($vehicle_result->num_rows > 0) {
            $vehicle_row = $vehicle_result->fetch_assoc();
            $vehicle_label = $vehicle_row['label']; // Récupérer le label du véhicule
        } else {
            $vehicle_label = "Véhicule inconnu"; // Gérer le cas où le véhicule n'est pas trouvé
        }

        // Insérer les données dans la table llx_user
        $sql = "INSERT INTO llx_user (login, gender, lastname, firstname, address, user_mobile, email, entity, personal_email, signature , job, lang, zip, photo, birth) 
                VALUES ('$uk_user_login', '$gender', '$lastname', '$firstname', '$address', '$user_mobile', '$email', 2, '$vehicle_label', '$department', '$job', '$city', '$zip', '$photo', '$birthdate')";

        if ($conn->query($sql) === TRUE) {
            // Rediriger vers listConducteurs.php après une insertion réussie
            header("Location: listConducteurs.php");
            exit();
        } else {
            echo "Erreur : " . $sql . "<br>" . $conn->error;
        }
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
    <link rel="stylesheet" href="css/ajoutC.css">
   
    <title>AutoFlotte</title>
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
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Conducteurs</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Conducteurs</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="listConducteurs.php">List Conducteurs</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Ajouter Conducteur</h3>
        </div>
        <form id="addDriverForm" action="ajoutConducteur.php" method="POST" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group">
            <label for="gender">Genre:</label>
            <select name="gender" class="form-control" required>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
            </select>
        </div>
        <div class="form-group">
            <label for="firstname">Prénom:</label>
            <input type="text" name="firstname" class="form-control" placeholder="Prénom du conducteur" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="lastname">Nom:</label>
            <input type="text" name="lastname" class="form-control" placeholder="Nom du conducteur" required>
        </div>
        <div class="form-group">
            <label for="address">Adresse:</label>
            <input type="text" name="address" class="form-control" placeholder="Adresse du conducteur">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="user_mobile">Mobile:</label>
            <input type="text" name="user_mobile" class="form-control" placeholder="Numéro de téléphone du conducteur" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" placeholder="Email du conducteur">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="department">Département:</label>
            <input type="text" name="department" class="form-control" placeholder="Département">
        </div>
        <div class="form-group">
            <label for="job">Poste:</label>
            <input type="text" name="job" class="form-control" placeholder="Poste">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="city">Ville:</label>
            <input type="text" name="city" class="form-control" placeholder="Ville">
        </div>
        <div class="form-group">
            <label for="zip">Code Postal:</label>
            <input type="text" name="zip" class="form-control" placeholder="Code Postal">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" name="photo" class="form-control">
        </div>
        <div class="form-group">
            <label for="birthdate">Date de Naissance:</label>
            <input type="date" name="birthdate" class="form-control">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="vehicle_label">Véhicule:</label>
            <select name="vehicle_label" class="form-control" required>
                <?php
                // Inclusion du fichier de connexion à la base de données
                include 'config.php';

                // Sélectionner tous les véhicules depuis la table llx_product
                $sql = "SELECT rowid, label FROM llx_product";
                $result = $conn->query($sql);

                // Vérifier s'il y a des enregistrements
                if ($result->num_rows > 0) {
                    // Afficher les données de chaque ligne
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['rowid'] . "'>" . $row['label'] . "</option>";
                    }
                } else {
                    echo "<option disabled>Aucun véhicule disponible</option>";
                }

                // Fermer la connexion à la base de données
                $conn->close();
                ?>
            </select>
        </div>
    </div>
    <div class="form-row">
        <button type="submit" class="btn-submit">Ajouter Conducteur</button>
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