<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM llx_user WHERE login = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['pass'])) {
            $_SESSION['user_id'] = $user['rowid'];  // Assurez-vous d'utiliser 'rowid' pour l'ID de l'utilisateur
            header("Location: dashbord.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2></h2>
        <form action="login.php" method="post">
        <img   src="img/véhicule.jpg" alt="Description de l'image">
            <div>
                <label for="username">Nom d'utilisateur:</label>
                <input  styles="height:5px;" type="text" id="username" name="username"  placeholder="Nom d'utilisateur" required>
            </div>
            <div>
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password"  placeholder="Mot de passe" required>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        </form>
        <footer>
            <p></p>
        </footer>
    </div>
</body>
</html>
