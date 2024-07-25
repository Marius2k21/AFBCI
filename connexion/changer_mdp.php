<?php
// Inclusion de la connexion à la base de données
include("connexion.php");

// Récupération des paramètres de la requête GET
$token = $_GET['token'];
$id_admin = $_GET['id_admin'];

// Gestion du formulaire de changement de mot de passe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $id_admin = $_POST['id_admin'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification de la confirmation du mot de passe
    if ($new_password == $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Mise à jour du mot de passe dans la base de données
        $sql = "UPDATE administrateur SET mdp_admin='$hashed_password' WHERE id_admin='$id_admin'";
        if ($conn->query($sql) === TRUE) {
            $success_message = "Mot de passe mis à jour avec succès!";
            ?>
            <script>
                // Rediriger après 5 secondes
                setTimeout(function() {
                    window.location.href = "login.php";
                }, 5000); // 5000 millisecondes équivaut à 5 secondes
            </script>
            <?php
        } else {
            $error_message = "Erreur: " . $conn->error;
        }
    } else {
        $error_message = "Les mots de passe ne sont pas identiques.";
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/logo1.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer le mot de passe</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-primary p-5 m-3 form-container">
                <h2 class="card-title text-center mb-4">Changer le mot de passe</h2>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <input type="hidden" name="id_admin" value="<?php echo htmlspecialchars($id_admin); ?>">
                    <div class="mb-3">
                        <input name="new_password" required autofocus type="password" class="form-control py-3" id="new_password" placeholder="Entrez le nouveau mot de passe">
                    </div>
                    <div class="mb-3">
                        <input name="confirm_password" required type="password" class="form-control py-3" id="confirm_password" placeholder="Confirmer le mot de passe">
                    </div>
                    <button type="submit" class="btn btn-secondary w-100 py-3">Changer</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
