(function ($) {

	'use strict';

	$(document).ready(function() {

    $('.carousel').carousel({
      interval: false,
      keyboard: false,
      wrap: false
    });
    $('.carousel-toggle').on('change', function (event) {
      console.log(event);
      $('.carousel').carousel($(this).data("slide-to"));
    })
    
  });

}(jQuery));
