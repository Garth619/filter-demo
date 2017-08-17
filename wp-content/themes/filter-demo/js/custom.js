jQuery(document).ready(function(){
	
	
	$(function() {
		var selectedClass = "";
		$(".fil-cat").click(function(){ 
		selectedClass = $(this).attr("data-rel"); 
     $("#filter_container").fadeTo(100, 0.1);
		$("#filter_container div").not("."+selectedClass).fadeOut().removeClass('scale-anm');
    setTimeout(function() {
      $("."+selectedClass).fadeIn().addClass('scale-anm');
      $("#filter_container").fadeTo(300, 1);
    }, 300); 
		
	});
});
	
	
  
}); // document ready