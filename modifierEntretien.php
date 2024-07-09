<?php
// Include the database connection file
include 'config.php';

// Initialiser $entretien pour éviter les erreurs si $_GET['id'] n'est pas défini ou ne correspond à aucun enregistrement
$entretien = array();

// Check if an ID is provided to fetch the existing maintenance data
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the maintenance data from the database
    $sql = "SELECT * FROM llx_product_extrafields WHERE rowid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if there is a result
    if ($result->num_rows > 0) {
        $entretien = $result->fetch_assoc();  // Fetch the maintenance record
    } else {
        // Handle case where no record is found
        echo "Aucun enregistrement d'entretien trouvé avec l'ID : " . $id;
        exit; // Stop further execution
    }
    
    $stmt->close();
}

// Handle form submission for updating maintenance data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form
    $id = $_POST['id'];
    $vehicule = $_POST['vehicule'];
    $nom_conducteur = $_POST['nom_conducteur'];
    $tache_entretien = $_POST['tache_entretien'];
    $etat_tache = $_POST['etat_tache'];
    $date_entretien = $_POST['date_entretien'];
    $nom_technicien = $_POST['nom_technicien'];

    // Get the label of the vehicle
    $sql_label = "SELECT label FROM llx_product WHERE rowid = ?";
    $stmt_label = $conn->prepare($sql_label);
    $stmt_label->bind_param("i", $vehicule);
    $stmt_label->execute();
    $stmt_label->bind_result($label);
    $stmt_label->fetch();
    $stmt_label->close();

    // Update the maintenance record in the database
    $sql_update = "UPDATE llx_product_extrafields 
                   SET fk_object = ?, nom = ?, tache = ?, etat = ?, date = ?, conducteur = ?, technicien = ?
                   WHERE rowid = ?";
    $stmt_update = $conn->prepare($sql_update);
    if ($stmt_update === false) {
        trigger_error($conn->error, E_USER_ERROR);
    }
    $stmt_update->bind_param("issssssi", $vehicule, $label, $tache_entretien, $etat_tache, $date_entretien, $nom_conducteur, $nom_technicien, $id);
    
    if ($stmt_update->execute()) {
        echo "Entretien modifié avec succès.";
        header("Location: listEntretien.php");
        exit();
    } else {
        echo "Erreur lors de la modification de l'entretien: " . $stmt_update->error;
    }

    $stmt_update->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/ajoutC.css">
    <style>
        .form-table td.label, .form-table td.input {
            padding: 5px;
        }

        .form-table td.label {
            text-align: left;
            padding-right: 200px;
        }

        .form-table td.input {
            text-align: left;
            padding-left: 30px;
        }
    </style>
    <title>Modifier Entretien</title>
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
            <li class="active">
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
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Modifier Entretien</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Maintenance</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Modifier Entretien</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Modifier l'entretien</h3>
                    </div>
                    <form id="editMaintenanceForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <input type="hidden" name="id" value="<?php echo isset($entretien['rowid']) ? $entretien['rowid'] : ''; ?>">
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <td class="label"><label for="vehicule">Véhicule:</label></td>
                                    <td class="input">
                                        <select name="vehicule" style="width: 70%; height: 35px;" required>
                                            <!-- Options chargées depuis la base de données -->
                                            <?php
                                            $sql_vehicule = "SELECT rowid, label FROM llx_product";
                                            $result_vehicule = $conn->query($sql_vehicule);
                                            if ($result_vehicule->num_rows > 0) {
                                                while($row_vehicule = $result_vehicule->fetch_assoc()) {
                                                    $selected = ($row_vehicule['rowid'] == $entretien['fk_object']) ? 'selected' : '';
                                                    echo "<option value='" . $row_vehicule['rowid'] . "' $selected>" . $row_vehicule['label'] . "</option>";
                                                }
                                            } else {
                                                echo "<option disabled>Aucun véhicule disponible</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label"><label for="nom_conducteur">Nom du conducteur:</label></td>
                                    <td class="input">
                                        <select name="nom_conducteur" style="width: 70%; height: 35px;" required>
                                            <!-- Options chargées depuis la base de données -->
                                            <?php
                                            $sql_conducteur = "SELECT rowid, lastname FROM llx_user WHERE entity = 2";
                                            $result_conducteur = $conn->query($sql_conducteur);
                                            if ($result_conducteur->num_rows > 0) {
                                                while($row_conducteur = $result_conducteur->fetch_assoc()) {
                                                    $selected = ($row_conducteur['rowid'] == $entretien['fk_user']) ? 'selected' : '';
                                                    echo "<option value='" . $row_conducteur['rowid'] . "' $selected>" . $row_conducteur['lastname'] . "</option>";
                                                }
                                            } else {
                                                echo "<option disabled>Aucun conducteur disponible</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label"><label for="tache_entretien">Tâche d'entretien:</label></td>
                                    <td class="input"><input type="text" name="tache_entretien" value="<?php echo isset($entretien['nom']) ? $entretien['nom'] : ''; ?>" style="width: 70%; height: 35px;" required></td>
                                </tr>
                                <tr>
    <td class="label"><label for="etat_tache">État de la tâche:</label></td>
    <td class="input">
        <select name="etat_tache" style="width: 70%; height: 35px;" required>
            <option value="En cours" <?php echo isset($entretien['etat']) && $entretien['etat'] == 'En cours' ? 'selected' : ''; ?>>En cours</option>
            <option value="Terminé" <?php echo isset($entretien['etat']) && $entretien['etat'] == 'Terminé' ? 'selected' : ''; ?>>Terminé</option>
            <option value="À faire" <?php echo isset($entretien['etat']) && $entretien['etat'] == 'À faire' ? 'selected' : ''; ?>>À faire</option>
        </select>
    </td>
</tr>
                                <tr>
                                    <td class="label"><label for="date_entretien">Date d'entretien:</label></td>
                                    <td class="input"><input type="date" name="date_entretien" value="<?php echo isset($entretien['date']) ? $entretien['date'] : ''; ?>" style="width: 70%; height: 35px;" required></td>
                                </tr>
                                <tr>
                                    <td class="label"><label for="nom_technicien">Nom du technicien:</label></td>
                                    <td class="input"><input type="text" name="nom_technicien" value="<?php echo isset($entretien['technicien']) ? $entretien['technicien'] : ''; ?>" style="width: 70%; height: 35px;" required></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;">
                                        <button type="submit"  style="width: 50%; height: 40px; background-color: #007bff; color: white; border: none; border-radius: 6px;" >Modifier l'entretien</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </main>
    </section>
</body>
</html>
