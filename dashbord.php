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
			<li class="active">
				<a href="#">
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
            <a href="#">
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
			<a href="#" class="profile">
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
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	
	<script src="script.js"></script>
</body>
</html>
