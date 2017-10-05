<?php

/*
Plugin Name: Flux Tabs
Plugin URI:  http://www.1point21interactive.com
Description: Creates Filtered Tabs for Posts/Custom Post Types based on Tags. Can also set up tabs for sections on Static Pages.
Version:     1.0
Author:      Garrett Cullen
Author URI:  http://www.1point21interactive.com
*/ 


defined( 'ABSPATH' ) OR exit;


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
        	'has_archive' => false,	 
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
 
 
 
 
 


// Posts Shortcode


if ( ! function_exists( 'flux_posts_shortcode' ) ) {


add_shortcode( 'flux-tabs', 'flux_posts_shortcode' );

// add_filter('widget_text', 'do_shortcode');

function flux_posts_shortcode( $atts, $content = null ) { 
	


		global $wp_query,
					$post;

		$atts = shortcode_atts( array(
       'feed' => 'post',
       'font-size' => '16px',
       'background' => '#000',
       'font-family' => 'helvetica',
       'color' => '#fff',
       'text-transform' => 'uppercase',
       'font-weight' => 'bold'
    ), $atts );
    
    
    // wrap in if
    
    $font_size = $atts['font-size'];
    $background =  $atts['background'];
    $font_family = $atts['font-family'];
    $color =  $atts['color'];
    $text_transform = $atts['text-transform'];
    $font_weight = $atts['font-weight'];
	    
   ?>
    
  
  <div class="button_wrapper">
	
		<div id="button_isotope_wrapper" class="button-group">
			
			
			<?php
				
				
				// echos out the default posts tag prefix a little differently than cpts
				
				if(sanitize_title( $atts['feed'] ) == 'post') {
					
						$args = array(
							'taxonomy' => 'post_tag'
						);
					
					$buttontags = get_terms($args);
			
					foreach($buttontags as $buttontag) { 
				
						print '<button style="font-weight:'.$font_weight.'; text-transform:'.$text_transform.'; color:'.$color.'; font-family:'.$font_family.'; background:'.$background.'; font-size:'.$font_size.'; font-family:'.$font_family.';" data-filter-name="tag-'.$buttontag->slug.'" data-filter=".tag-'.$buttontag->slug.'">'. $buttontag->name.'</button>';
			
					}
				
				}
				
				else {
					
					// prints out the cpt posts tag prefix as well as the tax title 
					
					
				$mytest = sanitize_title( $atts['feed'] );
					
				$listthisouts = get_object_taxonomies($mytest);	
				
				
				$myarray = array();
				
				foreach ($listthisouts as $listthisout) {
					
					$myarray[] = $listthisout;
					
				}

					
					$args = array(
						'taxonomy' => $myarray[0]
					);
					
					$buttontags = get_terms($args);
			
					foreach($buttontags as $buttontag) { 
				
						print '<button style="font-weight:'.$font_weight.'; text-transform:'.$text_transform.'; color:'.$color.'; font-family:'.$font_family.'; background:'.$background.'; font-size:'.$font_size.'; font-family:'.$font_family.';" data-filter-name="'.$myarray[0].'-'.$buttontag->slug.'" data-filter=".'.$myarray[0].'-'.$buttontag->slug.'">'. $buttontag->name.'</button>';
			
					}
					
				}
				
			?>
			
		</div><!-- button-group -->
	
	
		<?php print '<button id="clearall" style="color:#fff; text-transform:'.$text_transform.'; font-family:'.$font_family.'; font-size:'.$font_size.'; font-weight:'.$font_weight.';">Clear Filters</button>';?>
		
		

		
		
		
	
	
	</div><!-- button_wrapper -->
    
    
    <?php ob_start();
    
    
        
    // Flux Posts
    
    $query = new WP_Query( array(
      'post_type' => array( sanitize_title( $atts['feed'] ) )
      
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
        
        </div>
    
    <?php $myvariable = ob_get_clean();
    
    return $myvariable;
    }
	}
}




// Static Page Shortcode

if ( ! function_exists( 'flux_static_page_shortcode' ) ) {


	add_shortcode( 'flux-tabs-page', 'flux_static_page_shortcode' );
	
	function flux_static_page_shortcode( $atts, $content = null ) {
		
		
		
		
		$atts = shortcode_atts( array(
       'selector' => '',
       ), $atts ); 
	
	

	// How will this Vairable get pushed to my Jquery File ?? 
	
	 // $go = sanitize_title( $atts['selector'] );

		// echo $go;?>
		
		<div class="flux_tabs_page_wrapper">
			
					
			<div id="isotope">
				
				
			
				<?php echo $content;?>
			
			</div><!-- isotope -->
			
		</div><!-- flux_tabs_page_wrapper -->
	
	
	<?php }

}


// Flux Template Tags (Do Action) - template tags if you need custom classes somewhere in the template for whataver reason (similer to post_class). However I'm not currently using 

if ( ! function_exists( 'fluxtabs_classes' ) ) {
	
		
		function fluxtabs_classes() {
	    
	    if ( ! is_admin() && in_the_loop() ) {
		
				
				$fluxpost = get_the_ID();
				
				echo 'flux-post-' . $fluxpost . ' ';
				

				
				$posttags = get_the_tags();
				
				if ($posttags) {
					
					foreach($posttags as $tag) {
					
						 echo $tag->slug .' '; 
  				
  				}
				}
			}
		}
  	
		add_action( 'fluxtabs_classes', 'fluxtabs_classes' );
}




// Single Post Button



if ( ! function_exists( 'flux_tabs_theme_setup' ) ) {
    function flux_tabs_theme_setup() {
 
        add_action( 'init', 'flux_tabs_buttons' );
 
    }
}
 


add_action( 'after_setup_theme', 'flux_tabs_theme_setup' );




if ( ! function_exists( 'flux_tabs_buttons' ) ) {
    function flux_tabs_buttons() {
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }
 
        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }
 
        add_filter( 'mce_external_plugins', 'flux_tabs_add_buttons' );
        add_filter( 'mce_buttons', 'flux_tabs_register_buttons' );
    }
}
 




