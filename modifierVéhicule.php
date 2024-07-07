<?php
// Include the database connection file
include 'config.php';

// Check if the id parameter is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to fetch vehicle details by id
    $sql = "SELECT rowid, ref, label, DATE(datec) AS datec, description, price, note FROM llx_product WHERE rowid='$id'";
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
    $vehicle_name = $_POST['vehicle_name'];
    $vehicle_year = $_POST['vehicle_year'];
    $vehicle_description = $_POST['vehicle_description'];
    $vehicle_price = $_POST['vehicle_price'];
    $vehicle_model = $_POST['vehicle_model'];

    // SQL query to update vehicle details in llx_product table
    $sql = "UPDATE llx_product SET 
            label='$vehicle_name', 
            datec='$vehicle_year', 
            description='$vehicle_description', 
            price='$vehicle_price', 
            note='$vehicle_model'
            WHERE rowid='$id'";

    if ($conn->query($sql) === TRUE) {
        // Redirect to vehicule.php after successful update
        header("Location: vehicule.php");
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
                    <form id="editVehicleForm" action="modifierVéhicule.php?id=<?php echo $vehicle['rowid']; ?>" method="POST">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <label for="vehicle_name">Nom:</label>
                                        <input type="text" name="vehicle_name" value="<?php echo $vehicle['label']; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="vehicle_year">Date:</label>
                                        <input type="date" name="vehicle_year" value="<?php echo $vehicle['datec']; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="vehicle_description">Description:</label>
                                        <input type="textarea" style="height:80px" name="vehicle_description" value="<?php echo $vehicle['description']; ?>"></input>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="vehicle_price">Prix:</label>
                                        <input type="number" name="vehicle_price" value="<?php echo $vehicle['price']; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="vehicle_model">Modèle:</label>
                                        <input type="text" name="vehicle_model" value="<?php echo $vehicle['note']; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button style="padding: 10px 20px; width:200px; color: white;" type="submit">Mettre à jour Véhicule</button>
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
