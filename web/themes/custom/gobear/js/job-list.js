(function ($) {
  'use strict';

  Drupal.behaviors.gobearJobList = {
    attach: function (context, settings) {
      var self = this;
      $('.collapse').on('hidden.bs.collapse', function () {
        self.toggleText(this);
      });
      $('.collapse').on('shown.bs.collapse', function () {
        self.toggleText(this);
      });
    },

    toggleText: function (el) {
      var $trigger = $(el).closest('li').find('a[data-toggle="collapse"]');

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
