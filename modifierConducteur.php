<?php
// Include the database connection file
include 'config.php';

// Check if the id parameter is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to fetch driver details by id
    $sql = "SELECT rowid, gender, lastname, firstname, address, user_mobile, email, personal_email,signature, job,lang, zip, lang, birth, photo FROM llx_user WHERE rowid='$id'";
    $result = $conn->query($sql);

    // Check if a record is found
    if ($result->num_rows > 0) {
        $driver = $result->fetch_assoc();
    } else {
        echo "Conducteur non trouvé.";
        exit();
    }
} else {
    echo "ID non spécifié.";
    exit();
}

// Process form data for updating driver details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = $_POST['gender'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $address = $_POST['address'];
    $user_mobile = $_POST['user_mobile'];
    $email = $_POST['email'];
    $vehicle_label = $_POST['vehicle_label'];
    $department = $_POST['department'];
   
    $job = $_POST['job'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $birthdate = $_POST['birthdate'];
    $photo = $driver['photo'];

    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $photo = basename($_FILES['photo']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $photo;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // File uploaded successfully
        } else {
            echo "Erreur lors du téléchargement de la photo.";
            exit();
        }
    }
    // Fetch the vehicle label
    $vehicle_sql = "SELECT label FROM llx_product WHERE rowid='$vehicle_label'";
    $vehicle_result = $conn->query($vehicle_sql);

    if ($vehicle_result->num_rows > 0) {
        $vehicle_row = $vehicle_result->fetch_assoc();
        $vehicle_label = $vehicle_row['label'];
    } else {
        $vehicle_label = "Véhicule inconnu";
    }

    // SQL query to update driver details in llx_user table
    $sql = "UPDATE llx_user SET 
            gender='$gender', 
            lastname='$lastname', 
            firstname='$firstname', 
            address='$address', 
            user_mobile='$user_mobile', 
            email='$email', 
            personal_email='$vehicle_label', 
            signature='$department', 
            job='$job', 
            lang='$city', 
            zip='$zip', 
            birth='$birthdate', 
            photo='$photo' 
            WHERE rowid='$id'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to listConducteurs.php after successful update
        header("Location: listConducteurs.php");
        exit();
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
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
    <title>AdminHub</title><style>
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
                    <form id="editDriverForm" action="modifierConducteur.php?id=<?php echo $driver['rowid']; ?>" method="POST" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group">
            <label for="gender">Genre:</label>
            <select name="gender" required style="width: 40%; height: 35px;">
                <option value="M" <?php if ($driver['gender'] == 'M') echo 'selected'; ?>>Masculin</option>
                <option value="F" <?php if ($driver['gender'] == 'F') echo 'selected'; ?>>Féminin</option>
            </select>
        </div>
        <div class="form-group">
            <label for="lastname">Nom:</label>
            <input type="text" name="lastname" value="<?php echo $driver['lastname']; ?>" required style="width: 40%; height: 35px;">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="firstname">Prénom:</label>
            <input type="text" name="firstname" value="<?php echo $driver['firstname']; ?>" required style="width: 39%; height: 35px;">
        </div>
        <div class="form-group">
            <label for="address">Adresse:</label>
            <input type="text" name="address" value="<?php echo $driver['address']; ?>" style="width: 40%; height: 35px;">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="user_mobile">Mobile:</label>
            <input type="number" name="user_mobile" value="<?php echo $driver['user_mobile']; ?>" required style="width: 40%; height: 35px;">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $driver['email']; ?>" required style="width: 40%; height: 35px;">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="vehicle_label">Véhicule:</label>
            <select name="vehicle_label" required style="width: 38.5%; height: 35px;">
                <option value="">Sélectionner un véhicule</option>
                <?php
                // Fetch vehicle options from llx_product table
                $vehicle_sql = "SELECT rowid, label FROM llx_product";
                $vehicle_result = $conn->query($vehicle_sql);

                while ($vehicle_row = $vehicle_result->fetch_assoc()) {
                    $selected = ($vehicle_row['label'] == $driver['personal_email']) ? 'selected' : '';
                    echo "<option value='" . $vehicle_row['rowid'] . "' $selected>" . $vehicle_row['label'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="department">Département:</label>
            <input type="text" name="department" value="<?php echo $driver['signature']; ?>" style="width: 40%; height: 35px;">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="job">Poste:</label>
            <input type="text" name="job" value="<?php echo $driver['job']; ?>" style="width: 41%; height: 35px;">
        </div>
        <div class="form-group">
            <label for="city">Ville:</label>
            <input type="text" name="city" value="<?php echo $driver['lang']; ?>" style="width: 40%; height: 35px;">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="zip">Code Postal:</label>
            <input type="number" name="zip" value="<?php echo $driver['zip']; ?>" required style="width: 36%; height: 35px;">
        </div>
        <div class="form-group">
            <label for="birthdate">Date de Naissance:</label>
            <input type="date" name="birthdate" value="<?php echo $driver['birth']; ?>" required style="width: 40%; height: 35px;">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" name="photo" accept="image/*" style="width: 80%; height: 35px;">
            <?php if ($driver['photo']): ?>
                <img src="uploads/<?php echo $driver['photo']; ?>" alt="Driver Photo" style="width: 100px; height: 100px;">
            <?php endif; ?>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <input type="submit" value="Enregistrer" style="width: 190px; height: 40px; background-color: #007bff; color: white; border: none; border-radius: 6px;">
        </div>
    </div>
</form>
            </div>
        </main>
        <!-- MAIN -->
    </section>
</body>
</html>
