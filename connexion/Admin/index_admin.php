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

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Admin Accueil</title>
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
        .info-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .info-box h4 {
            color: #76BD0C;
        }
        .info-box ul {
            list-style-type: none;
            padding-left: 0;
        }
        .info-box ul li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .info-box ul li:last-child {
            border-bottom: none;
        }
        .info-box ul li i {
            color: #76BD0C;
            margin-right: 10px;
        }
        
        
    </style>

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
            <a href="index_admin.php" class="nav-item nav-link active">Accueil</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Ajouter</a>
                <div class="dropdown-menu m-0">
                    <a href="ajout_membre.php" class="dropdown-item">Membres</a>
                    <a href="ajout_materiel.php" class="dropdown-item">Materiels</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="service.php" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Liste</a>
                <div class="dropdown-menu m-0">
                    <a href="liste_membre.php" class="dropdown-item">Membres</a>
                    <a href="liste_materiel.php" class="dropdown-item">Materiels</a>
                </div>
            </div>
            
            <a href="message.php"  class="nav-item nav-link">Messages</a>
            
        </div>
        <a href="#">
            <img src="<?php echo htmlspecialchars($admin['photo_admin']); ?>" alt="Photo de Profil" class="profile-photo  m-2 mt-0 mb-0">
        </a>
    </div>
</nav>


    <div class="container-fluid  py-5 bg-hero" style="margin-bottom: 90px; background-color: rgb(118, 189, 12); " >
        <div class="container py-5">
            <div class="row justify-content-start">
                <div class="col-lg-8 text-center text-lg-start">
                    <strong class="display-1 text-warning">Bienvenue, Administrateur <?php echo htmlspecialchars( $admin['nom_admin']); ?></strong>
                    <p class="fs-4 text-warning mb-4">Gerer les tâches liées à l'association des femmes balayeuses de Côte d'Ivoire. 
                    </p>
                    <div class="pt-2">
                        
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Section Liste des tâches -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="info-box">
                    <h2>Les tâches que vous pouvez éffectuer</h2>
                    <h4>Ajout des membres de l'association</h4>
                    <p>Pour ajouter un membres à votre organisation :</p>
                    <ul>
                        <li> Avoir les informations nécessaires comme le nom,prénom, role, numéo de téléphone... sur ce membre</li>
                        <li> remplir le formulaire pour ajouter un membre</li>
                        <li> Puis Appuyer valider pour confirmer l'ajout</li>
                    </ul>
                    <h4>Ajouter des matériels</h4>
                    <p>Vous pouvez enregistrer l'ensemble de vos matériels pour avoir une tracabilité sur vos équipements</p>
                    
                    <h4>Gérer les membres et matériels</h4>
                    <p>Vous pouvez :</p>
                    <ul>
                        <li>Supprimer des membres; au cas où ils ne font plus partir de l'association par exemple</li>
                        <li>Modifier les informations d'un membre</li>
                        <li>Supprimer également des matériels...</li>
                    </ul>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Section Liste des tâches End -->

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
