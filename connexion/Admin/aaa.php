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

// Pagination
$elements_par_page = 10; // Nombre d'éléments par page
$page_courante = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page_courante - 1) * $elements_par_page;

// Rechercher des membres
$recherche = isset($_POST['recherche']) ? $_POST['recherche'] : '';

// Préparation et exécution de la requête SQL pour obtenir les membres
$sql = "SELECT * FROM membre WHERE nom LIKE ? OR prenom LIKE ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$param_recherche = "%$recherche%";
$stmt->bind_param("ssii", $param_recherche, $param_recherche, $elements_par_page, $offset);
$stmt->execute();
$resultat = $stmt->get_result();

// Récupérer le nombre total de membres pour la pagination
$sql_count = "SELECT COUNT(*) as total FROM membre WHERE nom LIKE ? OR prenom LIKE ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("ss", $param_recherche, $param_recherche);
$stmt_count->execute();
$resultat_count = $stmt_count->get_result();
$total_elements = $resultat_count->fetch_assoc()['total'];
$total_pages = ceil($total_elements / $elements_par_page);

// Modification
if (isset($_POST['action']) && $_POST['action'] === 'modifier') {
    $id_membre = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $role = $_POST['role'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $email = $_POST['email'];

    // Préparation de la requête SQL pour mettre à jour le membre
    $sql = "UPDATE membre SET nom=?, prenom=?, role=?, telephone=?, adresse=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nom, $prenom, $role, $telephone, $adresse, $email, $id_membre);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Membre modifié avec succès.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la modification du membre.";
    }

    // Redirection après la modification
    header("Location: liste_membre.php");
    exit();
}

// Suppression
if (isset($_POST['action']) && $_POST['action'] === 'supprimer') {
    $id_membre = $_POST['id_membre'];
    
    // Préparation de la requête SQL pour supprimer le membre
    $sql = "DELETE FROM membre WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_membre);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Membre supprimé avec succès.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la suppression du membre.";
    }

    // Redirection après la suppression
    header("Location: liste_membre.php");
    exit();
}

// Fermeture de la connexion à la base de données
$conn->close();
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
                    <a href="service.php" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Liste</a>
                    <div class="dropdown-menu m-0">
                        <a href="liste_membre.php" class="dropdown-item">Membres</a>
                        <a href="liste_materiel.php" class="dropdown-item">Matériels</a>
                    </div>
                </div>
                <a href="message.php" class="nav-item nav-link">Messages</a>
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

    <div class="container py-5">
        <h2 class="mb-4">Liste des membres</h2>

        <!-- Barre de recherche -->
        <form method="post" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="recherche" placeholder="Rechercher par nom ou prénom" value="<?php echo htmlspecialchars($recherche); ?>">
                <button class="btn btn-primary" type="submit">Rechercher</button>
            </div>
        </form>

        <!-- Tableau des membres -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Rôle</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ligne = $resultat->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ligne['nom']); ?></td>
                    <td><?php echo htmlspecialchars($ligne['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($ligne['role']); ?></td>
                    <td><?php echo htmlspecialchars($ligne['telephone']); ?></td>
                    <td><?php echo htmlspecialchars($ligne['adresse']); ?></td>
                    <td><?php echo htmlspecialchars($ligne['email']); ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $ligne['id']; ?>" data-nom="<?php echo htmlspecialchars($ligne['nom']); ?>" data-prenom="<?php echo htmlspecialchars($ligne['prenom']); ?>" data-role="<?php echo htmlspecialchars($ligne['role']); ?>" data-telephone="<?php echo htmlspecialchars($ligne['telephone']); ?>" data-adresse="<?php echo htmlspecialchars($ligne['adresse']); ?>" data-email="<?php echo htmlspecialchars($ligne['email']); ?>">Modifier</button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="<?php echo $ligne['id']; ?>">Supprimer</button>

                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?php if ($page_courante <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page_courante - 1; ?>" aria-label="Précédent">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $page_courante) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php if ($page_courante >= $total_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page_courante + 1; ?>" aria-label="Suivant">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Modal Modifier -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title" id="editModalLabel">Modifier Membre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success_message']); ?></div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
                <form id="editForm" action="" method="post">
                    <input type="hidden" name="id" id="editId">
                    <div class="row g-3">
                        <div class="col-12">
                            <input type="text" class="form-control bg-light border-0 py-3" id="editNom" name="nom" placeholder="Nom" required>
                        </div>
                        <div class="col-12">
                            <input type="text" class="form-control bg-light border-0 py-3" id="editPrenom" name="prenom" placeholder="Prénom" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <input type="text" class="form-control bg-light border-0 py-3" id="editRole" name="role" placeholder="Rôle ou Poste" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <input type="text" class="form-control bg-light border-0 py-3" id="editTelephone" name="telephone" placeholder="Numéro de téléphone">
                        </div>
                        <div class="col-12">
                            <input type="text" class="form-control bg-light border-0 py-3" id="editAdresse" name="adresse" placeholder="Adresse" required>
                        </div>
                        <div class="col-12">
                            <input type="email" class="form-control bg-light border-0 py-3" id="editEmail" name="email" placeholder="Email">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-secondary w-100 py-3" type="submit">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Modal Supprimer -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Supprimer Membre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce membre ?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="post" action="liste_membre.php">
                    <input type="hidden" name="action" value="supprimer">
                    <input type="hidden" name="id_membre" id="deleteId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- JavaScript pour le modal Modifier -->
    <script>
    var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var id = button.getAttribute('data-id');
        var nom = button.getAttribute('data-nom');
        var prenom = button.getAttribute('data-prenom');
        var role = button.getAttribute('data-role');
        var telephone = button.getAttribute('data-telephone');
        var adresse = button.getAttribute('data-adresse');
        var email = button.getAttribute('data-email');

        var modal = editModal.querySelector('#editId');
        var modalNom = editModal.querySelector('#editNom');
        var modalPrenom = editModal.querySelector('#editPrenom');
        var modalRole = editModal.querySelector('#editRole');
        var modalTelephone = editModal.querySelector('#editTelephone');
        var modalAdresse = editModal.querySelector('#editAdresse');
        var modalEmail = editModal.querySelector('#editEmail');

        modal.value = id;
        modalNom.value = nom;
        modalPrenom.value = prenom;
        modalRole.value = role;
        modalTelephone.value = telephone;
        modalAdresse.value = adresse;
        modalEmail.value = email;
    });
</script>

<script>
    // Fonction pour ouvrir le modal et définir l'ID du membre à supprimer
    function openDeleteModal(id) {
        var deleteIdInput = document.getElementById('deleteId');
        deleteIdInput.value = id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Exemple de code pour lier la fonction à un bouton
    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            openDeleteModal(id);
        });
    });
</script>


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

    <a href="#" class="btn btn-lg btn-secondary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
</body>
</html>
