(function ($) {
    "use strict";

    //Navbar Sticky 
    $(window).scroll(function () {
        if ($(this).scrollTop() > 40) {
            $('.navbar').addClass('sticky-top');
        } else {
            $('.navbar').removeClass('sticky-top');
        }
    });
    
    // Liste déroulante lors du survol de la souris
    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });
    
    
    //button pour remonter
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Témoignages
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        items: 1,
        dots: false,
        nav : true,
        loop: true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ]
    });
    
})(jQuery);





// barre de recherche

/*barre de recherche*/










function ouvrirpage(){
    var a = document.getElementById("myInput").value;

    if(a === "avoir des informations supplémentaires" ){
        window.open("contact.html#con");
    }
    if(a === "avoir accès" ){
        window.open("contact.html#con");
    }
    if(a === "sécuriser le système d’information de votre SSII" ){
        window.open("support.html#télécharger");
    }
    if(a === "protéger votre système" ){
        window.open("support.html#télécharger");
    }
    if(a === "modèles et marques" ){
        window.open("support.html#télécharger");
    }
    if(a === "détecter une panne" ){
        window.open("support.html#télécharger");
    }
    if(a === "maîtrise des techniques de reparation" ){
        window.open("support.html#télécharger");
    }
    if(a === "principales missions" ){
        window.open("support.html#télécharger");
    }
    
    if(a === "rapport" ){
        window.open("support.html#télécharger");
    }
    if(a === "obtenir des informations à partir des données" ){
        window.open("support.html#télécharger");
    }
    if(a === "secteur d'intervention" ){
        window.open("support.html#télécharger");
    }
    if(a === "financement" ){
        window.open("support.html#télécharger");
    }
    if(a === "mission" ){
        window.open("présentation.html");
    }
    if(a === "historique" ){
        window.open("présentation.html");
    }
    if(a === "equipe" ){
        window.open("présentation.html");
    }
    if(a === "réseaux informatique" ){
        window.open("prestation.html");
        
    }
    if(a === "télécommunication" ){
        window.open("prestation.html");
        
    }
    if(a === "genie logiciel" ){
        window.open("prestation.html");
        
    }
    if(a === "intelligence économique" ){
        window.open("prestation.html");
        
    }
    if(a === "technologie internet" ){
        window.open("prestation.html");
        
    }
    if(a === "sécurité des SI" ){
        window.open("prestation.html");
        
    }
    if(a === "certifications" ){
        window.open("Formation.html");
        
    }
    if(a === "séminaires" ){
        window.open("Formation.html");
        
    }
    if(a === "conférences" ){
        window.open("Formation.html");
        
    }
    if(a === "quelles sont vos formations de base ?" ){
        window.open("Formation.html");
        
    }
    if(a === "nationaux" ){
        window.open("partenaire.html");
        
    }
    if(a === "inter nationaux" ){
        window.open("partenaire.html");
        
    }
    if(a === "contact"){
        window.open("index.html")
    }
    if(a === "s'inscrire"){
        window.open("s'inscrire.htmll")
    }
    if(a === "quelles sont les pièces à fournir pour integrer l'entreprise ?"){
        window.open("contact.html")
    }
    if(a === "Les certifications"){
        window.open("Formation.html")
    }
    if(a === "le prix des salaires"){
        window.open("prestation.html")
    }
    
    if(a === "acteurs"){
        window.open("présentation.html")
    }
    if(a === "succès"){
        window.open("Formation.html")
    }
    
    if(a === "objectifs"){
        window.open("prestation.html")
    }
    if(a === "comment excéller"){
        window.open("prestation.html")
    }
    
    if(a === "balance"){
        window.open("prestation.html")
    }
    
    
    
    if(a === "divers"){
        window.open("contact.html")
    }
    
    if(a === "les différentes tâches"){
        window.open("prestation.html")
    }
    
    
    
}




const backToTopButton = document.querySelector("#back-to-top-btn");

window.addEventListener("scroll", scrollFunction);

function scrollFunction() {
  if (window.pageYOffset > 300) { // montrer le bouton pour remonter
    if(!backToTopButton.classList.contains("btnEntrance")) {
      backToTopButton.classList.remove("btnExit");
      backToTopButton.classList.add("btnEntrance");
      backToTopButton.style.display = "block";
    }
  }
  else { // cacher le bouton pour remonter
    if(backToTopButton.classList.contains("btnEntrance")) {
      backToTopButton.classList.remove("btnEntrance");
      backToTopButton.classList.add("btnExit");
      setTimeout(function() {
        backToTopButton.style.display = "none";
      }, 250);
    }
  }
}

backToTopButton.addEventListener("click", smoothScrollBackToTop);

// function backToTop() {
//   window.scrollTo(0, 0);
// }

function smoothScrollBackToTop() {
  const targetPosition = 0;
  const startPosition = window.pageYOffset;
  const distance = targetPosition - startPosition;
  const duration = 750;
  let start = null;
  
  window.requestAnimationFrame(step);

  function step(timestamp) {
    if (!start) start = timestamp;
    const progress = timestamp - start;
    window.scrollTo(0, easeInOutCubic(progress, startPosition, distance, duration));
    if (progress < duration) window.requestAnimationFrame(step);
  }
}

function easeInOutCubic(t, b, c, d) {
	t /= d/2;
	if (t < 1) return c/2*t*t*t + b;
	t -= 2;
	return c/2*(t*t*t + 2) + b;
};