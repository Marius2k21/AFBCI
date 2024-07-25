<?php
session_start();
include("../connexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_admin = $_POST['email_admin'];
    $password = $_POST['mdp_admin'];

    // Préparation et exécution de la requête SQL pour vérifier l'existence de l'email
    $sql = "SELECT id_admin, mdp_admin, photo_admin FROM administrateur WHERE email_admin=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email_admin);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $id_admin = $row['id_admin'];
        $hashed_password = $row['mdp_admin'];
        $photo_admin = $row['photo_admin'];

        // Vérification du mot de passe
        if (password_verify($password, $hashed_password)) {
            // Stocker les informations de l'administrateur dans la session
            $_SESSION['id_admin'] = $id_admin;
            $_SESSION['photo_admin'] = $photo_admin;

            // Redirection vers le tableau de bord
            header("Location: dash_admin.php");
            exit();
        } else {
            $error_message = "Email ou mot de passe incorrect.";
        }
    } else {
        $error_message = "Email ou mot de passe incorrect.";
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../../img/logo.jpg" type="image/x-icon">
    <style>
        .form-container {
            border-radius: 15px;
        }
        .forgot-password {
            text-align: right;
        }
        .logo {
            display: block;
            margin: 0 auto;
            width: 100px; 
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-primary p-5 m-3 form-container">
                    <a href="../../index.php" class="text-center mb-4">
                        <img src="../../img/logo.jpg" alt="Logo du projet" class="logo mb-3">
                    </a>
                    <h2 class="text-center text-light mb-4">Connexion</h2>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <input type="email" name="email_admin" class="form-control bg-light border-0 py-3" placeholder="Votre E-mail" required="required">
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="password" name="mdp_admin" class="form-control bg-light border-0 py-3" placeholder="Mot de passe" required="required">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-secondary w-100 py-3" type="submit">Se Connecter</button>
                            </div>
                            <div class="col-12 forgot-password">
                                <a href="mdp_oublié.php" class="text-info">Mot de passe oublié ?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
