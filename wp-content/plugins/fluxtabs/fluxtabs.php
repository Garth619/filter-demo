<?php

/*
Plugin Name: Flux Tabs
Plugin URI:  http://www.1point21interactive.com
Description: Creates Filtered Tabs for Posts/Custom Post Types based on Tags.
Version:     1.0
Author:      Garrett Cullen
Author URI:  http://www.1point21interactive.com
*/ 


defined( 'ABSPATH' ) OR exit;


register_activation_hook( __FILE__, 'fluxtabs_activation' );

// Plugin activation

function fluxtabs_activation() {
			
	 flux_tabs();
	 flush_rewrite_rules();
	 
}



if ( ! function_exists( 'flux_tabs' ) ) {
  
  add_action('init', 'flux_tabs');
  
  // Register Custom Post Type
  
  function flux_tabs() { 
	  
	  	$rename_cpt_url = sanitize_title(get_option('myplugin_field_cpt'));  
			$rename_cpt_title = get_option('myplugin_field_cpt');   

    	$args = array(    
        	'label' => __($rename_cpt_title),    
        	'singular_label' => __($rename_cpt_title),    
        	'public' => true,    
        	'show_ui' => true,
        	'has_archive' => false,	 
        	'capability_type' => 'post',    
        	'hierarchical' => false,    
        	'rewrite' => array('with_front' => false,'slug' => $rename_cpt_url),
					'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )    
       	);    
   	 
    	register_post_type( 'flux_tabs' , $args );
    	
    	// Flux Tab Tags
    	
    	register_taxonomy ('flux-tabs-tag',array('flux_tabs'),
				array (
        	'hierarchical' => false,
					'labels' => array (
            'name' => _x( $rename_cpt_title.' Tags', 'taxonomy general name' ),
            'singular_name' => _x( $rename_cpt_title.' Tag', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search '.$rename_cpt_title.' Tags' ),
            'all_items' => __( 'All '.$rename_cpt_title.' Tags' ),
            'edit_item' => __( 'Edit '.$rename_cpt_title.' Tag' ), 
            'update_item' => __( 'Update '.$rename_cpt_title.' Tag' ),
            'add_new_item' => __( 'Add New '.$rename_cpt_title.' Tag' ),
            'new_item_name' => __( 'New F'.$rename_cpt_title.'Tag Name' ),
            'menu_name' => __( $rename_cpt_title.' Tags' ),
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


function flux_posts_shortcode( $atts, $content = null ) { 
	
		global $wp_query,
					$post;

		$atts = shortcode_atts( array(
       'feed' => 'post'
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
				
						print '<button data-filter-name="'.$myarray[0].'-'.$buttontag->slug.'" data-filter=".'.$myarray[0].'-'.$buttontag->slug.'">'. $buttontag->name.'</button>';
			
					}
					
				}
				
			?>
			
		</div><!-- button-group -->
	
	
		<?php print '<button id="clearall">Clear Filters</button>';?>
		
		
	</div><!-- button_wrapper -->
    
    
    <?php ob_start();
    
    
        
    // Flux Posts Loop
    
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
	
		?>
		
		<div class="flux_tabs_page_wrapper">
			
					
			<div id="isotope">
				
				<?php echo $content;?>
			
			</div><!-- isotope -->
			
		</div><!-- flux_tabs_page_wrapper -->
	
	
	<?php }

}


// Flux Template Tags (Do Action) - template tags if you need custom classes somewhere in the template for whataver reason (similer to post_class). 

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
        $plugin_array['mybutton'] = plugin_dir_url( __FILE__ ) . 'js/tinymce_buttons-min.js';
        
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

// Settings API

add_option( 'myplugin_field_cpt', 'flux_tabs' );
add_option( 'demo-radio', 1 );
add_option( 'myplugin_field_3', '#000000' );
add_option( 'myplugin_field_4', '#969696' );
add_option( 'myplugin_field_5', 'arial' );
add_option( 'myplugin_field_6', '#ffffff' );
add_option( 'myplugin_field_7', '#ffffff' );
add_option( 'myplugin_field_2', 'uppercase' );
add_option( 'myplugin_field_8', '15px' );
add_option( 'myplugin_field_9', 'bold' );
 

function myplugin_settings_menu() {
 
    global $flux_settings_page;
    
    $flux_settings_page =
    
    add_options_page(
       'Flux Tabs Settings',
       'Flux Tabs',
       'manage_options',
       'myplugin_settings',
       'myplugin_settings_page'
    );
 
}
add_action( 'admin_menu', 'myplugin_settings_menu' );



// Load JS Scripts on the settings page


function flux_admin_load_scripts($hook) {
 
	global $flux_settings_page;
 
	if( $hook != $flux_settings_page ) 
		return;
	
	wp_enqueue_script( 'myadmin-js', plugin_dir_url( __FILE__ ) . 'js/my-admin-min.js', array('jquery'), '1.0', false );
	
}


add_action('admin_enqueue_scripts', 'flux_admin_load_scripts');
 

 function myplugin_settings_page() { ?>
 
    <div class="wrap">
       
 
        <h2>Flux Tabs</h2>
       
        <p>Flux Tabs allow you to filter through Posts based on Tags.</p>
        
        <p><strong>To Use:</strong></p>
        
        <ul>
	        <li>1. Create Posts for your Blog and assign various Tags to each Post.</li>
	        <li>2. Create a static Page.</li>
	        <li>3. Look for the 1P21 Button in the Page's Rich Text Editor and add a Shortcode.</li>
	        <li>4. Click Update, enjoy.</li>
        </ul>
        
        <hr style="margin:25px 0;">
        
        <form method="post" action="options.php">
            
            <?php
 
           
            do_settings_sections( 'myplugin_settings' );
 
           
            settings_fields( 'myplugin_settings_group' );
 
            
            submit_button();
            
            //flush_rewrite_rules();
 
            ?>
            
        </form>
    </div>
 
    <?php
}
 

function myplugin_settings_init() {
 		
 		
 		add_settings_section(
       'myplugin_settings_section_1',
       'Custom Post Type',
			 'myplugin_settings_section_1_callback',
       'myplugin_settings'
    );
    
    
    function myplugin_settings_section_1_callback() { ?>
 
    <p>If you don't want to use your blog, Flux Tabs comes with one registered Custom Post Type for you to use. You can rename it or disable it below.</p>
    
   
    	  
	  <p><i><strong style="color:red">Note: After you rename this Custom Post Type and hit Save Changes below, go to Settings --> Permalinks and click Save Changes.</strong><br/> This will initiate your new Custom Post Type Name. Otherwise you will get 404 errors. For more information: <a href="https://typerocket.com/flushing-permalinks-in-wordpress/" target="_blank">Flushing Permalinks in Wordpress</a></i></p>
	  
	 
	  
<!-- 	  <p><i>Future releases of Flux Tabs will allow for unlimited Custom Post Types to be used/created.</i></p> -->
	  


<?php }
	
	
	
		add_settings_field(
    	"demo-radio",
    	"Disable Custom Post Type",
    	"demo_radio_display",
    	"myplugin_settings",
    	"myplugin_settings_section_1");  
    
    
    register_setting("myplugin_settings_group", "demo-radio");
 		
 		add_settings_field(
       'myplugin_field_cpt',
       '<span class="rename_cpt">Rename Custom Post Type</span>',
       'myplugin_field_cpt_input',
       'myplugin_settings',
       'myplugin_settings_section_1'
    );
    
    
    register_setting( 'myplugin_settings_group', 'myplugin_field_cpt' );
    
    
		add_settings_section(
       'myplugin_settings_section_2',
       '<hr style="margin-bottom:25px;">CSS Styles',
			 'myplugin_settings_section_2_callback',
       'myplugin_settings'
    );
     
     
		add_settings_field(
       'myplugin_field_3',
       'Background Color',
       'myplugin_field_3_input',
       'myplugin_settings',
       'myplugin_settings_section_2'
    );
 
    
    register_setting( 'myplugin_settings_group', 'myplugin_field_3' );
    
    
    
    add_settings_field(
       'myplugin_field_4',
       'Background Hover Color',
       'myplugin_field_4_input',
       'myplugin_settings',
       'myplugin_settings_section_2'
    );
    
     
    register_setting( 'myplugin_settings_group', 'myplugin_field_4' );
    
    
    add_settings_field(
       'myplugin_field_5',
       'Font Family',
       'myplugin_field_5_input',
       'myplugin_settings',
       'myplugin_settings_section_2'
    );
    
    
    register_setting( 'myplugin_settings_group', 'myplugin_field_5' );
    
    
    add_settings_field(
       'myplugin_field_6',
       'Text Color',
       'myplugin_field_6_input',
       'myplugin_settings',
       'myplugin_settings_section_2'
    );
    
    
    register_setting( 'myplugin_settings_group', 'myplugin_field_6' );
    
   
    add_settings_field(
       'myplugin_field_7',
       'Text Hover Color',
       'myplugin_field_7_input',
       'myplugin_settings',
       'myplugin_settings_section_2'
    );
    
    
    register_setting( 'myplugin_settings_group', 'myplugin_field_7' );
    
    
    add_settings_field(
       'myplugin_field_8',
       'Font Size',
       'myplugin_field_8_input',
       'myplugin_settings',
       'myplugin_settings_section_2'
    );
 
    
    register_setting( 'myplugin_settings_group', 'myplugin_field_8' );
    
    
    add_settings_field(
       'myplugin_field_2',
       'Text Transform',
       'myplugin_field_2_input',
       'myplugin_settings',
       'myplugin_settings_section_2'
    );
 
    
    register_setting( 'myplugin_settings_group', 'myplugin_field_2' ); 
    
    
     
    add_settings_field(
      'myplugin_field_9',
      'Font Weight',
      'myplugin_field_9_input',
      'myplugin_settings',
      'myplugin_settings_section_2'
    );
 
    
    register_setting( 'myplugin_settings_group', 'myplugin_field_9' ); 
    
    
}
add_action( 'admin_init', 'myplugin_settings_init' );
 
 
function myplugin_settings_section_2_callback() {
 
    echo( 'Change the settings below to change the styles of any Flux Tabs that you create.' );
}
 


function myplugin_field_2_input() {
 
    
    $options = array(
        'uppercase' => 'Uppercase',
        'lowercase' => 'Lowercase',
        'capitalized' => 'Capitalized'
    );
     
    
    $current = get_option( 'myplugin_field_2' );
     
    
    $html = '<select id="myplugin_field_2" name="myplugin_field_2">';
 
    foreach ( $options as $value => $text )
    {
        $html .= '<option value="'. $value .'"';
 
        
        if ( $value == $current ) $html .= ' selected="selected"';
 
        $html .= '>'. $text .'</option>';
    }
     
    $html .= '</select>';
 
    echo( $html );  
}



function myplugin_field_cpt_input() {
 
    
    echo( '<input type="text" name="myplugin_field_cpt" id="myplugin_field_cpt" value="'. get_option( 'myplugin_field_cpt' ) .'" /> <br/>' ); 
}


   function demo_radio_display()
			{
   		?>
        <input id="radio_one" type="radio" name="demo-radio" value="1" <?php checked(1, get_option('demo-radio'), true); ?>>Enable Custom Post Type<br/>
        <input id="radio_two" type="radio" name="demo-radio" value="2" <?php checked(2, get_option('demo-radio'), true); ?>>Disable Custom Post Type
        
      <?php
}

// Deregisters Custom Post Type if radio button selected

if(get_option('demo-radio') == 2) {
	
	function delete_post_type(){
  
  	unregister_post_type( 'flux_tabs' );
}


add_action('init','delete_post_type', 100);


}


function myplugin_field_3_input() {
 
    
    echo( '<input type="text" name="myplugin_field_3" id="myplugin_field_3" value="'. get_option( 'myplugin_field_3' ) .'" />' ); 
}



function myplugin_field_4_input() {
 
    
    echo( '<input type="text" name="myplugin_field_4" id="myplugin_field_4" value="'. get_option( 'myplugin_field_4' ) .'" />' ); 
}


function myplugin_field_5_input() {
 
    
    echo( '<input type="text" name="myplugin_field_5" id="myplugin_field_5" value="'. get_option( 'myplugin_field_5' ) .'" />' ); 
}



function myplugin_field_6_input() {
 
    
    echo( '<input type="text" name="myplugin_field_6" id="myplugin_field_6" value="'. get_option( 'myplugin_field_6' ) .'" />' ); 
}




function myplugin_field_7_input() {
 
    
    echo( '<input type="text" name="myplugin_field_7" id="myplugin_field_7" value="'. get_option( 'myplugin_field_7' ) .'" />' ); 
}



function myplugin_field_8_input() {
 
    
    echo( '<input type="text" name="myplugin_field_8" id="myplugin_field_8" value="'. get_option( 'myplugin_field_8' ) .'" />' ); 
}



function myplugin_field_9_input() {
 
   
    $options = array(
        'bold' => 'Bold',
        'normal' => 'Normal'
     );
     
    
    $current = get_option( 'myplugin_field_9' );
     
   
    $html = '<select id="myplugin_field_9" name="myplugin_field_9">';
 
    foreach ( $options as $value => $text )
    {
        $html .= '<option value="'. $value .'"';
 
        
        if ( $value == $current ) $html .= ' selected="selected"';
 
        $html .= '>'. $text .'</option>';
    }
     
    $html .= '</select>';
 
    echo( $html );  
}


// CSS


add_action( 'wp_head', 'internal_css_print' );

function internal_css_print() {
		
		
	$settings_background = get_option('myplugin_field_3');
	$settings_background_hover = get_option('myplugin_field_4');
	$settings_font_family = get_option('myplugin_field_5');
	$settings_text_color = get_option('myplugin_field_6');
	$settings_text_hover_color = get_option('myplugin_field_7');
	$settings_text_transform = get_option('myplugin_field_2');
	$settings_font_size = get_option('myplugin_field_8');
	$settings_font_weight = get_option('myplugin_field_9');
  
  echo '<style type="text/css">
  
.button_wrapper{text-align:left;margin-bottom:35px}.button_wrapper button:focus {outline:0;}.button_wrapper button{font-weight:'.$settings_font_weight.';font-size:'.$settings_font_size.';text-transform:'.$settings_text_transform.';color:'.$settings_text_color.';font-family:'.$settings_font_family.';border:none;padding:8px 20px;margin-bottom:4px;-webkit-transition:all .2s ease-in-out;transition:all .2s ease-in-out;cursor:pointer;margin-right:5px;background:'.$settings_background.'}.button_wrapper button#clearall{font-family:'.$settings_font_family.';background:#969696;color:#fff}.button_wrapper button#clearall:hover{background:#acacac}.button_wrapper button.active{background:'.$settings_background_hover.'}button.active, button:hover {color:'.$settings_text_hover_color.';background:'.$settings_background_hover.'}


</style>';


} 



// JS



function flux_js() {   
    
	
	wp_enqueue_script( 'flux_script', plugin_dir_url( __FILE__ ) . 'js/flux-js-min.js', array('jquery'), '1.0', true );
	
	}


add_action('wp_enqueue_scripts', 'flux_js');


// Flush Rewrite Rules Again if Plugin is Deactivated


function myplugin_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'myplugin_deactivate' );


