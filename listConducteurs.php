<?php
// Include the database connection file
include 'config.php';
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
                            <a class="active" href="#">Accueil</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Liste des conducteurs</h3>
                        <button class="btn-add-driver" id="btn-add-driver" style="padding: 10px 20px; width:200px; color: white;background-color:#007bff;"><a href="ajoutConducteur.php" style="color: white;">Ajouter Conducteur</a></button>
                    </div>
                    <table>
                     <!-- Supprimez la colonne Genre et Adresse -->
<thead>
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Image</th> <!-- Ajoutez cette ligne pour la colonne de la photo -->
        <th>Véhicule</th>
        <!-- Réorganisez selon votre besoin -->
    </tr>
</thead>
<tbody>
<?php
// Sélectionnez tous les conducteurs depuis la table llx_user avec entity=2
$sql = "SELECT rowid, login, lastname, firstname, user_mobile, email, photo, personal_email FROM llx_user WHERE entity=2";
$result = $conn->query($sql);

// Vérifiez s'il y a des enregistrements
if ($result->num_rows > 0) {
    // Affichez les données de chaque ligne
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['lastname'] . "</td>";
        echo "<td>" . $row['firstname'] . "</td>";
        echo "<td>" . $row['user_mobile'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td><img src='uploads/" . $row['photo'] . "' style='width: 50px; height: 50px; border-radius: 50%; object-fit: cover;'></td>";
        // Ajoutez cette ligne pour afficher la photo
        echo "<td>" . $row['personal_email'] . "</td>";
        echo "<td>";
        echo " <a style='color:jaune;' href='modifierConducteur.php?id=" . $row['rowid'] . "' class='btn-action'><i class='bx bx-edit'></i> </a>  ";
        echo " <a style='color: red;' href='supprimerConducteur.php?id=" . $row['rowid'] . "' class='btn-action' onclick='return confirmDelete()'><i class='bx bx-trash'></i> </a>";
        echo " <a style='color: green;' href='detailsConducteur.php?id=" . $row['rowid'] . "' class='btn-action'><i class='bx bx-info-circle'> </i> </a>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Aucun conducteur trouvé.</td></tr>";
}

// Fermez la connexion à la base de données
$conn->close();
?>
</tbody>
  
                        
                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    <script>
    function confirmDelete() {
        return confirm("Êtes-vous sûr de vouloir supprimer ce conducteur ?");
    }
    </script>

    <script src="script.js"></script>
</body>
</html>
