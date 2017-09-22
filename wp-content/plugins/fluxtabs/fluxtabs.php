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
			
	 flux_tabs();
	 flush_rewrite_rules();
	 

}

// Register Custom Post Type

if ( ! function_exists( 'flux_tabs' ) ) {
  
  add_action('init', 'flux_tabs');
  
  function flux_tabs() {    
    	$args = array(    
        	'label' => __('Flux Tabs'),    
        	'singular_label' => __('Flux Tab'),    
        	'public' => true,    
        	'show_ui' => true,
        	'has_archive' => true,	 
        	'capability_type' => 'post',    
        	'hierarchical' => false,    
        	'rewrite' => true,    
        	'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )    
       	);    
   	 
    	register_post_type( 'flux_tabs' , $args );
    	
    	// Flux Tab Tags
    	
    	register_taxonomy ('flux-tab-tag',array('flux_tabs'),
				array (
        	'hierarchical' => false,
					'labels' => array (
            'name' => _x( 'Flux Tab Tags', 'taxonomy general name' ),
            'singular_name' => _x( 'Flux Tab Tag', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Flux Tab Tags' ),
            'all_items' => __( 'All Flux Tab Tags' ),
            'edit_item' => __( 'Edit Flux Tab Tag' ), 
            'update_item' => __( 'Update Flux Tab Tag' ),
            'add_new_item' => __( 'Add New Flux Tab Tag' ),
            'new_item_name' => __( 'New Flux Tab Tag Name' ),
            'menu_name' => __( 'Flux Tab Tags' ),
        ),
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'flux-tab-tag', 'with_front' => true),
				)
			);
    } 
    
    
 }
	
// Flux Template Tags (Do Action) 

if ( ! function_exists( 'fluxtabs_classes' ) ) {
	
		
		function fluxtabs_classes() {
	    
	    if ( ! is_admin() && in_the_loop() ) {
		
				
				$fluxpost = get_the_ID();
				
				echo 'flux-post flux-post-' . $fluxpost . ' ';
				
				$posttags = get_the_tags();
				
				if ($posttags) {
					
					foreach($posttags as $tag) {
					
						 echo 'tag-' . $tag->slug . ' '; 
  				
  				}
				}
			}
		}
  	
		add_action( 'fluxtabs_classes', 'fluxtabs_classes' );
}


// Blog Shortcode


if ( ! function_exists( 'flux_posts_shortcode' ) ) {


add_shortcode( 'flux-blog-posts', 'flux_posts_shortcode' );

function flux_posts_shortcode( $atts ) { ?>
    
  
  
  <div class="button_wrapper">
	
		<div id="button_isotope_wrapper" class="button-group">
	
			<?php $args = array(
				'post_type' => 'post',
				//'orderby' => 'name',
				'order' => 'ASC'
			);

			$buttontags = get_tags($args);

			foreach($buttontags as $buttontag) { 
				
				
			echo '<button data-filter-name="tag-'.$buttontag->slug.'" data-filter=".tag-'.$buttontag->slug.'">'. $buttontag->name.'</button>';
			
			
			} ?>
	
		</div><!-- button_isotope_wrapper -->
	
	
		<button id="clearall">Clear Filters</button>
	
	
	</div><!-- button_wrapper -->
    
    
    <?php ob_start();
    
    
        
    // Flux Posts
    
    $query = new WP_Query( array(
      'post_type' => 'post',
      'posts_per_page' => -1,
    ) );
    
    if ( $query->have_posts() ) { ?>
    
    		<div id="isotope">
            
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            
            <div id="post-<?php the_ID(); ?>" <?php post_class('flux-post');?>>
               
               <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
               
               <?php the_content();?>
               
            </div>
            
            <?php endwhile;
            wp_reset_postdata(); ?>
        
        </div><!-- isotope -->
    
    <?php $myvariable = ob_get_clean();
    
    return $myvariable;
    }
}

}


// Custom Post Type



if ( ! function_exists( 'flux_custom_posts_shortcode' ) ) {


add_shortcode( 'flux-custom-posts', 'flux_custom_posts_shortcode' );

function flux_custom_posts_shortcode( $atts ) { ?>
    
  <div class="button_wrapper">
	
		<div id="button_isotope_wrapper" class="button-group">
	
			<?php $args = array(
				'post_type' => 'flux_tabs',
				'order' => 'ASC',
			);
			
			

			$buttontags = get_terms('flux-tab-tag',$args);

			foreach($buttontags as $buttontag) { 
				
				
			echo '<button data-filter-name="flux-tab-tag-'.$buttontag->slug.'" data-filter=".flux-tab-tag-'.$buttontag->slug.'">'. $buttontag->name.'</button>';
			
			
			} ?>
	
		</div><!-- button_isotope_wrapper -->
	
	
		<button id="clearall">Clear Filters</button>
	
	
	</div><!-- button_wrapper -->
    
    
    <?php ob_start();
    
    
        
    // Flux Posts
    
    $query = new WP_Query( array(
      'post_type' => 'flux_tabs',
      'posts_per_page' => -1,
    ) );
    
    if ( $query->have_posts() ) { ?>
    
    		<div id="isotope">
            
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            
            <div id="post-<?php the_ID(); ?>" <?php post_class('flux-post');?>>
               
               <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
               
               <?php the_content();?>
               
            </div>
            
            <?php endwhile;
            wp_reset_postdata(); ?>
        
        </div><!-- isotope -->
    
    <?php $myvariable = ob_get_clean();
    
    return $myvariable;
    }
}

}





// JS


function flux_js() {   
    
	
	wp_enqueue_script( 'flux_script', plugin_dir_url( __FILE__ ) . 'js/flux-js-min.js', array('jquery'), '1.0', true );}


add_action('wp_enqueue_scripts', 'flux_js');



// CSS



add_action( 'wp_head', 'internal_css_print' );

function internal_css_print() {
  
  echo '<style type="text/css">
  
.button_wrapper{text-align:left;margin-bottom:35px}.button_wrapper button{background:#000;color:#fff;border:none;text-transform:uppercase;font-weight:bold;font-family:helvetica;font-size:14px;padding:8px 20px;margin-bottom:4px;transition:all .2s ease-in-out;cursor:pointer;margin-right:5px}.button_wrapper button:hover{background:#ed1d24}.button_wrapper button#clearall{background:grey}.button_wrapper button#clearall:hover{background:#ed1d24}.button_wrapper button.active{background:#ed1d24}


</style>';


}






// Functions that need to be cleaned up upon deactivation

// function fluxtabs_deactivate() {}register_activation_hook( __FILE__, 'fluxtabs_deactivation' );















