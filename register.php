<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas. Veuillez réessayer.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if the username already exists
        $check_sql = "SELECT * FROM llx_user WHERE login = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Nom d'utilisateur déjà existant. Veuillez en choisir un autre.";
        } else {
            $sql = "INSERT INTO llx_user (login, pass) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Erreur lors de l'enregistrement de l'utilisateur.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container"  style="height:70%">
        <h2>Inscription</h2>
        
        <form action="register.php" method="post">
            <img src="img/véhicule.jpg" alt="Description de l'image">
            <div class="form-group">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Nom d'utilisateur" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirmer le mot de passe" required>
            </div>
            <div>
                <button type="submit" >S'inscrire</button>
                <p>  pour accéder à la page de connexion     <a  style="" href="login.php">Login</a></p>
            </div>
            <?php if (isset($error)) { echo "<p class='text-danger'>$error</p>"; } ?>
        </form>
        <footer>
            <p></p>
        </footer>
    </div>
</body>
</html>
