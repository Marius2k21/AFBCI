<?php
session_start();
include("../connexion.php");

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit();
}

$id_admin = $_SESSION['id_admin'];

// Préparation et exécution de la requête SQL pour obtenir les informations de l'administrateur
$sql = "SELECT * FROM administrateur WHERE id_admin=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_admin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();
} else {
    echo "Veullez vous connecter.";
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST['identifiant'];
    $nom_admin = $_POST['nom_admin'];
    $prenom_admin = $_POST['prenom_admin'];
    $email_admin = $_POST['email_admin'];
    $numero_admin = $_POST['numero_admin'];

    // Vérification et traitement de l'image
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
            $_SESSION['error_message'] = "Type de fichier non autorisé.";
            header("Location: index_admin.php");
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
            header("Location: index_admin.php");
            exit();
        }
    } else {
        $photo_admin = $admin['photo_admin']; // Conserver l'ancienne photo si aucune nouvelle n'est téléchargée
    }

    // Mise à jour des informations de l'administrateur dans la base de données
    $stmt = $conn->prepare("UPDATE administrateur SET identifiant=?, nom_admin=?, prenom_admin=?, email_admin=?, numero_admin=?, photo_admin=? WHERE id_admin=?");
    $stmt->bind_param("ssssssi", $identifiant, $nom_admin, $prenom_admin, $email_admin, $numero_admin, $photo_admin, $id_admin);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Profil mis à jour avec succès.";
        header("Location: index_admin.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Erreur: " . $stmt->error;
    }

    $stmt->close();
}




// Suppression d'un matériel
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Supprimer la photo du serveur
    $sql = "SELECT photo_materiel FROM materiels WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $materiel = $result->fetch_assoc();
    if ($materiel && !empty($materiel['photo_materiel']) && file_exists($materiel['photo_materiel'])) {
        unlink($materiel['photo_materiel']);
    }


    // Supprimer le matériel de la base de données
    $sql = "DELETE FROM materiels WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    $_SESSION['success_message'] = "Matériel supprimé avec succès.";

    // Redirection pour éviter la resoumission du formulaire
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Récupération des matériels depuis la base de données
$sql = "SELECT * FROM materiels";
$result = $conn->query($sql);



$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Ajout de matériels</title>
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
            width: 90px;
            height: 90px;
            object-fit: cover;
            cursor: pointer;

        }
        .form-container {
            border-radius: 15px;
            
        }
        .form{
            margin-bottom: 200px;
        }
        .gallery {
            display: flex;
            flex-wrap: wrap;
            
        }
        .gallery-item {
            margin: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            width: calc(33.333% - 20px);
            box-sizing: border-box;
            
        }
        .gallery-item img {
            width: 100%;
            height: auto;
            cursor:pointer;
        }
        .modal .modal-body img {
            width: 100%;
        }

    </style>

    
</head>
<body>

