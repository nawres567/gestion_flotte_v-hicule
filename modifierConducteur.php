<?php
// Include the database connection file
include 'config.php';

// Check if the id parameter is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to fetch driver details by id
    $sql = "SELECT rowid, gender, lastname, firstname, address, user_mobile, email, personal_email FROM llx_user WHERE rowid='$id'";
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
    $vehicle_label = $_POST['vehicle_label']; // Get the selected vehicle label

    // Fetch the vehicle label
    $vehicle_sql = "SELECT label FROM llx_product WHERE rowid='$vehicle_label'";
    $vehicle_result = $conn->query($vehicle_sql);

    if ($vehicle_result->num_rows > 0) {
        $vehicle_row = $vehicle_result->fetch_assoc();
        $vehicle_label = $vehicle_row['label']; // Get the vehicle label
    } else {
        $vehicle_label = "Véhicule inconnu"; // Handle case where vehicle is not found
    }

    // SQL query to update driver details in llx_user table
    $sql = "UPDATE llx_user SET 
            gender='$gender', 
            lastname='$lastname', 
            firstname='$firstname', 
            address='$address', 
            user_mobile='$user_mobile', 
            email='$email',
            personal_email='$vehicle_label' 
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
                    <h1>Conducteurs</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Conducteurs</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="listConducteurs.php">Modifier Conducteur</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Modifier Conducteur</h3>
                    </div>
                    <form id="editDriverForm" action="modifierConducteur.php?id=<?php echo $driver['rowid']; ?>" method="POST">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <label for="gender">Genre:</label>
                                        <select  style=" padding: 12px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 6px;
    transition: border-color 0.3s, box-shadow 0.3s;
    width: 350px;
    height: 45px;" name="gender" required>
                                            <option value="M" <?php if ($driver['gender'] == 'M') echo 'selected'; ?>>Masculin</option>
                                            <option value="F" <?php if ($driver['gender'] == 'F') echo 'selected'; ?>>Féminin</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="lastname">Nom:</label>
                                        <input type="text" name="lastname" value="<?php echo $driver['lastname']; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="firstname">Prénom:</label>
                                        <input type="text" name="firstname" value="<?php echo $driver['firstname']; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="address">Adresse:</label>
                                        <input type="text" name="address" value="<?php echo $driver['address']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="user_mobile">Mobile:</label>
                                        <input type="number" name="user_mobile" value="<?php echo $driver['user_mobile']; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="email">Email:</label>
                                        <input type="email" name="email" value="<?php echo $driver['email']; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                         <label for="vehicle_label">Véhicule:</label>
                                         <select style=" padding: 12px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 6px;
    transition: border-color 0.3s, box-shadow 0.3s;
    width: 350px;
    height: 45px;" name="vehicle_label" required>
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
                                                    $selected = ($row['label'] == $driver['personal_email']) ? 'selected' : '';
                                                    echo "<option value='" . $row['rowid'] . "' $selected>" . $row['label'] . "</option>";
                                                }
                                            } else {
                                                echo "<option disabled>Aucun véhicule disponible</option>";
                                            }

                                            // Fermer la connexion à la base de données
                                            $conn->close();
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input style="padding: 1px;
    font-size: 16px;
    background-color: rgb(5, 20, 137);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
    height: 30px;
    width: 200px;" type="submit" value="Mettre à jour">
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
