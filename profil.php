<?php
session_start();
include 'config.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: profil.php");
    exit();
}

// Récupère les informations de l'utilisateur depuis la base de données
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM llx_user WHERE rowid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Gérer le cas où aucun utilisateur n'est trouvé (ceci est un cas de sécurité, normalement ne devrait pas arriver si la session est gérée correctement)
    echo "Erreur: Utilisateur non trouvé.";
    exit();
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

    <title>AutoFlotte</title>
    <style>
        /* Styles for profile container */
        .container {
            max-width: 600px;
            margin: 20px 150px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .profile-item {
            display: flex;
           
            margin-bottom: 10px;
        }

        .profile-item label {
            font-weight: bold;
            margin-right: 10px;
        }

        .profile-item p {
            margin: 0;
        }

        .container img {
            max-width: 50%;
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
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
            <li>
                <a href="dashbord.php">
                    <i class='bx bxs-dashboard' ></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="vehicule.php">
                    <i class='bx bxs-car' ></i>
                    <span class="text">Véhicules</span>
                </a>
            </li>
            <li>
                <a href="listConducteurs.php">
                    <i class='bx bxs-group' ></i>
                    <span class="text">Conducteurs</span>
                </a>
            </li>
            <li>
                <a href="carburant.php">
                    <i class='bx bxs-gas-pump'></i>
                    <span class="text">Suivi du carburant </span>
                </a>
            </li>
            <li>
                <a href="listEntretien.php">
                    <i class='bx bxs-wrench' ></i>
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
                    <i class='bx bxs-cog' ></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle' ></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu' ></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="#">
                <div class="form-input">
                    
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell' ></i>
                <span class="num">8</span>
            </a>
            <a href="profil.php" class="profile">
                <img src="img/people.webp">
            </a>
        </nav>
        <!-- NAVBAR -->
        <main>
            <div class="head-title">
                <div class="left">
                </div>
            </div>
            <div class="container">
                <h2>Profil de <?php echo htmlspecialchars($user['login']); ?></h2>
                <div>
                    <p><img src="uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Photo de profil"></p>
                    <div class="profile-item">
                        <label>Civilite:</label>
                        <p><?php echo htmlspecialchars($user['gender']); ?></p>
                    </div>
                    <div class="profile-item">
                        <label>Nom complet:</label>
                        <p><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></p>
                    </div>
                    <div class="profile-item">
                        <label>Adresse:</label>
                        <p><?php echo htmlspecialchars($user['address']); ?></p>
                    </div>
                    <div class="profile-item">
                        <label>Téléphone:</label>
                        <p><?php echo htmlspecialchars($user['user_mobile']); ?></p>
                    </div>
                    <div class="profile-item">
                        <label>Email:</label>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div class="profile-item">
                        <label>Code Postal:</label>
                        <p><?php echo htmlspecialchars($user['zip']); ?></p>
                    </div>
                    <div class="profile-item">
                        <label>Date de Naissance:</label>
                        <p><?php echo htmlspecialchars($user['birth']); ?></p>
                    </div>
                    <div class="profile-item">
                        <label>Ville:</label>
                        <p><?php echo htmlspecialchars($user['lang']); ?></p>
                    </div>
                    <!-- Assurez-vous que le chemin 'uploads/' correspond au répertoire où vous stockez les images -->
                    <!-- Ajoutez d'autres informations que vous souhaitez afficher -->
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    <script src="script.js"></script>
</body>
</html>
