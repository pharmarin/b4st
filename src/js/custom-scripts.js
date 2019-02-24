(function ($) {

	'use strict';

	$(document).ready(function() {

		// Comments

		$('.commentlist li').addClass('card mb-3');
		$('.comment-reply-link').addClass('btn btn-secondary');

		// Forms

		$('select, input[type=text], input[type=email], input[type=password], textarea').addClass('form-control');
		$('input[type=submit]').addClass('btn btn-primary');

		// Pagination fix for ellipsis

		$('.pagination .dots').addClass('page-link').parent().addClass('disabled');

		// Popover for HE

    $('[data-toggle="popover"]').popover({
      title: function () {
        let slug = $(this).data('slug');
        return popover[slug].title;
      },
      content: function () {
        let slug = $(this).data('slug');
        return popover[slug].content;
      }
    })

    // Responsive image rwdImageMaps

    $('img[usemap]').rwdImageMaps();

	});

}(jQuery));
