<?php
session_start();
include("../connexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $identifiant = $_POST['identifiant'];
    $nom_admin = $_POST['nom_admin'];
    $prenom_admin = $_POST['prenom_admin'];
    $role_admin = $_POST['role_admin'];
    $email_admin = $_POST['email_admin'];
    $numero_admin = $_POST['numero_admin'];
    $mdp_admin = $_POST['mdp_admin'];
    $mdp_confirm = $_POST['mdp_confirm'];

    // Vérification que les mots de passe correspondent
    if ($mdp_admin !== $mdp_confirm) {
        $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
        header("Location: inscription.php");
        exit();
    } else {
        $mdp_admin = password_hash($mdp_admin, PASSWORD_DEFAULT);

        // Traitement de l'image
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
                $_SESSION['error_message'] = "Type de fichier non autorisé.";
                header("Location: inscription.php");
                exit();
            }

            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($_FILES['photo']['name']);
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                $photo_admin = $uploadFile;
            } else {
                $_SESSION['error_message'] = "Erreur lors du téléchargement de la photo.";
                header("Location: inscription.php");
                exit();
            }
        } else {
            $photo_admin = null;
        }

        // Insertion des données dans la base de données
        $stmt = $conn->prepare("INSERT INTO administrateur (identifiant, nom_admin, prenom_admin, role_admin, email_admin, numero_admin, mdp_admin, photo_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $identifiant, $nom_admin, $prenom_admin, $role_admin, $email_admin, $numero_admin, $mdp_admin, $photo_admin);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Inscription réussie.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Erreur: " . $stmt->error;
        }

        $stmt->close();

        // Redirection pour éviter la resoumission du formulaire
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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
                    <h2 class="text-center text-light mb-4">Inscription</h2>
                    <!-- Affichage des messages -->
                    <?php
                    if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success_message']); ?></div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <input type="text" class="form-control bg-light border-0 py-3" name="identifiant" placeholder="Identifiant" required="required">
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="text" class="form-control bg-light border-0 py-3" name="nom_admin" placeholder="Nom" required="required">
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="text" class="form-control bg-light border-0 py-3" name="prenom_admin" placeholder="Prénom" required="required">
                            </div>
                            <div class="col-12 col-sm-6">
                                <select class="form-control bg-light border-0 py-3" name="role_admin" required="required">
                                    <option value="" disabled selected>Rôle</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">Utilisateur</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <input type="email" class="form-control bg-light border-0 py-3" name="email_admin" placeholder="Votre E-mail" required="required">
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="password" class="form-control bg-light border-0 py-3" name="mdp_admin" placeholder="Mot de passe" required="required">
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="password" class="form-control bg-light border-0 py-3" name="mdp_confirm" placeholder="Confirmer mot de passe" required="required">
                            </div>
                            <div class="col-12">
                                <input type="tel" class="form-control bg-light border-0 py-3" name="numero_admin" placeholder="Téléphone" required="required">
                            </div>
                            <div class="col-12">
                                <label for="photo" class="form-label text-light">Photo</label>
                                <input type="file" class="form-control bg-light border-0 py-3" id="photo" name="photo" required="required">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-secondary w-100 py-3" type="submit">S'inscrire</button>
                            </div>
                            <div class="col-12 forgot-password">
                                <h4 class="text-light">J'ai déjà un compte</h4>
                                <a href="login.php" class="text-info">Se connecter</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
