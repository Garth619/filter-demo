

 jQuery(document).ready(function($){
	
	
	// Settings Hide Custom Post Type Rename
  
  
  $('#radio_two').on('click', function(e) {
	        	
	  $('#myplugin_field_cpt, span.rename_cpt').fadeOut();	  
	  
        	  
  });
  
  $('#radio_one').on('click', function(e) {
	        	
	  $('#myplugin_field_cpt, span.rename_cpt').fadeIn();
        	  
  });
  
  
  
  if($("#radio_two").is(":checked")){
  //Code to append goes here


$('#myplugin_field_cpt, span.rename_cpt').hide();




}



if($("#radio_one").is(":checked")){
  //Code to append goes here


$('#myplugin_field_cpt, span.rename_cpt').show();




}
  
  
  
  
  
/*
  $('#radio_two').change(
    function(){
        if (this.checked && this.value == 'Yes') {
            
        
        	
        
        
        }
    });
  
*/
  
  
  
  

}); // Document Ready

