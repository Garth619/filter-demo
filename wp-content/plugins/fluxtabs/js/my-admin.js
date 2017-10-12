jQuery(document).ready(function($){
	
	
	// Settings Hide Custom Post Type Rename
  
  
  $('#radio_two').on('click', function(e) {
	        	
	  $('#myplugin_field_cpt, span.rename_cpt').fadeOut();	  
	  
        	  
  });
  
  $('#radio_one').on('click', function(e) {
	        	
	  $('#myplugin_field_cpt, span.rename_cpt').fadeIn();
        	  
  });
  
  
  
  if($("#radio_two").is(":checked")){
  
		$('#myplugin_field_cpt, span.rename_cpt').hide();

	}


	if($("#radio_one").is(":checked")){
  
		$('#myplugin_field_cpt, span.rename_cpt').show();

	}
  
  
}); // Document Ready

