$(document).ready(function(){
    $('.partner-logos').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2800,
        speed: 2800,
        arrows: false,
        dots: false,
        pauseOnHover: true,
        variableWidth: true,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 3
            }
        }]
    });
    $(document).ready(function(){ 
     $(".partner-logos").css('visibility', 'visible'); 
     $(".partner-logos").hide().fadeIn(800);
  });
});

