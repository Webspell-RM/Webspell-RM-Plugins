$(document).ready(function(){
    $('.squad-logos').slick({
        slidesToShow: 6,
      slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1500,
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
     $(".squad-logos").css('visibility', 'visible'); 
     $(".squad-logos").hide().fadeIn(800);
  });

});