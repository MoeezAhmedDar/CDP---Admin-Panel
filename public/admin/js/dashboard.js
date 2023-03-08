 $(document).ready(function() {
     $('#report-carousel').owlCarousel({
         loop: false,
         navigation: false,
         dots: false,
         items: 3,
         responsive: {
             0: {
                 items: 1
             },

             580: {
                 items: 2
             },

             768: {
                 items: 3
             }
         }
     })
 });

 $(document).ready(function() {
     $('#progress-carousel').owlCarousel({
         loop: false,
         navigation: true,
         dots: false,
         items: 4,

         responsiveClass:true,
         responsive:{
             0:{
                 items:1,
                 nav:true
             },
             600:{
                 items:2,
                 nav:false
             },
             1000:{
                 items:3,
                 nav:true,
                 loop:false
             },
             1500:{
                 items:4,
                 nav:true,
                 loop:false
             }
         }
     })
 });
