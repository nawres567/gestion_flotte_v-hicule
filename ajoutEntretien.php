<?php
// Vérification de la soumission du formulaire
// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclusion du fichier de configuration de la base de données
    include 'config.php';

    // Récupération des données du formulaire
    $vehicule = $_POST['vehicule'];
    $nom_conducteur = $_POST['nom_conducteur'];
    $tache_entretien = $_POST['tache_entretien'];
    $etat_tache = $_POST['etat_tache'];
    $date_entretien = $_POST['date_entretien'];
    $nom_technicien = $_POST['nom_technicien'];

    // Récupération du libellé du véhicule
    $sql_label = "SELECT label FROM llx_product WHERE rowid = ?";
    $stmt_label = $conn->prepare($sql_label);
    $stmt_label->bind_param("i", $vehicule);
    $stmt_label->execute();
    $stmt_label->bind_result($label);
    $stmt_label->fetch();
    $stmt_label->close();

    // Préparation de la requête SQL pour insérer les données
    $sql = "INSERT INTO llx_product_extrafields (fk_object, nom, tache, etat, date, conducteur, technicien) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Préparation de la requête SQL sécurisée
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        trigger_error($conn->error, E_USER_ERROR);
    }

    // Liaison des paramètres et exécution de la requête
    $stmt->bind_param("issssss", $vehicule, $label, $tache_entretien, $etat_tache, $date_entretien, $nom_conducteur, $nom_technicien);
    
    // Exécution de la requête
    if ($stmt->execute()) {
        echo "Entretien ajouté avec succès.";
        // Redirection vers listEntretien.php
        header("Location: listEntretien.php");
        exit();
    } else {
        echo "Erreur lors de l'ajout de l'entretien: " . $stmt->error;
    }

    // Fermeture de la connexion
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
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/ajoutC.css">
    <style>
        /* Ajouter ce style pour réduire l'espace entre les labels et les inputs */
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
    <title>AutoFlotte</title>
    
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
            <li  class="active">
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
                    <h1>Maintenance</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Maintenance</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="listEntretien.php">List Entretiens</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Ajouter Entretien</h3>
        </div>
        <form id="addMaintenanceForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div class="form-row">
        <div class="form-group">
            <label for="vehicule">Véhicule:</label>
            <select name="vehicule" class="form-control" required>
                <!-- Options chargées depuis la base de données -->
                <?php
                include 'config.php';
                $sql = "SELECT rowid, label FROM llx_product";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['rowid'] . "'>" . $row['label'] . "</option>";
                    }
                } else {
                    echo "<option disabled>Aucun véhicule disponible</option>";
                }
                $conn->close();
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="nom_conducteur">Nom du conducteur:</label>
            <select name="nom_conducteur" class="form-control" required>
                <!-- Options chargées depuis la base de données -->
                <?php
                include 'config.php';
                $sql = "SELECT rowid, lastname FROM llx_user WHERE entity = 2";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['lastname'] . "'>" . $row['lastname'] . "</option>";
                    }
                } else {
                    echo "<option disabled>Aucun conducteur trouvé</option>";
                }
                $conn->close();
                ?>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="tache_entretien">Tâche d'entretien:</label>
            <input type="text" name="tache_entretien" class="form-control" placeholder="Tâche d'entretien" required>
        </div>
        <div class="form-group">
            <label for="etat_tache">État de la tâche:</label>
            <select name="etat_tache" class="form-control" required>
                <option value="à faire">À faire</option>
                <option value="en cours">En cours</option>
                <option value="terminé">Terminé</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="date_entretien">Date de l'entretien:</label>
            <input type="date" name="date_entretien" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nom_technicien">Nom du technicien:</label>
            <input type="text" name="nom_technicien" class="form-control" placeholder="Nom du technicien" required>
        </div>
    </div>
    <div class="form-row">
        <button type="submit" class="btn-submit">Ajouter Entretien</button>
    </div>
</form>

</body>
</html>