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
  
  function flux_tabs() {    
    	$args = array(    
        	'label' => __('Practice Areas'),    
        	'singular_label' => __('Practice Area'),    
        	'public' => true,    
        	'show_ui' => true,
        	'has_archive' => true,	 
        	'capability_type' => 'post',    
        	'hierarchical' => false,    
        	'rewrite' => true,    
        	'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )    
       	);    
   	 
    	register_post_type( 'flux_tabs' , $args );    
	}  
	
	
	add_action('init', 'flux_tabs');
	
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
				'type' => 'post',
				'orderby' => 'name',
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
               
               <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
               
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
  
  body{font-family:"helvetica"}.container{width:100%;max-width:700px;margin:50px auto;padding:0 4px}.button_wrapper{text-align:left;margin-bottom:35px}.button_wrapper button{background:#000;color:#fff;border:none;text-transform:uppercase;font-weight:bold;font-size:14px;padding:8px 20px;margin-bottom:4px;transition:all .2s ease-in-out;cursor:pointer;margin-right:5px}.button_wrapper button:hover{background:#ed1d24}.button_wrapper button#clearall{background:grey}.button_wrapper button#clearall:hover{background:#ed1d24}.button_wrapper button.active{background:#ed1d24}.flux-post{margin-bottom:25px;line-height:22px}.flux-post img{display:block;margin-top:20px;margin-bottom:30px;max-width:100%;height:auto}.flux-post h2{margin-bottom:9px;font-size:20px}.flux-post p{margin-bottom:25px}

</style>';


}




/*
if ( ! function_exists( 'flux_tabs_blog' ) ) {

function flux_tabs_blog($atts, $content = null) {
        extract(shortcode_atts(array(
           "num" => '200',
           "cat" => ''
        ), $atts));
        
        global $post;
        
        $myposts = get_posts('numberposts='.$num.'&order=DESC&orderby=post_date&category='.$cat);
        
        //$posttags = get_the_tags();
        
        $flux_output='<ul>';
        
        foreach($myposts as $post) :
           
           setup_postdata($post);
             
           $flux_output.='<div><a href="'.get_permalink().'">'.the_title("","",false).'</a></div>';
        
        endforeach;
        
        $flux_output.='</ul> ';
        
        return $flux_output;
}


add_shortcode("flux_list", "flux_tabs_blog");

}
*/



/*
function sc_liste($atts, $content = null) {
        extract(shortcode_atts(array(
                "num" => '',
                "cat" => ''
        ), $atts));
        global $post;
        $myposts = get_posts('numberposts='.$num.'&order=DESC&orderby=post_date&category='.$cat);
        $retour='<ul>';
        foreach($myposts as $post) :
                setup_postdata($post);
             $retour.='<li><a href="'.get_permalink().'">'.the_title("","",false).'</a></li>';
        endforeach;
        $retour.='</ul> ';
        return $retour;
}


add_shortcode("list", "sc_liste");
*/







	
	
/*
if ( ! function_exists( 'flux_cpt_shortcode' ) ) {


function flux_cpt_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		"post" => ''
		
	), $atts));
	return '<video id="'.$id.'" class="gif_replacement_video" src="'.$src.'" width="'.$width.'" autoplay loop muted playsinline autobuffer></video>';
}

} 
*/    






// Functions that need to be cleaned up upon deactivation

// function fluxtabs_deactivate() {}register_activation_hook( __FILE__, 'fluxtabs_deactivation' );







/*
if ( ! function_exists( 'fluxtabs_post_id' ) ) {
	
		
		function fluxtabs_post_id() {
	    
	    if ( ! is_admin() && in_the_loop() ) {
		
				
				$fluxpost = get_the_ID();
				
				echo 'flux-post-' . $fluxpost . ' ';
				
				
			}
		}
  	
		add_action( 'fluxtabs_post_id', 'fluxtabs_post_id' );
}
*/




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