if ( ! function_exists( 'flux_tabs_add_buttons' ) ) {
    function flux_tabs_add_buttons( $plugin_array ) {
        $plugin_array['mybutton'] = plugin_dir_url( __FILE__ ) . 'js/tinymce_buttons.js';
        
        return $plugin_array;
    }
}


 


if ( ! function_exists( 'flux_tabs_register_buttons' ) ) {
    function flux_tabs_register_buttons( $buttons ) {
        array_push( $buttons, 'mybutton' );
        return $buttons;
    }
}
 


if ( !function_exists( 'flux_tabs_tinymce_extra_vars' ) ) {
	function flux_tabs_tinymce_extra_vars() { ?>
		
		<script type="text/javascript">
			
			var tinyMCE_object = <?php echo json_encode(
				array(
					'button_name' => esc_html__('Flux Tabs', 'flux_tabs-slug'),
					'button_title' => esc_html__('Flux Tabs', 'flux_tabs-slug'),
					'image_title' => esc_html__('MP4 Video File URL', 'flux_tabs-slug'),
					'image_button_title' => esc_html__('Upload or Select MP4 From Media Library', 'flux_tabs-slug'),
				)
				);
			?>;
			
		</script><?php
	
	}
}


add_action ( 'after_wp_tiny_mce', 'flux_tabs_tinymce_extra_vars' );


// CSS


add_action( 'wp_head', 'internal_css_print' );

function internal_css_print() {
	
	global $atts;
  
  echo '<style type="text/css">
  
.button_wrapper{text-align:left;margin-bottom:35px}.button_wrapper button{border:none;padding:8px 20px;margin-bottom:4px;-webkit-transition:all .2s ease-in-out;transition:all .2s ease-in-out;cursor:pointer;margin-right:5px}.button_wrapper button#clearall{background:#7d7d7d}.button_wrapper button#clearall:hover{background:#7c7c7c}.button_wrapper button.active{background:#ed1d24}button.active, button:hover {background:grey !important;}


</style>';


} 



// JS



function flux_js() {   
    
	
	wp_enqueue_script( 'flux_script', plugin_dir_url( __FILE__ ) . 'js/flux-js-min.js', array('jquery'), '1.0', true );
	
	}


add_action('wp_enqueue_scripts', 'flux_js');




/*
if ( !function_exists( 'list_cpt_list' ) ) {

	function list_cpt_list() {
	
		$args = array(
			'public'   => true,
			'_builtin' => false
		);
	
	
		$list_post_types = get_post_types($args);
	
		foreach ( $list_post_types as $list_post_type ) {
  
  		echo '{ text:"' . $list_post_type .'", value: "'.$list_post_type.'" },';
	
		}
	
	}

}
*/


