<?php

defined( 'ABSPATH' ) OR exit;

/*
Plugin Name: Flux Tabs
Plugin URI:  http://www.1point21interactive.com
Description: Creates Filtered Tabs for Posts based on Tags
Version:     1.0
Author:      Garrett Cullen
Author URI:  http://www.1point21interactive.com
*/ 


register_activation_hook( __FILE__, 'fluxtabs_activation' );

// Functions that need to happen upon activation

function fluxtabs_activation() {
			
	// my_custom_post_type_three();
	// flush_rewrite_rules();

}

// add_action( 'init', 'my_custom_post_type_three' );

/*
function my_custom_post_type_three() {
    $args = array(
      'public' => true,
      'label'  => 'Board Games Three'
    );
    register_post_type( 'boardgamesthree', $args );
}
*/

// Functions that need to be cleaned up upon deactivation

// function fluxtabs_deactivate() {}register_activation_hook( __FILE__, 'fluxtabs_deactivation' );



// Blog



if ( ! function_exists( 'check_for_class' ) ) {
	
	
	//function check_for_class($myclass) {
		
		//if ( ! is_admin() && is_main_query() ) {
        
        // do something
        
    
    //}
		
		//return $myclass;
		
	// }
	
	
	// add_filter( '', '' );
	
	
	
        
    function fluxtabs_classes($fluxtags) {
	    
	    if ( ! is_admin() && in_the_loop() ) {
		
				$posttags = get_the_tags();
				
				if ($posttags) {
					
					foreach($posttags as $tag) {
					
						echo $tag->name . ' '; 
  
  				}
				}
		
			}
			
			
        
  	}
  	
  	add_filter( 'the_title', 'fluxtabs_classes' );
	
	
	


}

