(function ($) {
  'use strict';

  Drupal.behaviors.gobearJobList = {
    attach: function (context, settings) {
      var self = this;
      $('.collapse').on('hide.bs.collapse', function () {
        self.toggleText(this);
      });
      $('.collapse').on('show.bs.collapse', function () {
        self.toggleText(this);
      });
    },

    toggleText: function (el) {
      var $trigger = $(el).closest('li').find('a[data-toggle="collapse"]');

      console.log('Here...');

      if ($trigger.length) {
        if ($(el).hasClass('in')) {
          $trigger.text(Drupal.t('Less info'));
        }
        else {
          $trigger.text(Drupal.t('More info'));
        }
      }
    }
  };

})(jQuery);
