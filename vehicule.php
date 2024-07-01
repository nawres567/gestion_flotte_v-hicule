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
			<li >
				<a href="dashbord.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
            <li class="active">
				<a href="vehicule.php">
					<i class='bx bxs-car' ></i>
					<span class="text">Véhicules</span>
				</a>
			</li>
            <li>
				
                <a href="#">
                        <i class='bx bxs-group' ></i>
                        <span class="text">Conducteurs</span>
                    </a>
                </li>
                <li>
                <a href="#">
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

<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
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
            <h1>Véhicules</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="#">Véhicules</a>
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
                <h3>Liste des véhicules</h3>
                <i class='bx bx-search'></i>
                <i class='bx bx-filter'></i>
                <button class="btn-add-vehicle"  style="padding: 10px 20px;
    font-size: 14px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
    margin-left: auto;
    margin-right: 20px;">Ajouter Véhicule</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Année</th>
                        <th>Année</th>
                        <th>Modèle</th>
                        <th>Modèle</th>
                        <th>Modèle</th>
                        <th>Modèle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <img style="width:90px;height:60px" src="img/R.jpg">
                            <p>Véhicule1</p>
                        </td>
                        <td>01-10-2021</td>
                        <td><span class="status completed">Completed</span></td>
                    </tr>
                    <tr>
                        <td>
                        <img style="width:90px;height:60px" src="img/R.jpg">
                            <p>Véhicule2</p>
                        </td>
                        <td>01-10-2021</td>
                        <td><span class="status pending">Pending</span></td>
                    </tr>
                    <tr>
                        <td>
                        <img style="width:90px;height:60px" src="img/R.jpg">
                            <p>Véhicule3</p>
                        </td>
                        <td>01-10-2021</td>
                        <td><span class="status process">Process</span></td>
                    </tr>
                    <tr>
                        <td>
                        <img style="width:90px;height:60px" src="img/R.jpg">
                            <p>Véhicule4</p>
                        </td>
                        <td>01-10-2021</td>
                        <td><span class="status pending">Pending</span></td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
</main>

		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	<script src="script.js"></script>
</body>
</html>
