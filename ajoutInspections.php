<?php
include 'config.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $nom_vehicule = $_POST['nom_vehicule'];
    $formulaire_inspection = $_POST['formulaire_inspection'];
    $date_inspection = $_POST['date_inspection'];
    $nom_conducteur = $_POST['nom_conducteur'];
    $etat = $_POST['etat'];

    // Prepare SQL statement for inserting inspection data
    $sql_inspection = "INSERT INTO llx_product_lang (fk_product, label, description, date_inspection, nom_conducteur, etat) 
                      VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_inspection = $conn->prepare($sql_inspection);
    if ($stmt_inspection === false) {
        trigger_error($conn->error, E_USER_ERROR);
    }

    // Bind parameters and execute the inspection query
    $stmt_inspection->bind_param("isssis", $nom_vehicule, $nom_vehicule, $formulaire_inspection, $date_inspection, $nom_conducteur, $etat);

    // Execute the inspection query
    if ($stmt_inspection->execute()) {
        echo "Inspection ajoutée avec succès.";
        // Redirect to the inspections page
        header("Location: inspections.php");
        exit();
    } else {
        echo "Erreur lors de l'ajout de l'inspection : " . $stmt_inspection->error;
    }

    // Close the statement and the database connection
    $stmt_inspection->close();
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
            <li >
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
            <li >
                <a href="listEntretien.php">
                    <i class='bx bxs-wrench'></i>
                    <span class="text">Maintenance</span>
                </a>
            </li>
			<li class="active">
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
                    <h1>Inspections</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Inspections</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="inspections.php">List Inspections</a>
                        </li>
                    </ul>
                </div>
            </div>
              
            <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Ajouter Inspection</h3>
                </div>
                <form id="addInspectionForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom_vehicule">Nom du Véhicule:</label>
                            <select name="nom_vehicule" id="nom_vehicule" required>
                                <?php
                                // Fetch vehicles from database
                                $sql = "SELECT rowid, label FROM llx_product";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['rowid']) . "'>" . htmlspecialchars($row['label']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Aucun véhicule trouvé</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="formulaire_inspection">Formulaire d'Inspection:</label>
                            <input type="text" name="formulaire_inspection" class="form-control" placeholder="Formulaire d'inspection" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_inspection">Date d'Inspection:</label>
                            <input type="date" name="date_inspection" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nom_conducteur">Nom du Conducteur:</label>
                            <select name="nom_conducteur" id="nom_conducteur" required>
                                <?php
                                // Fetch drivers from database (example)
                                $sql = "SELECT rowid, lastname FROM llx_user WHERE entity = 2";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['rowid']) . "'>" . htmlspecialchars($row['lastname']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Aucun conducteur trouvé</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="etat">État:</label>
                        <select name="etat" id="etat" required>
                            <option value="Complétée">Complétée</option>
                            <option value="à faire">à faire</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <button type="submit" class="btn-submit">Ajouter Inspection</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>