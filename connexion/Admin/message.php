<?php
include("../connexion.php");
session_start(); // Démarre une nouvelle session ou reprend une session existante // Démarre une nouvelle session ou reprend une session existante

// Vérifie si l'utilisateur est authentifié en vérifiant l'existence de l'ID du service client dans la session
if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit();
}

// Importation des classes PHPMailer nécessaires
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Inclure la bibliothèque PHPMailer



$id_admin = $_SESSION['id_admin'];

// Préparation et exécution de la requête SQL pour obtenir les informations de l'administrateur
$sql = "SELECT email_admin, nom_admin, prenom_admin, photo_admin FROM administrateur WHERE id_admin=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_admin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();
} else {
    echo "Erreur: Impossible de récupérer les informations de l'administrateur.";
    exit();
}

$message = ''; // Initialisation du message de feedback

// Vérifie si la méthode de la requête HTTP est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_message_id'])) {
        $message_id = $_POST['delete_message_id']; // Récupération de l'ID du message à supprimer

        // Préparation de la requête SQL pour supprimer le message
        $sql = "DELETE FROM message WHERE id = '$message_id'";
        if ($conn->query($sql) === TRUE) {
            $message = "Message supprimé avec succès!"; // Message de succès en cas de suppression réussie
        } else {
            $message = "Erreur lors de la suppression: " . $conn->error; // Message d'erreur en cas d'échec de la suppression
        }
    } elseif (isset($_POST['message_id'])) {
        $message_id = $_POST['message_id']; // Récupération de l'ID du message à répondre
        $recipient_email = $_POST['recipient_email']; // Récupération de l'email du destinataire
        $response = $_POST['response']; // Récupération de la réponse à envoyer

        $mail = new PHPMailer(true);
        try {
            // Configuration SMTP pour l'envoi de l'email
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'iuainscandpay@gmail.com';
            $mail->Password = 'inhk wmyw miyf vykl';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Paramètres de l'email
            $mail->setFrom('iuainscandpay@gmail.com', 'AFBCI');
            $mail->addAddress($recipient_email);
            $mail->isHTML(true);
            $mail->Subject = 'Reponse a votre message';
            $mail->Body = '
                <body style="font-family: Poppins, Arial, sans-serif">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <table class="table table-bordered" style="background-color: #ffffff; margin-top: 20px; max-width: 600px; margin: auto;">
                                    <tr>
                                        <td class="header" style="background-color: #8CC641; padding: 40px; text-align: center; color: white; font-size: 24px;">
                                            Association des femmes balayeuses de Côte d\'Ivoire
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="body" style="padding: 40px; text-align: left; font-size: 16px; line-height: 1.6;">
                                            Cher Utilisateur,
                                            <br><br>
                                            Voici la réponse à votre message :
                                            <br><br>
                                            <b>' . nl2br(htmlspecialchars($response)) . '</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="footer" style="background-color: #8CC641; padding: 40px; text-align: center; color: white; font-size: 14px;">
                                            Copyright &copy; 2024 | Société AFBCI
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </body>';

            $mail->send(); // Envoi de l'email

            // Préparation de la requête SQL pour supprimer le message après réponse
            $sql = "DELETE FROM message WHERE id = '$message_id'";
            if ($conn->query($sql) === TRUE) {
                $message = "Réponse envoyée avec succès!"; // Message de succès en cas d'envoi réussi
            } else {
                $message = "Erreur lors de la suppression: " . $conn->error; // Message d'erreur en cas d'échec de la suppression
            }
        } catch (Exception $e) {
            $message = "Le message ne peut être envoyé. Erreur d'envoi: {$mail->ErrorInfo}"; // Message d'erreur en cas d'échec de l'envoi de l'email
        }
    }
}

// Pagination
$messages_per_page = 8; // Nombre de messages par page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Page actuelle, par défaut 1
$start = ($page > 1) ? ($page * $messages_per_page) - $messages_per_page : 0; // Calcul de l'offset pour SQL

// Préparation de la requête SQL pour récupérer les messages avec pagination
$sql = "SELECT * FROM message LIMIT $start, $messages_per_page";
$result = $conn->query($sql);

// Préparation de la requête SQL pour compter le nombre total de messages
$total_sql = "SELECT COUNT(*) FROM message";
$total_result = $conn->query($total_sql);
$total_messages = $total_result->fetch_row()[0]; // Nombre total de messages
$total_pages = ceil($total_messages / $messages_per_page); // Calcul du nombre total de pages

