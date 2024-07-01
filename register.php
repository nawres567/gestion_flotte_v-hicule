<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if the username already exists
    $check_sql = "SELECT * FROM llx_user WHERE login = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username already exists. Please choose another one.";
    } else {
        $sql = "INSERT INTO llx_user (login, pass) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Error registering user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2></h2>
        
        <form action="register.php" method="post">
        <img   src="img/véhicule.jpg" alt="Description de l'image">
            <div>
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username"  placeholder="Nom d'utilisateur" required>
            </div>
            <div>
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password"  placeholder="Mot de passe" required>
            </div>
            <div>
                <button type="submit">Register</button>
            </div>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        </form>
        <footer>
            <p></p>
        </footer>
    </div>
</body>
</html>
