<?php
// Vérifie si la méthode de la requête HTTP est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("Connexion/connexion.php");

    // Sécurisation des données entrantes en utilisant la méthode real_escape_string pour éviter les injections SQL
    $nom_mess = $conn->real_escape_string($_POST['nom_prenom']); // Nom de l'expéditeur
    $mail_mess = $conn->real_escape_string($_POST['email']); // Email de l'expéditeur
    $sujet_mess = $conn->real_escape_string($_POST['sujet']); // Sujet du message
    $contenu_mess = $conn->real_escape_string($_POST['message']); // Contenu du message

    // Préparation de la requête SQL pour insérer les données du message dans la base de données
    $sql = "INSERT INTO message (nom_prenom, email, sujet, message) VALUES ('$nom_mess', '$mail_mess', '$sujet_mess', '$contenu_mess')";

    // Exécution de la requête SQL
    if ($conn->query($sql) === TRUE) {
        $success_message =  "Message envoyé avec succès!"; // Stockage d'un message de succès en cas de succès de l'insertion
    } else {
        $error_message = "Erreur : " . $sql . "<br>" . $conn->error; // Stockage d'un message d'erreur en cas d'échec de l'insertion
    }

    // Redirection pour éviter la resoumission du formulaire
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
    // Fermeture de la connexion à la base de données
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>AFBCI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        

    <link href="img/hero.jpg" rel="icon" >

    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">  

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    
    <link href="css/bootstrap.min.css" rel="stylesheet">

    
    <link href="css/style.css" rel="stylesheet">



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/css/swiper.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Oswald:500" rel="stylesheet">
    <script>!function(e){"undefined"==typeof module?this.charming=e:module.exports=e}(function(e,n){"use strict";n=n||{};var t=n.tagName||"span",o=null!=n.classPrefix?n.classPrefix:"char",r=1,a=function(e){for(var n=e.parentNode,a=e.nodeValue,c=a.length,l=-1;++l<c;){var d=document.createElement(t);o&&(d.className=o+r,r++),d.appendChild(document.createTextNode(a[l])),n.insertBefore(d,e)}n.removeChild(e)};return function c(e){for(var n=[].slice.call(e.childNodes),t=n.length,o=-1;++o<t;)c(n[o]);e.nodeType===Node.TEXT_NODE&&a(e)}(e),e});
    </script>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/js/swiper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.2/TweenMax.min.js"></script>
</head>

