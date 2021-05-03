(function($) {
	$(document).ready(function() {
		"use strict";
		
});
	    $(document).click(function(e) {
		    if (!$(e.target).is('.panel-body')) {
		      $('.collapse').collapse('hide');      
		    }
		    });

		 jQuery(window).scroll(function(){
	          var scroll = $(window).scrollTop();
	          if (scroll >= 100) {
	              $("#main-menu").addClass("sticky");
	          } else {
	              $("#main-menu").removeClass("sticky");
	          }

	          });
		jQuery(document).ready(function( $ ) {	
				$('.portfolio-popup').magnificPopup({
			    type: 'image',
			    removalDelay: 300,
			    mainClass: 'mfp-fade',
			    gallery: {
			      enabled: true
			    },
			    zoom: {
			      enabled: false,
			      duration: 300,
			      easing: 'ease-in-out',
			      opener: function(openerElement) {
			        return openerElement.is('img') ? openerElement : openerElement.find('img');
			      }
			    }

				});
				$(".performers-carousel").owlCarousel({
				        
				        pagination: true,
				        autoPlay:true,
						responsive: {
					      0:{
					          items:1
					      },
					      600:{
					          items:2
					      },
					      1000:{
					          items:4
					      }    
					    }
				    });

				});	


				  $('.popup-youtube').magnificPopup({
				         disableOn: 700,
				         type: 'iframe',
				         mainClass: 'mfp-fade',
				         removalDelay: 160,
				         preloader: false,
				         fixedContentPos: false
				       });

				  var o = $(".album"),
			        n = audiojs.create(o, {
			            trackEnded: function() {
			                var s = $(".playlist li.playing").next();
			                s.length || (s = $(".playlist li").first()), s.addClass("playing").siblings().removeClass("playing"), audio1.load($(".as-link", s).attr("data-src")), audio1.play()
			            }
			        })[0],
			        r = $(".playlist li .as-link").attr("data-src");
			    $(".playlist li ").first().addClass("pause"), n.load(r), $(".playlist li").on("click", function() {
			        return "playing" == $(this).attr("class") ? ($(this).addClass("pause"), n.playPause()) : ($(this).addClass("playing").removeClass("pause").siblings().removeClass("playing").removeClass("pause"), n.load($(".as-link", this).attr("data-src")), n.play()), !1
			    }),	
			     $(".toggle-lyrics").on("click", function() {
			        return $(this).closest(".playlist li").find(".block-lyrics").slideToggle(), $(this).toggleClass("selected"), !1
			    })

    
				$(window).on('load', function(){
				    var $container = $('.portfolioContainer');
				    $container.isotope({
				        filter: '*',
				        animationOptions: {
				            duration: 750,
				            easing: 'linear',
				            queue: false
				        }
				    });

$('.portfolioFilter a').click(function(){
               $('.portfolioFilter .current').removeClass('current');
               $(this).addClass('current');
               $(".portfolioContainer .hide").addClass("portfolio-item");
               $(".portfolioContainer .portfolio-item").removeClass("hide");
               var selector = $(this).attr('data-filter');
               if(selector != "*")
               {
                   $(".portfolioContainer .categories:not( "
                   +selector+") .portfolio-item").addClass('hide');
                   $(".portfolioContainer .categories:not( "
                   +selector+") .hide").removeClass('portfolio-item');

               }
               $('.portfolio-item .portfolio-popup').magnificPopup({
                   type: 'image',
                   removalDelay: 300,
                   mainClass: 'mfp-fade',
                   gallery: {
                       enabled: true
                   },
                   zoom: {
                       enabled: false,
                       duration: 300,
                       easing: 'ease-in-out',
                       opener: function(openerElement) {
                           return openerElement.is('img') ? openerElement : openerElement.find('img');
                       }
                   }
               });

               $container.isotope({
                   filter: selector,
                   animationOptions: {
                       duration: 750,
                       easing: 'linear',
                       queue: false
                   }
                });
                return false;
           });
 }); 
		 

})(jQuery);
