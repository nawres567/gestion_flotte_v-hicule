<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include 'config.php';

// Requête SQL pour compter le nombre de conducteurs où entity=2
$count_sql = "SELECT COUNT(*) as count FROM llx_user WHERE entity=2";
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$conducteurs_count = $count_row['count'];

// Requête SQL pour compter le nombre de véhicules
$count_sql_vehicules = "SELECT COUNT(*) as count FROM llx_product";
$count_result_vehicules = $conn->query($count_sql_vehicules);
$count_row_vehicules = $count_result_vehicules->fetch_assoc();
$vehicules_count = $count_row_vehicules['count'];



$count_sql_entretiens = "SELECT COUNT(*) as count FROM llx_product_extrafields WHERE date >= CURDATE()";
$count_result_entretiens = $conn->query($count_sql_entretiens);
$count_row_entretiens = $count_result_entretiens->fetch_assoc();
$entretiens_count = $count_row_entretiens['count'];



// diagramme 
$sql = "SELECT DATE_FORMAT(datec, '%Y-%m') AS transaction_month, SUM(total) AS total
        FROM llx_product_fournisseur_price
        GROUP BY transaction_month
        ORDER BY transaction_month ASC";

$result = $conn->query($sql);

$months = [];
$totals = [];

if ($result->num_rows > 0) {
    // Parcourir les résultats et stocker les données dans les tableaux PHP
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['transaction_month'];
        $totals[] = $row['total'];
    }
}




// Récupérer le mois, l'année et le jour actuels ou depuis les paramètres GET
$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('n');
$currentYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$currentDay = isset($_GET['day']) ? $_GET['day'] : '';

// Calculer la date actuelle
$currentDate = date('Y-m-d');

// Construire la requête SQL pour récupérer les entretiens à venir
$sql = "SELECT * FROM llx_product_extrafields WHERE DATE(date) >= '$currentDate' ORDER BY date ASC";
$result = $conn->query($sql);

// Créer un tableau associatif des entretiens par date pour faciliter l'affichage dans le calendrier
$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventDate = date('Y-m-d', strtotime($row['date']));
        $events[$eventDate][] = $row; // Regrouper les entretiens par date
    }
}



// Fermer la connexion à la base de données
$conn->close();




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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>AutoFlotte</title>
	 <style>
        /* Styles pour la popup */
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none; /* Caché par défaut */
        }
        .popup h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }
        .popup ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }
        .popup li {
            margin-bottom: 10px;
        }
        .popup .close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: #999;
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
            <li class="active">
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
    <!-- SIDEBAR -->

    <!-- CONTENT -->
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
            <a href="profil.php" class="profile">
                <img src="img/people.webp">
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Accueil</a>
                        </li>
                    </ul>
                </div>
            </div>

            <ul class="box-info">
                <li>
                    <i class='bx bxs-car'></i>
                    <span class="text">
                        <h3><?php echo $vehicules_count; ?></h3>
                        <p><a href="vehicule.php">Véhicules</a></p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3><?php echo $conducteurs_count; ?></h3>
                        <p><a href="listConducteurs.php">Conducteurs</a></p>
                    </span>
                </li>
				<li id="entretiens-popup" class="popup-trigger">
    <i class='bx bxs-wrench'></i>
    <span class="text">
        <h3><?php echo $entretiens_count; ?></h3>
        <p><a href="#">Prochains entretiens</a></p>
    </span>
</li>
            </ul>

            <canvas id="myChart" width="200" height="200"></canvas>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Récupérer les données pour le diagramme depuis PHP
                let months = <?php echo json_encode($months); ?>; // Tableau des mois
                let totals = <?php echo json_encode($totals); ?>; // Tableau des totaux

                // Créer un tableau des noms des mois
                const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

                // Convertir les valeurs des mois en noms des mois
                const monthLabels = months.map(month => {
                    const [year, monthNumber] = month.split('-');
                    return `${monthNames[parseInt(monthNumber) - 1]} ${year}`;
                });

                // Configuration du diagramme
                const ctx = document.getElementById('myChart').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: monthLabels,
                        datasets: [{
                            label: 'Total de carburant par mois',
                            data: totals,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                barPercentage: 8, // Réduire l'espace entre les barres
                                categoryPercentage: 8 // Réduire l'espace entre les catégories
                            },
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                labels: {}
                            }
                        },
                        barThickness: 20 // Ajustez cette valeur pour modifier la taille des barres
                    }
                });
            });
            </script>
            <script src="script.js"></script>
			<div id="popup-entretiens" class="popup">
    <span class="close">&times;</span>
    <h2>Prochains entretiens</h2>
    <ul>
        <?php
        // Affichage des prochains entretiens à partir du tableau $events
        foreach ($events as $eventDate => $eventList) {
            foreach ($eventList as $event) {
                echo "<li>{$event['nom']} le {$event['date']}</li>";
            }
        }
        ?>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const popupTrigger = document.getElementById('entretiens-popup');
    const popup = document.getElementById('popup-entretiens');
    const closeBtn = document.querySelector('#popup-entretiens .close');

    popupTrigger.addEventListener('click', function(event) {
        event.preventDefault();
        popup.style.display = 'block'; // Afficher la popup
    });

    closeBtn.addEventListener('click', function() {
        popup.style.display = 'none'; // Cacher la popup
    });
});
</script>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

</body>
</html>
