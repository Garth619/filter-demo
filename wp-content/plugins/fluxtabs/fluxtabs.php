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



// Template Tags




if ( ! function_exists( 'fluxtabs_classes' ) ) {
	
		
		function fluxtabs_classes() {
	    
	    if ( ! is_admin() && in_the_loop() ) {
		
				$posttags = get_the_tags();
				
				if ($posttags) {
					
					echo 'flux-post ';
					
					foreach($posttags as $tag) {
					
						 echo 'tag-' . $tag->slug . ' '; 
  				
  				}
				}
			}
		}
  	
		add_action( 'fluxtabs_classes', 'fluxtabs_classes' );
}




if ( ! function_exists( 'fluxtabs_post_id' ) ) {
	
		
		function fluxtabs_post_id() {
	    
	    if ( ! is_admin() && in_the_loop() ) {
		
				
				$fluxpost = get_the_ID();
				
				echo 'flux-post-' . $fluxpost . ' ';
				
				
			}
		}
  	
		add_action( 'fluxtabs_post_id', 'fluxtabs_post_id' );
}




// End of custom template tags


// Manipualte Loop from Functions

/*
if ( ! function_exists( 'change_loop' ) ) {

	function change_loop($query) {
		
		if ( ! is_admin() && $query->is_main_query() ) {
        
     
        $query->set( 'posts_per_page', 3);
        
       
		}
		
	}

	add_action( 'pre_get_posts', 'change_loop' );

}
*/








/*

add_action('the_content','ravs_content_div');
 function ravs_content_div( $content ){
  return '<div class="garrett-wrap">'.$content.'</div>';
 }
*/