<div class="container-fluid  d-none d-lg-block" style="background-color: rgb(118, 189, 12);">
        <div class="container" >
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


    <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm px-5 py-3 py-lg-0" >
        <a href="index_admin.php" class="navbar-brand p-0">
            <h1 class="m-0 text-uppercase text-white"><img style="width: 90px; height: auto;" src="../../img/logo.jpg" alt=""></h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0 pe-4 border-end border-5 border-success">
                <a href="index_admin.php" class="nav-item nav-link ">Accueil</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Ajouter</a>
                    <div class="dropdown-menu m-0">
                        <a href="ajout_membre.php" class="dropdown-item">Membres</a>
                        <a href="ajout_materiel.php" class="dropdown-item">Materiels</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="service.php" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Liste</a>
                    <div class="dropdown-menu m-0">
                        <a href="liste_membre.php" class="dropdown-item">Membres</a>
                        <a href="liste_materiel.php" class="dropdown-item">Materiels</a>
                    </div>
                </div>
                
                <a href="message.php"  class="nav-item nav-link">Messages</a>
                
            </div>
            <img src="<?php echo htmlspecialchars($admin['photo_admin']); ?>" alt="Photo de Profil" class="profile-photo  m-2 mt-0 mb-0">
        </div>
    </nav>

    <!-- Modal du profile -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Modifier le profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateProfileForm" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="profileIdentifiant" class="form-label">Identifiant</label>
                            <input type="text" class="form-control" id="profileIdentifiant" name="identifiant" value="<?php echo htmlspecialchars($admin['identifiant']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="profileNom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="profileNom" name="nom_admin" value="<?php echo htmlspecialchars($admin['nom_admin']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="profilePrenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="profilePrenom" name="prenom_admin" value="<?php echo htmlspecialchars($admin['prenom_admin']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="profileEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="profileEmail" name="email_admin" value="<?php echo htmlspecialchars($admin['email_admin']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="profileNumero" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="profileNumero" name="numero_admin" value="<?php echo htmlspecialchars($admin['numero_admin']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="profilePhoto" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="profilePhoto" name="photo">
                        </div>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.querySelector('.profile-photo').addEventListener('click', function() {
        var profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
        profileModal.show();
    });
    </script>



    <div class="container-fluid py-5 bg-hero" style="margin-bottom: 90px; background-color: rgb(118, 189, 12);">
        <div class="container py-5">
            <div class="row justify-content-start">
                <div class="col-lg-8 text-center text-lg-start">
                    <strong class="display-1 text-warning">Liste des matériels</strong>
                    <p class="fs-4 text-warning mb-4">Vous pouvez Voir la liste des matériels de l'association sur cette page.</p>
                    <div class="pt-2">
                        <!-- Optionally, add buttons or additional information here if needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <h2 class="text-center">Liste des Matériels</h2>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success_message']); ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <div class="gallery">
            <?php while ($materiel = $result->fetch_assoc()): ?>
                <div class="gallery-item">
                    <img src="<?php echo $materiel['photo_materiel']; ?>" alt="<?php echo htmlspecialchars($materiel['nom']); ?>" data-toggle="modal" data-target="#materielModal<?php echo $materiel['id']; ?>">
                    <h5><?php echo htmlspecialchars($materiel['nom']); ?></h5>
                    <p><?php echo htmlspecialchars($materiel['description']); ?></p>
                    <form method="post" class="mt-2">
                        <input type="hidden" name="delete_id" value="<?php echo $materiel['id']; ?>">
                        <button type="submit" class="btn btn-danger" >Supprimer</button>
                    </form>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="materielModal<?php echo $materiel['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="materielModalLabel<?php echo $materiel['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="materielModalLabel<?php echo $materiel['id']; ?>"><?php echo htmlspecialchars($materiel['nom']); ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <img src="<?php echo $materiel['photo_materiel']; ?>" alt="<?php echo htmlspecialchars($materiel['nom']); ?>">
                                <p><strong>Description:</strong> <?php echo htmlspecialchars($materiel['description']); ?></p>
                                <p><strong>Marque:</strong> <?php echo htmlspecialchars($materiel['marque']); ?></p>
                                <p><strong>Modèle:</strong> <?php echo htmlspecialchars($materiel['modele']); ?></p>
                                <p><strong>Date d'achat:</strong> <?php echo htmlspecialchars($materiel['date_achat']); ?></p>
                                <p><strong>Prix d'achat:</strong> <?php echo htmlspecialchars($materiel['prix_achat']); ?></p>
                                <p><strong>État:</strong> <?php echo htmlspecialchars($materiel['etat']); ?></p>
                                <p><strong>Fournisseur:</strong> <?php echo htmlspecialchars($materiel['fournisseur']); ?></p>
                                <p><strong>Contact Fournisseur:</strong> <?php echo htmlspecialchars($materiel['contact_fournisseur']); ?></p>
                                <p><strong>Quantité:</strong> <?php echo htmlspecialchars($materiel['quantite']); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    
    <div class="container-fluid bg-dark bg-footer text-light py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6" style="margin-right: 100px;">
                    <h4 class="text-white">Contactez-nous</h4>
                    <hr class="w-25 text-secondary mb-4" style="opacity: 1;">
                    <p class="mb-2" style="color: white;"><i class="fa fa-map-marker-alt text-white me-3"></i>Riviera 4 les jardins d'eden</p>
                    <p class="mb-2"style="color: white;"><i class="fa fa-envelope text-white me-3"></i>associationfemmesbalayeusesci@gmail.com</p>
                    <p class="mb-0"style="color: white;"><i class="fa fa-phone-alt text-white me-3"></i>+225 07 08 63 76 04</p>
                    <p class="mb-0"style="color: white;"><i class="fa fa-phone-alt text-white me-3"></i>+225 05 05 14 62 40</p>
                    <p class="mb-0"style="color: white;"><i class="fa fa-phone-alt text-white me-3"></i>+225 07 09 30 45 39</p>
                </div>
                <div class="col-lg-3 col-md-6" >
                    <h4 class="text-white">Liens rapides</h4>
                    <hr class="w-25 text-secondary mb-4" style="opacity: 1;">
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-light mb-2" href="ajout_membre.php"><i class="fa fa-angle-right me-2"></i>Ajout de membres</a>
                        <a class="text-light mb-2" href="ajout_materiel.php"><i class="fa fa-angle-right me-2"></i>Enregistrer matériels</a>
                        <a class="text-light mb-2" href="liste_membre.php"><i class="fa fa-angle-right me-2"></i>Membres</a>
                        <a class="text-light" href="liste_materiel.php"><i class="fa fa-angle-right me-2"></i>Materiels</a>
                        <a class="text-light" href="message.php"><i class="fa fa-angle-right me-2"></i>Messages</a>

                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white">Nos Comptes</h4>
                    <hr class="w-25 text-secondary mb-4" style="opacity: 1;">
                    <form action="">
                        <div class="input-group">
                            
                        </div>
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
        <div class="container" >
            <div class="row g-0">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-md-0">Copyright &copy; <a class="text-dark fw-bold" href="#">Société AFBCI</a>. Tous droits réservés.</p>
                </div>
                
            </div>
        </div>
    </div>

    <a href="#" class="btn btn-lg btn-secondary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
       
    </body>
</html>
</body>
</html>
