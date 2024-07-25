<?php
// Importation des classes PHPMailer nécessaires
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Chargement de l'autoloader de Composer
require __DIR__ . '/vendor/autoload.php';

include("connexion.php"); // Inclure le fichier de connexion à la base de données

// Vérifie si la méthode de la requête HTTP est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_admin = $_POST['email_admin']; // Récupération de l'email de l'administrateur depuis le formulaire

    // Préparation de la requête SQL pour vérifier l'existence de l'email dans la base de données
    $sql = "SELECT id_admin FROM administrateur WHERE email_admin=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email_admin);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérification si l'email existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Récupération des données de l'administrateur
        $id_admin = $row['id_admin']; // ID de l'administrateur
        $token = bin2hex(random_bytes(50)); // Génération d'un token de réinitialisation aléatoire
        $reset_link = "http://localhost/AFBCI/changer_mdp.php?token=$token&id_admin=$id_admin"; // Lien de réinitialisation du mot de passe

        // Envoi de l'email de réinitialisation du mot de passe
        sendEmail($email_admin, 'Demande de reinitialisation de mot de passe', '
        <body style="font-family: Arial, sans-serif;">
        <div style="max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;">
            <h2 style="text-align: center; color: #8CC641;">Association des femmes balayeuses de Côte d\'Ivoire</h2>
            <p>Bonjour,</p>
            <p>Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
            <p style="text-align: center;">
                <a href="'.$reset_link.'" style="background-color: #8CC641; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Réinitialiser le mot de passe</a>
            </p>
            <p>Si vous n\'avez pas demandé cette réinitialisation, veuillez ignorer cet e-mail. Votre mot de passe restera inchangé.</p>
            <p>Merci,</p>
            <p>L\'équipe de l\'Association des femmes balayeuses de Côte d\'Ivoire</p>
        </div>
        </body>
        ');

        // Message de succès et redirection
        $success_message = "Un lien de réinitialisation a été envoyé à votre adresse email.";
        ?>
        <script>
            // Rediriger après 5 secondes
            setTimeout(function() {
                window.location.href = "login.php";
            }, 5000); // 5000 millisecondes équivaut à 5 secondes
        </script>
        <?php
    } else {
        $error_message = "Erreur: Email non trouvé."; // Message d'erreur si l'email n'est pas trouvé
    }
}

// Fermeture de la connexion à la base de données
$conn->close();

// Fonction pour envoyer un email
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true); // Crée une instance de PHPMailer
    try {
        $mail->isSMTP(); // Utiliser le protocole SMTP
        $mail->Host = 'smtp.gmail.com'; // Adresse du serveur SMTP
        $mail->SMTPAuth = true; // Activer l'authentification SMTP
        $mail->Username = 'iuainscandpay@gmail.com'; // Nom d'utilisateur SMTP
        $mail->Password = 'inhk wmyw miyf vykl'; // Mot de passe SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Activer le cryptage TLS
        $mail->Port = 587; // Port TCP à utiliser

        $mail->setFrom('iuainscandpay@gmail.com', 'IUA'); // Définir l'adresse de l'expéditeur
        $mail->addAddress($to); // Ajouter un destinataire

        $mail->isHTML(true); // Définir le format de l'email en HTML
        $mail->Subject = $subject; // Définir le sujet de l'email
        $mail->Body    = $body; // Définir le corps de l'email

        $mail->send(); // Envoyer l'email
    } catch (Exception $e) {
        $error_message = "Le message ne peut être envoyé. Erreur d'envoi: {$mail->ErrorInfo}"; // Message d'erreur en cas d'échec de l'envoi
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/logo1.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
       .form-container {
            border-radius: 15px;
        }
    </style>
</head>
<body>
    
    <div class="container">
    <div class="row justify-content-center align-items-center vh-100">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="bg-primary p-5 m-3 form-container">
                <h2 class="card-title text-center mb-4">Réinitialiser le mot de passe</h2>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="email_admin" class="form-label ">Email</label>
                        <input name="email_admin" required autofocus type="email" class="form-control py-3" id="email_admin" placeholder="Entrez votre email de récupération">
                    </div>
                    <button type="submit" class="btn btn-secondary w-100 py-3">Envoyer le lien de réinitialisation</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
