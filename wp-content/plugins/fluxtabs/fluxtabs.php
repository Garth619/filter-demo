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


// Shortcode


if ( ! function_exists( 'flux_posts_shortcode' ) ) {


add_shortcode( 'flux-tabs', 'flux_posts_shortcode' );

function flux_posts_shortcode( $atts, $content = null ) { 
	
	
/*
	extract(shortcode_atts(array(
		"feed" => '',
	), $atts));
*/

		global $wp_query,
        	 $post;

		$atts = shortcode_atts( array(
       'feed' => '',
       'wrap' => ''
    ), $atts );

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
				
						print '<button data-filter-name="tag-'.$buttontag->slug.'" data-filter=".tag-'.$buttontag->slug.'">'. $buttontag->name.'</button>';
			
					}
				
				}
				
				else {
					
					// echos out the cpt posts tag prefix
					
					
					///////////
				
				
				
				$mytest = sanitize_title( $atts['feed'] );
					
				$listthisouts = get_object_taxonomies($mytest);	
				
				
				$myarray = array();
				
				foreach ($listthisouts as $listthisout) {
					
					$myarray[] = $listthisout; // not sure about this line
					
				}
				
				
				echo $myarray[0];
				
/*
				$myArray = array(); 
				
				foreach ( $databaseResultSet as $resultRow ) { 
						$myArray[] = $resultRow['someColumn']; 
				} 

				print_r($myArray); //$myArray now contains all contents of 'someColumn' from the result set  
*/
					
				
				
				/////////
					
					$args = array(
						'taxonomy' => 'flux-tab-tag'
					);
					
					$buttontags = get_terms($args);
			
					foreach($buttontags as $buttontag) { 
				
						print '<button data-filter-name="flux-tab-tag-'.$buttontag->slug.'" data-filter=".flux-tab-tag-'.$buttontag->slug.'">'. $buttontag->name.'</button>';
			
					}
					
				}
				
			?>
			
		</div><!-- button-group -->
	
	
		<button id="clearall">Clear Filters</button>
	
	
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





/*
if ( !function_exists( 'register_shortcodes' ) ) {


	function register_shortcodes() {
    add_shortcode( 'flux-tabs', 'shortcode_options' );
	}

	add_action( 'init', 'register_shortcodes' );


	function shortcode_options( $atts ) {
    global $wp_query,
        	 $post;

    $atts = shortcode_atts( array(
       'feed' => '',
       'tag'	=> ''
    ), $atts ); ?>

    
    
    
    
   <div class="button_wrapper">
	
		<div id="button_isotope_wrapper" class="button-group">
			
			<?php $args = array(
				
				'order' => 'ASC',
				'hide_empty' => false,
				'taxonomy' => 'post_tag'
				
			);?>

			<?php $buttontags = get_terms($args);

			foreach($buttontags as $buttontag) { 
				
				echo '<button data-filter-name="flux-tab-tag-'.$buttontag->slug.'" data-filter=".flux-tab-tag-'.$buttontag->slug.'">'. $buttontag->name.'</button>';
			
			
			} ?>
	
		</div>
	
	
		<button id="clearall">Clear Filters</button>
	
	
	</div>
    
		<?php $loop = new WP_Query( array(
       'post_type'  =>  array( sanitize_title( $atts['feed'] ) ),
        'order'     => 'ASC'
        )
       );

    
    
    if( ! $loop->have_posts() ) {
        return false;
    } 
    
    echo '<div class="isotope">';

    while( $loop->have_posts() ) {
        $loop->the_post(); ?>
        
        
        
				<div id="post-<?php the_ID(); ?>" <?php post_class('flux-post');?>>
               
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
               
            <?php the_content();?>
               
         </div>
    
    
    
    <?php }
	    
	    echo '</div>';

    wp_reset_postdata();
	}


}
*/



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





// JS



function flux_js() {   
    
	
	wp_enqueue_script( 'flux_script', plugin_dir_url( __FILE__ ) . 'js/flux-js-min.js', array('jquery'), '1.0', true );
	
	}


add_action('wp_enqueue_scripts', 'flux_js');




// CSS



add_action( 'wp_head', 'internal_css_print' );

function internal_css_print() {
  
  echo '<style type="text/css">
  
.button_wrapper{text-align:left;margin-bottom:35px}.button_wrapper button{background:#000;color:#fff;border:none;text-transform:uppercase;font-weight:bold;font-family:helvetica;font-size:14px;padding:8px 20px;margin-bottom:4px;transition:all .2s ease-in-out;cursor:pointer;margin-right:5px}.button_wrapper button:hover{background:#ed1d24}.button_wrapper button#clearall{background:grey}.button_wrapper button#clearall:hover{background:#ed1d24}.button_wrapper button.active{background:#ed1d24}


</style>';


} 


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