$conn->close(); // Fermeture de la connexion à la base de données
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Liste des membres</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="../../img/hero.jpg" rel="icon" >
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">  
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/css/swiper.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Oswald:500" rel="stylesheet">
    <script>!function(e){"undefined"==typeof module?this.charming=e:module.exports=e}(function(e,n){"use strict";n=n||{};var t=n.tagName||"span",o=null!=n.classPrefix?n.classPrefix:"char",r=1,a=function(e){for(var n=e.parentNode,a=e.nodeValue,c=a.length,l=-1;++l<c;){var d=document.createElement(t);o&&(d.className=o+r,r++),d.appendChild(document.createTextNode(a[l])),n.insertBefore(d,e)}n.removeChild(e)};return function c(e){for(var n=[].slice.call(e.childNodes),t=n.length,o=-1;++o<t;)c(n[o]);e.nodeType===Node.TEXT_NODE&&a(e)}(e),e});
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/js/swiper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.2/TweenMax.min.js"></script>
</head>

<style>
    .profile-photo {
        border-radius: 50%;
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
    
</style>

<body>
    <div class="container-fluid  d-none d-lg-block" style="background-color: rgb(118, 189, 12);">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-lg-start">
                    <div class="d-inline-flex align-items-center">
                        <a style="color: #ff5e00;" class="text-body py-2 px-3 border-end border-white" href="https://www.facebook.com/profile.php?id=100086961294763" target="_blank"> 
                            <i class="fab fa-facebook-f text-white"></i>
                        </a>
                        <a class="text-body py-2 px-3 border-end border-white" href="https://twitter.com/AAfbci82469" target="_blank">
                            <i class="fab fa-twitter text-white"></i>
                        </a>
                        <a class="text-body py-2 px-3 border-end border-white" href="https://ci.linkedin.com/in/association-afbci-91a699270" target="_blank">
                            <i class="fab fa-linkedin-in text-white"></i>
                        </a>
                        <a class="text-body py-2 ps-3" href="https://www.youtube.com/" target="_blank">
                            <i class="fab fa-youtube text-white"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-center text-lg-end mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center">
                        <a href="../logout.php" class="text-dark py-2 pe-3 border-start border-white px-3 text-white" style="display: flex;"> Déconnexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm px-5 py-3 py-lg-0">
        <a href="index_admin.php" class="navbar-brand p-0">
            <h1 class="m-0 text-uppercase text-white"><img style="width: 90px; height: auto;" src="../../img/logo.jpg" alt=""></h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0 pe-4 border-end border-5 border-success">
                <a href="index_admin.php" class="nav-item nav-link">Accueil</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Ajouter</a>
                    <div class="dropdown-menu m-0">
                        <a href="ajout_membre.php" class="dropdown-item">Membres</a>
                        <a href="ajout_materiel.php" class="dropdown-item">Matériels</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="service.php" class="nav-link dropdown-toggle " data-bs-toggle="dropdown">Liste</a>
                    <div class="dropdown-menu m-0">
                        <a href="liste_membre.php" class="dropdown-item">Membres</a>
                        <a href="liste_materiel.php" class="dropdown-item">Matériels</a>
                    </div>
                </div>
                <a href="message.php" class="nav-item nav-link active">Messages</a>
            </div>
            <a href="#">
                <img src="<?php echo htmlspecialchars($admin['photo_admin']); ?>" alt="Photo de Profil" class="profile-photo  m-2 mt-0 mb-0">
            </a>
        </div>
    </nav>

    <div class="container-fluid  py-5 bg-hero" style="margin-bottom: 90px; background-color: rgb(118, 189, 12);">
        <div class="container py-5">
            <div class="row justify-content-start">
                <div class="col-lg-8 text-center text-lg-start">
                    <strong class="display-1 text-warning">Liste des membres</strong>
                    <p class="fs-4 text-warning mb-4">Gérer les membres de l'association des femmes balayeuses de Côte d'Ivoire.</p>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Tableau start -->

    <div class="container my-5">
    <h2>Notifications - Messages des Utilisateurs</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    <div class="search-box">
        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un message...">
    </div>
    <table class="table table-striped" id="messagesTable">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Sujet</th>
                <th>Message</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nom_prenom']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['sujet']); ?></td>
                <td style="max-width: 300px; word-wrap: break-word; overflow-wrap: break-word;"> <?php echo htmlspecialchars($row['message']); ?></td>
                <td>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#replyModal" data-email="<?php echo htmlspecialchars($row['email']); ?>" data-id="<?php echo htmlspecialchars($row['id']); ?>">Répondre</button>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="delete_message_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>



    <!-- Modal Répondre -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="replyForm" action="" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="replyModalLabel">Répondre au Message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="message_id" id="message_id">
                        <div class="mb-3">
                            <label for="recipient-email" class="form-label">Email du Destinataire</label>
                            <input type="email" class="form-control" id="recipient-email" name="recipient_email" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="response" class="form-label">Votre Réponse</label>
                            <textarea class="form-control" id="response" name="response" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Envoyer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    


<div class="container-fluid bg-dark bg-footer text-light py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6" style="margin-right: 100px;">
                    <h4 class="text-white">Contactez-nous</h4>
                    <hr class="w-25 text-secondary mb-4" style="opacity: 1;">
                    <p class="mb-2" style="color: white;"><i class="fa fa-map-marker-alt text-white me-3"></i>Riviera 4 les jardins d'eden</p>
                    <p class="mb-2"style="color: white;"><i class="fa fa-envelope text-white me-3"></i>associationfemmesbalayeusesci@gmail.com</p>
                    <p class="mb-0"style="color: white;"><i class="fa fa-phone-alt text-white me-3"></i>+225 07 08 63 76 04</p>
                    <p class="mb-0"style="color: white;"><i class="fa fa-phone-alt text-white me-3"></i>+225 05 05 14 62 40</p>
                    <p class="mb-0"style="color: white;"><i class="fa fa-phone-alt text-white me-3"></i>+225 07 09 30 45 39</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white">Liens rapides</h4>
                    <hr class="w-25 text-secondary mb-4" style="opacity: 1;">
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-light mb-2" href="ajout_membre.php"><i class="fa fa-angle-right me-2"></i>Ajout de membres</a>
                        <a class="text-light mb-2" href="ajout_materiel.php"><i class="fa fa-angle-right me-2"></i>Enregistrer matériels</a>
                        <a class="text-light mb-2" href="liste_membre.php"><i class="fa fa-angle-right me-2"></i>Membres</a>
                        <a class="text-light" href="liste_materiel.php"><i class="fa fa-angle-right me-2"></i>Matériels</a>
                        <a class="text-light" href="message.php"><i class="fa fa-angle-right me-2"></i>Messages</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white">Nos Comptes</h4>
                    <hr class="w-25 text-secondary mb-4" style="opacity: 1;">
                    <form action="">
                        <div class="input-group"></div>
                    </form>
                    <h6 class="text-white mt-4 mb-3">Suivez-nous</h6>
                    <div class="d-flex">
                        <a class="btn btn-lg  btn-lg-square rounded-circle me-2" style="background-color: rgb(118, 189, 12);" href="https://twitter.com/AAfbci82469"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-lg  btn-lg-square rounded-circle me-2" style="background-color: rgb(118, 189, 12);" href="https://www.facebook.com/profile.php?id=100086961294763" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-lg  btn-lg-square rounded-circle me-2" style="background-color: rgb(118, 189, 12);" href="https://ci.linkedin.com/in/association-afbci-91a699270"><i class="fab fa-linkedin-in"></i></a>
                        <a class="btn btn-lg  btn-lg-square rounded-circle" style="background-color: rgb(118, 189, 12);" href="https://www.youtube.com/"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid  text-dark py-4" style="background-color: rgb(118, 189, 12);">
        <div class="container">
            <div class="row g-0">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-md-0">Copyright &copy; <a class="text-dark fw-bold" href="#">Société AFBCI</a>. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
    
    <script>
    var replyModal = document.getElementById('replyModal');
    replyModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var email = button.getAttribute('data-email');
        var messageId = button.getAttribute('data-id');

        var modalEmailInput = replyModal.querySelector('#recipient-email');
        var modalMessageIdInput = replyModal.querySelector('#message_id');

        modalEmailInput.value = email;
        modalMessageIdInput.value = messageId;
    });

    // Search Functionality
    document.getElementById('searchInput').addEventListener('keyup', function () {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();
        table = document.getElementById('messagesTable');
        tr = table.getElementsByTagName('tr');

        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = 'none';
            td = tr[i].getElementsByTagName('td');
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                        break;
                    }
                }
            }
        }
    });
</script>

</body>
</html>
