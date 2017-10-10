

 jQuery(document).ready(function(){
	
	
	// Settings Hide Custom Post Type Rename
  
  
  jQuery('#radio_two').on('click', function(e) {
	        	
	  jQuery('#myplugin_field_cpt, span.rename_cpt').fadeOut();	  
	  
        	  
  });
  
  jQuery('#radio_one').on('click', function(e) {
	        	
	  jQuery('#myplugin_field_cpt, span.rename_cpt').fadeIn();
        	  
  });
  

}); // Document Ready

