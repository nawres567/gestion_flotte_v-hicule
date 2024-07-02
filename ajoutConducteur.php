<?php
// Include the database connection file
include 'config.php';

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = $_POST['gender'];
    $lastname = $_POST['lastname'];
    $uk_user_login= $_POST['firstname'];
    $address = $_POST['address'];
    $user_mobile = $_POST['user_mobile'];
    $email = $_POST['email'];
    
    // SQL query to insert data into llx_user table
    $sql = "INSERT INTO llx_user (login,gender, lastname, firstname, address, user_mobile, email) 
            VALUES ('$uk_user_login','$gender', '$lastname', '$uk_user_login', '$address', '$user_mobile', '$email')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to listConducteurs.php after successful insertion
        header("Location: listConducteurs.php");
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
                <a href="#">
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
                    <h1>Conducteurs</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Conducteurs</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="listConducteurs.php">List Conducteurs</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Ajouter Conducteur</h3>
                    </div>
                    <form id="addDriverForm" action="ajoutConducteur.php" method="POST">
                        <table>
                            <tbody>
                                <tr>
                                <td>
                                        <label for="lastname">Nom:</label>
                                        <input type="text" name="lastname" placeholder="Nom du conducteur" required>
                                    </td>
                                    
                                </tr>
                                <tr>
                                <td>
                                        <label for="firstname">Prénom:</label>
                                        <input type="text" name="firstname" placeholder="Prénom du conducteur" required>
                                    </td>
                                
                                </tr>
                                <tr>
                                <td>
                                        <label for="gender">Genre:</label>
                                        <select  style=" padding: 12px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
  
    transition: border-color 0.3s, box-shadow 0.3s;
    width: 200px;
    height: 45px;" name="gender" required>
                                            <option value="M">Masculin</option>
                                            <option value="F">Féminin</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="address">Adresse:</label>
                                        <input type="text" name="address" placeholder="Adresse du conducteur">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="user_mobile">Mobile:</label>
                                        <input type="number" name="user_mobile" placeholder="Numéro de téléphone du conducteur" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="email">Email:</label>
                                        <input  style=" padding: 12px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 6px;
    transition: border-color 0.3s, box-shadow 0.3s;
    width: 350px;
    height: 35px;"  type="email" name="email" placeholder="Email du conducteur" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button style="padding: 10px 20px; width:190px; color: white;" type="submit">Ajouter Conducteur</button>
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
