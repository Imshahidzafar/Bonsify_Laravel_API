$(document).ready(function () {

  $('.mobile_bars').click(function(){
    $('.accordion').slideToggle(1000);
    return false;
  });



      $(".category_slider.owl-carousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        slideBy: 3,
        responsive: {
          0: {
            items: 1,
          },
          600: {
            items: 3,
          },
          1000: {
            items: 4,
          },
        },
      });

      $(".brand_slider.owl-carousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        slideBy: 3,
        responsive: {
          0: {
            items: 2,
          },
          600: {
            items: 4,
          },
          1000: {
            items: 6,
          },
        },
      });
      
      $(".feature.owl-carousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        slideBy: 3,
        responsive: {
          0: {
            items: 1,
          },
          600: {
            items: 2,
          },
          1000: {
            items: 4,
          },
        },
      });
      
      $(".feature_2.owl-carousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        slideBy: 3,
        responsive: {
          0: {
            items: 1,
          },
          600: {
            items: 2,
          },
          1000: {
            items: 4,
          },
        },
      });
      
      $(".category_content.owl-carousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        slideBy: 3,
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 3
          },
          1000: {
            items: 6
          }
        },
      }); 

      $(".make_content.owl-carousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        slideBy: 3,
        responsive: {
          0: {
            items: 2,
          },
          600: {
            items: 4,
          },
          1000: {
            items: 6,
          },
        },
      });

      $(".body_content.owl-carousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        slideBy: 3,
        responsive: {
          0: {
            items: 2,
          },
          600: {
            items: 4,
          },
          1000: {
            items: 6,
          },
        },
      });



});


     $(function() {
  var Accordion = function(el, multiple) {
    this.el = el || {};
    this.multiple = multiple || false;

    // Variables privadas
    var links = this.el.find('.link');
    // Evento
    links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
  }

  Accordion.prototype.dropdown = function(e) {
    var $el = e.data.el;
      $this = $(this),
      $next = $this.next();

    $next.slideToggle();
    $this.parent().toggleClass('open');

    if (!e.data.multiple) {
      $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
    };
  } 

  var accordion = new Accordion($('#accordion'), false);
});



