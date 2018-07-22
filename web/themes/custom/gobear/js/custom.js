// Custom JS

(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.mountHappy = {
    attach: function (context, settings) {
      $('.more-info .show-desc', context).each(function(index, el) {
      	$(this).on('click', function(event) {
	      	$(this).parents('.row').toggleClass('row-toggle');
	    });
      });
    }
  };
})(jQuery, Drupal);