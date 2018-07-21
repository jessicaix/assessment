jQuery(document).ready(function(){
  jQuery('#more').click(function(){
   jQuery(this).siblings('#text').show();
   jQuery(this).siblings('#less').show();
   jQuery(this).hide();
  });
  jQuery('#less').click(function(){
   jQuery(this).siblings('#text').hide();
   jQuery(this).siblings('#more').show();
   jQuery(this).hide();
  });
});


