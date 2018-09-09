Drupal.behaviors.customGobear = {
    attach: function (context) {
      jQuery('.show-desp', context).click(function(){
       jQuery(this).siblings('.description').slideToggle('slow');
       if(jQuery(this).text() === 'Less Info >'){
           jQuery(this).text('More Info >');
       } else {
           jQuery(this).text('Less Info >');
       }
    });
    
    }
};