<body>
    
    <div class="container-fluid  d-none d-lg-block" style="background-color: rgb(118, 189, 12);">
        <div class="container" >
            <div class="row">
                <div class="col-md-6 text-center text-lg-start mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-dark py-2 pe-3 border-end border-white" style="display: flex;"><i class="bi bi-telephone text-white me-2"></i><span> 07 08 63 76 04</span></a>
                        <a class="text-dark py-2 px-3" style="display: flex;"><i class="bi bi-envelope text-white me-2" ></i>associationfemmesbalayeusesci@gmail.com </a>
                    </div>
                </div>
                <div class="col-md-6 text-center text-lg-end">
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
            </div>
        </div>
    </div>


    <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm px-5 py-3 py-lg-0" >
        <a href="index.php" class="navbar-brand p-0">
            <h1 class="m-0 text-uppercase text-white"><img style="width: 90px; height: auto;" src="img/logo.jpg" alt=""></h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0 pe-4 border-end border-5 border-success">
                <a href="index.php" class="nav-item nav-link ">Accueil</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Présentation</a>
                    <div class="dropdown-menu m-0">
                        <a href="présentation.php#Historique" class="dropdown-item">Historique</a>
                        <a href="présentation.php#Mission" class="dropdown-item">Mission</a>
                        <a href="présentation.php#Membres" class="dropdown-item">Membres</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="service.php" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Prestations</a>
                    <div class="dropdown-menu m-0">
                        <a href="prestation.php#Details" class="dropdown-item">Details</a>
                        <a href="prestation.php#Activités" class="dropdown-item">Activités</a>
                    </div>
                </div>
                
                
                
                <a href="contact.php"  class="nav-item nav-link active">Contact</a>
            </div>
                <a href="connexion/code_admin_login.php"  class="nav-item nav-link">Se connecter</a>
                <a href="connexion/code_admin_signup.php"  class="nav-item nav-link">S'inscrire</a>
        </div>
    </nav>


    <div class="container-fluid  py-5 bg-hero" style="margin-bottom: 90px; background-color: rgb(118, 189, 12);" >
        <div class="container py-5">
            <div class="row justify-content-start">
                <div class="col-lg-8 text-center text-lg-start">
                    <strong class="display-1 text-warning">Contactez-nous</strong>
                    <p class="fs-4 text-warning mb-4">N'hésitez pas à nous faire part de vos problèmes pour qu'ensemble, nous éssayons de les résoudres.</p>
                    <div class="pt-2">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5" id="con" >
        <div class="container-fluid py-5">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h1 class="display-5">N’hésitez pas à nous contacter</h1>
                <hr class="w-25 mx-auto text-primary" style="opacity: 1;">
            </div>
            <div class="row g-3 mb-5">
                <div class="col-lg-4 col-md-6 pt-5">
                    <div class="contact-item d-flex flex-column align-items-center justify-content-center text-center pb-5">
                        <div class="contact-icon p-3">
                            <div><i class="fa fa-2x fa-map-marker-alt"style=" color: red;"></i></div>
                        </div>
                        <h4 class="mt-5 text-white">Riviera 4 les jardins d'eden</h4>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 pt-5">
                    <div class="contact-item d-flex flex-column align-items-center justify-content-center text-center pb-5">
                        <div class="contact-icon p-3">
                            <div><i class="fa fa-2x fa-phone" style=" color: red;"></i></div>
                        </div>
                        <h4 class="mt-5 text-white">+225 07 08 63 76 04</h4>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 pt-5">
                    <div class="contact-item d-flex flex-column align-items-center justify-content-center text-center pb-5">
                        <div class="contact-icon p-3">
                            <div><i class="fa fa-2x fa-envelope-open" style=" color: red;"></i></div>
                        </div>
                        <h4 class="mt-5 text-white"><span style="font-size: 20px;"> associationfemmesbalayeusesci@gmail.com </span></h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" style="height: 500px;">
                    <div class="position-relative h-100">

                        <iframe class="position-relative w-100 h-100" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3299.1226089715838!2d-3.9424014889367927!3d5.3277605318434444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc1ed1aaf2cb191%3A0x1784f82f89499992!2sCit%C3%A9%20Eden!5e0!3m2!1sen!2sci!4v1682799772501!5m2!1sen!2sci" 
                        frameborder="0" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" aria-hidden="false" tabindex="0"></iframe>
                    </div>
                </div>
            </div>
            
            <div class="row justify-content-center position-relative" style="border-radius: 15px;">
                <div class="col-lg-8">
                    <div class="bg-primary p-5 m-5 mb-0">
                    <h2 class="text-center text-light mb-4">Envoyer un méssage</h2>
                    <?php
                    if (isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                        <form action="" method="POST">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <input type="text" class="form-control bg-light border-0" required="required" name="nom_prenom" placeholder="Nom et prénom" style="height: 55px;">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="email" class="form-control bg-light border-0" required="required" name="email" placeholder="Votre E-mail" style="height: 55px;">
                                </div>
                                <div class="col-12">
                                    <input type="text" class="form-control bg-light border-0" required="required" name="sujet" placeholder="Sujet" style="height: 55px;">
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control bg-light border-0" rows="5" required="required" name="message"  placeholder="Message"></textarea>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-secondary w-100 py-3" type="submit">Envoyer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    

    
    

    <div class="container-fluid bg-dark bg-footer text-light py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6" style="margin-right: 100px;">
                    <h4 class="text-white">Contactez-nous</h4>
                    <hr class="w-25 text-secondary mb-4" style="opacity: 1;">
                    <p class="mb-4" style="color: white;">pour plus d'informations sur la société, vous pouvez vous pouvez nous contacter directement</p>
                    <p class="mb-2" style="color: white;"><i class="fa fa-map-marker-alt text-white me-3"></i>Angre, rue 105, Côte d'Ivoire</p>
                    <p class="mb-2"style="color: white;"><i class="fa fa-envelope text-white me-3"></i>associationfemmesbalayeusesci@gmail.com</p>
                    <p class="mb-0"style="color: white;"><i class="fa fa-phone-alt text-white me-3"></i>+225 07 08 63 76 04</p>
                    <p class="mb-0"style="color: white;"><i class="fa fa-phone-alt text-white me-3"></i>+225 05 05 14 62 40</p>
                    <p class="mb-0"style="color: white;"><i class="fa fa-phone-alt text-white me-3"></i>+225 07 09 30 45 39</p>
                </div>
                <div class="col-lg-3 col-md-6" >
                    <h4 class="text-white">Nos service</h4>
                    <hr class="w-25 text-secondary mb-4" style="opacity: 1;">
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-light mb-2" href="index.php"><i class="fa fa-angle-right me-2"></i>Accueil</a>
                        <a class="text-light mb-2" href="présentation.php"><i class="fa fa-angle-right me-2"></i>présentation</a>
                        <a class="text-light mb-2" href="prestation.php"><i class="fa fa-angle-right me-2"></i>prestation</a>
                        <a class="text-light" href="contact.php"><i class="fa fa-angle-right me-2"></i>contact</a>
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

    <script src="js/main.js"></script>
    <script>
        // The Slideshow class.
    class Slideshow {
        constructor(el) {
            
            this.DOM = {el: el};
          
            this.config = {
              slideshow: {
                delay: 3000,
                pagination: {
                  duration: 3,
                }
              }
            };
            
            // Set the slideshow
            this.init();
          
        }
        init() {
          
          var self = this;
          
          // Charmed title
          this.DOM.slideTitle = this.DOM.el.querySelectorAll('.slide-title');
          this.DOM.slideTitle.forEach((slideTitle) => {
            charming(slideTitle);
          });
          
          // Set the slider
          this.slideshow = new Swiper (this.DOM.el, {
              
              loop: true,
              autoplay: {
                delay: this.config.slideshow.delay,
                disableOnInteraction: false,
              },
              speed: 500,
              preloadImages: true,
              updateOnImagesReady: true,
              
              // lazy: true,
              // preloadImages: false,
    
              pagination: {
                el: '.slideshow-pagination',
                clickable: true,
                bulletClass: 'slideshow-pagination-item',
                bulletActiveClass: 'active',
                clickableClass: 'slideshow-pagination-clickable',
                modifierClass: 'slideshow-pagination-',
                renderBullet: function (index, className) {
                  
                  var slideIndex = index,
                      number = (index <= 8) ? '0' + (slideIndex + 1) : (slideIndex + 1);
                  
                  var paginationItem = '<span class="slideshow-pagination-item">';
                  paginationItem += '<span class="pagination-number">' + number + '</span>';
                  paginationItem = (index <= 8) ? paginationItem + '<span class="pagination-separator"><span class="pagination-separator-loader"></span></span>' : paginationItem;
                  paginationItem += '</span>';
                
                  return paginationItem;
                  
                },
              },
    
              navigation: {
                nextEl: '.slideshow-navigation-button.next',
                prevEl: '.slideshow-navigation-button.prev',
              },
    
              // And if we need scrollbar
              scrollbar: {
                el: '.swiper-scrollbar',
              },
            
              on: {
                init: function() {
                  self.animate('next');
                },
              }
            
            });
          
            // Init/Bind events.
            this.initEvents();
            
        }
        initEvents() {
            
            this.slideshow.on('paginationUpdate', (swiper, paginationEl) => this.animatePagination(swiper, paginationEl));
            //this.slideshow.on('paginationRender', (swiper, paginationEl) => this.animatePagination());
    
            this.slideshow.on('slideNextTransitionStart', () => this.animate('next'));
            
            this.slideshow.on('slidePrevTransitionStart', () => this.animate('prev'));
                
        }
        animate(direction = 'next') {
          
            // Get the active slide
            this.DOM.activeSlide = this.DOM.el.querySelector('.swiper-slide-active'),
            this.DOM.activeSlideImg = this.DOM.activeSlide.querySelector('.slide-image'),
            this.DOM.activeSlideTitle = this.DOM.activeSlide.querySelector('.slide-title'),
            this.DOM.activeSlideTitleLetters = this.DOM.activeSlideTitle.querySelectorAll('span');
          
            // Reverse if prev  
            this.DOM.activeSlideTitleLetters = direction === "next" ? this.DOM.activeSlideTitleLetters : [].slice.call(this.DOM.activeSlideTitleLetters).reverse();
          
            // Get old slide
            this.DOM.oldSlide = direction === "next" ? this.DOM.el.querySelector('.swiper-slide-prev') : this.DOM.el.querySelector('.swiper-slide-next');
            if (this.DOM.oldSlide) {
              // Get parts
              this.DOM.oldSlideTitle = this.DOM.oldSlide.querySelector('.slide-title'),
              this.DOM.oldSlideTitleLetters = this.DOM.oldSlideTitle.querySelectorAll('span'); 
              // Animate
              this.DOM.oldSlideTitleLetters.forEach((letter,pos) => {
                TweenMax.to(letter, .3, {
                  ease: Quart.easeIn,
                  delay: (this.DOM.oldSlideTitleLetters.length-pos-1)*.04,
                  y: '50%',
                  opacity: 0
                });
              });
            }
          
            // Animate title
            this.DOM.activeSlideTitleLetters.forEach((letter,pos) => {
                        TweenMax.to(letter, .6, {
                            ease: Back.easeOut,
                            delay: pos*.05,
                            startAt: {y: '50%', opacity: 0},
                            y: '0%',
                            opacity: 1
                        });
                    });
          
            // Animate background
            TweenMax.to(this.DOM.activeSlideImg, 1.5, {
                ease: Expo.easeOut,
                startAt: {x: direction === 'next' ? 200 : -200},
                x: 0,
            });
          
            //this.animatePagination()
        
        }
        animatePagination(swiper, paginationEl) {
                
          // Animate pagination
          this.DOM.paginationItemsLoader = paginationEl.querySelectorAll('.pagination-separator-loader');
          this.DOM.activePaginationItem = paginationEl.querySelector('.slideshow-pagination-item.active');
          this.DOM.activePaginationItemLoader = this.DOM.activePaginationItem.querySelector('.pagination-separator-loader');
          
          console.log(swiper.pagination);
          // console.log(swiper.activeIndex);
          
          // Reset and animate
            TweenMax.set(this.DOM.paginationItemsLoader, {scaleX: 0});
            TweenMax.to(this.DOM.activePaginationItemLoader, this.config.slideshow.pagination.duration, {
              startAt: {scaleX: 0},
              scaleX: 1,
            });
          
          
        }
        
    }
    
    const slideshow = new Slideshow(document.querySelector('.slideshow'));
    
    </script>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/isotope.min.js"></script>
<script src="assets/js/owl-carousel.js"></script>
<script src="assets/js/lightbox.js"></script>
<script src="assets/js/tabs.js"></script>
<script src="assets/js/video.js"></script>
<script src="assets/js/slick-slider.js"></script>
<script src="assets/js/custom.js"></script>
</body>

</html>