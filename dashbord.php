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




//diagramme 
$sql = "SELECT DATE(datec) AS transaction_date, SUM(total) AS total
        FROM llx_product_fournisseur_price
        GROUP BY DATE(datec)
        ORDER BY DATE(datec) ASC";

$result = $conn->query($sql);

$dates = [];
$totals = [];

if ($result->num_rows > 0) {
    // Parcourir les résultats et stocker les données dans les tableaux PHP
    while ($row = $result->fetch_assoc()) {
        $dates[] = $row['transaction_date'];
        $totals[] = $row['total'];
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

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Accueil</a>
						</li>
					</ul>
				</div>
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-car' ></i>
					<span class="text">
					<h3><?php echo $vehicules_count; ?></h3>
						<p><a href="vehicule.php" >Véhicules</a></p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group' ></i>
					<span class="text">
						<h3><?php echo $conducteurs_count; ?></h3>
						<p><a href="listConducteurs.php" >Conducteurs</a></p>
					</span>
				</li>
				<li>
					<i class='bx bxs-dollar-circle' ></i>
					<span class="text">
						<h3>$2543</h3>
						<p>Total Sales</p>
					</span>
				</li>
			</ul>
	
			<canvas id="myChart"  width="200" height="200"></canvas>
			<script>
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les données pour le diagramme depuis PHP
    let dates = <?php echo json_encode($dates); ?>; // Tableau des dates
    let totals = <?php echo json_encode($totals); ?>; // Tableau des totaux

    // Configuration du diagramme
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                label: 'Total de carburant par jour',
                data: totals,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1

            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                       
                    }
                }
            },
            barThickness: 20 // Ajustez cette valeur pour modifier la taille des barres
           
        }
    });
});

</script>
