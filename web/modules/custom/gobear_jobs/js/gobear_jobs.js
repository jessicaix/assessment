(function ($, Drupal) {
    Drupal.behaviors.jobs = {
        attach: function (context, settings) {
            //JS show hide
            $(".job-item .description a.more").each(function(){
               $(this).click(function(e){
                   e.preventDefault();
                   $(this).next().slideToggle("fast");
                   $(this).next().next().show();
                   $(this).hide();
               });
            });
            $(".job-item .description a.less").each(function(){
                $(this).click(function(e){
                    e.preventDefault();
                    $(this).prev().hide();
                    $(this).prev().prev().show();
                    $(this).hide();
                });

            });

        }
    }
})(jQuery, Drupal);
