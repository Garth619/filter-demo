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
		
		
		// add message
		
		set_transient( 'flux-tab-admin-notice', true, 5 );
		
	 // flux_tabs();
	 
	 
	 flush_rewrite_rules();
	 
}

 
add_action( 'admin_notices', 'fx_admin_notice_example_notice' );
 
 
function fx_admin_notice_example_notice(){
 
   
    if( get_transient( 'flux-tab-admin-notice' ) ){
       
       echo '<div class="updated notice is-dismissible"><p>Flux Tabs - To begin, go to the <a href="'.get_bloginfo('url').'/wp-admin/options-general.php?page=myplugin_settings">Settings Page.</a></p></div>';
        
        
        delete_transient( 'flux-tab-admin-notice' );
    }
}



// Posts Shortcode


if ( ! function_exists( 'flux_posts_shortcode' ) ) {


add_shortcode( 'flux-tabs', 'flux_posts_shortcode' );


function flux_posts_shortcode( $atts, $content = null ) { 
	
		global $wp_query,
					$post;

		$atts = shortcode_atts( array(
       'feed' => 'post',
    ), $atts );
    	    
   ob_start();?>
    
  
  <div class="button_wrapper">
	
		<div class="button_isotope_wrapper" class="button-group">
			
			
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
    
    
    <?php
    
    
    // Flux Posts Loop
    
    $query = new WP_Query( array(
      'post_type' => array( sanitize_title( $atts['feed'] ) )
      
    ) );
    
    
    if ( $query->have_posts() ) { ?>
    
    		<div id="isotope">
            
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            
            
                        
            <div id="post-<?php the_ID(); ?>" <?php post_class('flux-post');?>>
               
               <h2><?php the_title(); ?></h2>
               
               <?php the_content();?>
               
            </div>
            
					
            
            <?php endwhile;
            wp_reset_postdata(); ?>
        
        </div>
    
    <?php }
	    
    $myvariable = ob_get_clean();
    
    return $myvariable;
	
	}
}



// Static Page Shortcode

if ( ! function_exists( 'flux_static_page_shortcode' ) ) {


	add_shortcode( 'flux-tabs-page', 'flux_static_page_shortcode' );
	
	function flux_static_page_shortcode( $atts, $content = null ) {
		
		$atts = shortcode_atts( array(
       'selector' => '',
       ), $atts );
       
       ob_start(); 
	
		?>
		
		<div class="flux_tabs_page_wrapper">
			
					
			<div id="isotope">
				
				<?php echo $content;?>
			
			</div><!-- isotope -->
			
		</div><!-- flux_tabs_page_wrapper -->
	
	
	<?php $myvariabletwo = ob_get_clean();
    
    return $myvariabletwo; }

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

add_option( 'myplugin_field_cpt', 'Flux Tabs CPT' );
add_option( 'demo-radio', 2 );
add_option( 'demo-radiotwo', 2 );
add_option( 'myplugin_field_3', '#000000' );
add_option( 'myplugin_field_4', '#969696' );
add_option( 'myplugin_field_active', '#969696' );
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
       
        <p>Flux Tabs allows you to filter through Posts or Custom Post Types based on Tags.</p>
        
        <hr/>
        
        <h3>To Use:</strong></h3>
        
        <ul>
	        <li>1. Create Posts and assign various Tags to each Post.</li>
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
    
    
    function myplugin_settings_section_1_callback() { 
 
    echo "<p>Flux Tabs supports Custom Post Types and comes with one registered Custom Post Type for you to use. You can rename it or disable it below.</p><p><i>Future releases of Flux Tabs will allow for unlimited Custom Post Types to be used/created.</i></p>";
    
 		}
	
	
	
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
    
    add_settings_field(
    	"demo-radiotwo",
    	"SEO No Follow/No Index",
    	"demo_radiotwo_display",
    	"myplugin_settings",
    	"myplugin_settings_section_1");  
    
    
    register_setting("myplugin_settings_group", "demo-radiotwo");
    
    
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
       'myplugin_field_active',
       'Background Active Color',
       'myplugin_field_active_input',
       'myplugin_settings',
       'myplugin_settings_section_2'
    );
    
     
    register_setting( 'myplugin_settings_group', 'myplugin_field_active' );
    
    
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
    
    echo '<p><i><strong style="color:red">Note: After you enable or rename this Custom Post Type and hit Save Changes below, go to <a href="'.get_bloginfo('url').'/wp-admin/options-permalink.php">Settings --> Permalinks</a> and click Save Changes.</strong><br/> This will initiate your new Custom Post Type Name. Otherwise you will get 404 errors. </p><p><br/>For more information: <a href="https://typerocket.com/flushing-permalinks-in-wordpress/" target="_blank">Flushing Permalinks in Wordpress</a></i></p>';
    
   }


   function demo_radio_display()
			{
   		?>
        <input id="radio_one" type="radio" name="demo-radio" value="1" <?php checked(1, get_option('demo-radio'), true); ?>>Enable Custom Post Type<br/>
        <input id="radio_two" type="radio" name="demo-radio" value="2" <?php checked(2, get_option('demo-radio'), true); ?>>Disable Custom Post Type
        
      <?php
}


if(get_option('demo-radio') == 1) {
	
 add_action('init', 'flux_tabs');
  
  // Register Custom Post Type
  
  function flux_tabs() { 
	  
	  	$rename_cpt_url = sanitize_title(get_option('myplugin_field_cpt'));  
			$rename_cpt_title = esc_html(get_option('myplugin_field_cpt'));   

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



// Deregisters Custom Post Type if radio button selected

if(get_option('demo-radio') == 2) {
	
	function delete_post_type(){
  
  	unregister_post_type( 'flux_tabs' );
}


add_action('init','delete_post_type', 100);


}


// SEO Radio


function demo_radiotwo_display()
			{
				
				$flux_rename_cpt = esc_html(get_option('myplugin_field_cpt'));
				
   		?>
   		
	 			<input id="radio_three" type="radio" name="demo-radiotwo" value="1" <?php checked(1, get_option('demo-radiotwo'), true); ?>>Follow/Index for the Custom Post Type: <strong><?php echo $flux_rename_cpt;?></strong><br/>
        <input id="radio_four" type="radio" name="demo-radiotwo" value="2" <?php checked(2, get_option('demo-radiotwo'), true); ?>>No Follow/No Index for the Custom Post Type: <strong><?php echo $flux_rename_cpt;?></strong>
        
      <?php
}


if(get_option('demo-radiotwo') == 2) {
	
	function flux_nofollow() {
		
		if(is_singular('flux_tabs')) {
		
		$flux_seo = '<meta name=“robots” content=“noindex,nofollow”/>';
		
		echo $flux_seo;
		
		}
		
	}
	
	add_action('wp_head','flux_nofollow');
	
}



function myplugin_field_3_input() {
 
    
    echo( '<input type="text" name="myplugin_field_3" id="myplugin_field_3" value="'. get_option( 'myplugin_field_3' ) .'" />' ); 
}



function myplugin_field_4_input() {
 
    
    echo( '<input type="text" name="myplugin_field_4" id="myplugin_field_4" value="'. get_option( 'myplugin_field_4' ) .'" />' ); 
}


function myplugin_field_active_input() {
 
    
    echo( '<input type="text" name="myplugin_field_active" id="myplugin_field_active" value="'. get_option( 'myplugin_field_active' ) .'" />' ); 
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


add_action( 'wp_head', 'flux_tabs_internal_css_print' );

function flux_tabs_internal_css_print() {
		
		
	$settings_background = esc_html(get_option('myplugin_field_3'));
	$settings_background_hover = esc_html(get_option('myplugin_field_4'));
	$settings_background_active = esc_html(get_option('myplugin_field_active'));
	$settings_font_family = esc_html(get_option('myplugin_field_5'));
	$settings_text_color = esc_html(get_option('myplugin_field_6'));
	$settings_text_hover_color = esc_html(get_option('myplugin_field_7'));
	$settings_text_transform = esc_html(get_option('myplugin_field_2'));
	$settings_font_size = esc_html(get_option('myplugin_field_8'));
	$settings_font_weight = esc_html(get_option('myplugin_field_9'));
  
  print '<style type="text/css">.button_wrapper{text-align:left;margin:35px 0}.button_wrapper button:focus {outline:0;}.button_wrapper button{font-weight:'.$settings_font_weight.';font-size:'.$settings_font_size.';text-transform:'.$settings_text_transform.';color:'.$settings_text_color.';font-family:'.$settings_font_family.';border:none;padding:8px 20px;margin-bottom:4px;-webkit-transition:all .2s ease-in-out;transition:all .2s ease-in-out;cursor:pointer;margin-right:5px;background:'.$settings_background.'}.button_wrapper button#clearall{font-family:'.$settings_font_family.';background:#969696;color:#fff}.button_wrapper button#clearall:hover{background:'.$settings_background_hover.'}.button_wrapper button.active{background:'.$settings_background_active.'}button:hover {background:'.$settings_background_hover.'}</style>';
  
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




/////// CPT Genrator

function cpt_save_postdata() {
    global $post;
    if ($_POST['cpt-hidd'] == 'true') {
        $cp_public = get_post_meta($post->ID, 'cp_public', true);
        $cp_publicly_queryable = get_post_meta($post->ID, 'cp_publicly_queryable', true);
        $cp_show_ui = get_post_meta($post->ID, 'cp_show_ui', true);
        $cp_show_in_menu = get_post_meta($post->ID, 'cp_show_in_menu', true); 
        $cp_query_var = get_post_meta($post->ID, 'cp_query_var', true); 
        $cp_rewrite = get_post_meta($post->ID, 'cp_rewrite', true); 
        $cp_has_archive = get_post_meta($post->ID, 'cp_has_archive', true); 
        $cp_hierarchical = get_post_meta($post->ID, 'cp_hierarchical', true);
        $cp_capability_type = get_post_meta($post->ID, 'cp_capability_type', true);
        $cp_menu_position = get_post_meta($post->ID, 'cp_menu_position', true);
        $cp_s_title = get_post_meta($post->ID, 'cp_s_title', true);
        $cp_s_editor = get_post_meta($post->ID, 'cp_s_editor', true);
        $cp_s_author = get_post_meta($post->ID, 'cp_s_author', true);
        $cp_s_thumbnail = get_post_meta($post->ID, 'cp_s_thumbnail', true);
        $cp_s_excerpt = get_post_meta($post->ID, 'cp_s_excerpt', true);
        $cp_s_comments = get_post_meta($post->ID, 'cp_s_comments', true);
        $cp_general_name = get_post_meta($post->ID, 'cp_general_name', true);
        $cp_singular_name = get_post_meta($post->ID, 'cp_singular_name', true);
        $cp_add_new = get_post_meta($post->ID, 'cp_add_new', true);
        $cp_add_new_item = get_post_meta($post->ID, 'cp_add_new_item', true);
        $cp_edit_item = get_post_meta($post->ID, 'cp_edit_item', true);
        $cp_new_item = get_post_meta($post->ID, 'cp_new_item', true);
        $cp_all_items = get_post_meta($post->ID, 'cp_all_items', true);
        $cp_view_item = get_post_meta($post->ID, 'cp_view_item', true);
        $cp_search_items = get_post_meta($post->ID, 'cp_search_items', true);
        $cp_not_found = get_post_meta($post->ID, 'cp_not_found', true);
        $cp_not_found_in_trash = get_post_meta($post->ID, 'cp_not_found_in_trash', true);
        $cp_parent_item_colon = get_post_meta($post->ID, 'cp_parent_item_colon', true);

        update_post_meta($post->ID, 'cp_public', $_POST['cp_public'], $cp_public);
        update_post_meta($post->ID, 'cp_publicly_queryable', $_POST['cp_publicly_queryable'], $cp_publicly_queryable);
        update_post_meta($post->ID, 'cp_show_ui', $_POST['cp_show_ui'], $cp_show_ui);
        update_post_meta($post->ID, 'cp_show_in_menu', $_POST['cp_show_in_menu'], $cp_show_in_menu);
        update_post_meta($post->ID, 'cp_query_var', $_POST['cp_query_var'], $cp_query_var);
        update_post_meta($post->ID, 'cp_rewrite', $_POST['cp_rewrite'], $cp_rewrite);
        update_post_meta($post->ID, 'cp_has_archive', $_POST['cp_has_archive'], $cp_has_archive);
        update_post_meta($post->ID, 'cp_hierarchical', $_POST['cp_hierarchical'], $cp_hierarchical);
        update_post_meta($post->ID, 'cp_capability_type', $_POST['cp_capability_type'], $cp_capability_type);
        update_post_meta($post->ID, 'cp_menu_position', $_POST['cp_menu_position'], $cp_menu_position);
        update_post_meta($post->ID, 'cp_s_title', $_POST['cp_s_title'], $cp_s_title);
        update_post_meta($post->ID, 'cp_s_editor', $_POST['cp_s_editor'], $cp_s_editor);
        update_post_meta($post->ID, 'cp_s_author', $_POST['cp_s_author'], $cp_s_author);
        update_post_meta($post->ID, 'cp_s_thumbnail', $_POST['cp_s_thumbnail'], $cp_s_thumbnail);
        update_post_meta($post->ID, 'cp_s_excerpt', $_POST['cp_s_excerpt'], $cp_s_excerpt);
        update_post_meta($post->ID, 'cp_s_comments', $_POST['cp_s_comments'], $cp_s_comments);
        update_post_meta($post->ID, 'cp_general_name', $_POST['cp_general_name'], $cp_general_name);
        update_post_meta($post->ID, 'cp_singular_name', $_POST['cp_singular_name'], $cp_singular_name);
        update_post_meta($post->ID, 'cp_add_new', $_POST['cp_add_new'], $cp_add_new);
        update_post_meta($post->ID, 'cp_add_new_item', $_POST['cp_add_new_item'], $cp_add_new_item);
        update_post_meta($post->ID, 'cp_edit_item', $_POST['cp_edit_item'], $cp_edit_item);
        update_post_meta($post->ID, 'cp_new_item', $_POST['cp_new_item'], $cp_new_item);
        update_post_meta($post->ID, 'cp_all_items', $_POST['cp_all_items'], $cp_all_items);
        update_post_meta($post->ID, 'cp_view_item', $_POST['cp_view_item'], $cp_view_item);
        update_post_meta($post->ID, 'cp_search_items', $_POST['cp_search_items'], $cp_search_items);
        update_post_meta($post->ID, 'cp_not_found', $_POST['cp_not_found'], $cp_not_found);
        update_post_meta($post->ID, 'cp_not_found_in_trash', $_POST['cp_not_found_in_trash'], $cp_not_found_in_trash);
        update_post_meta($post->ID, 'cp_parent_item_colon', $_POST['cp_parent_item_colon'], $cp_parent_item_colon);
    }
}

function cpt_inner_custom_box() {
    global $post;

    $cp_public = get_post_meta($post->ID, 'cp_public', true);
    $cp_publicly_queryable = get_post_meta($post->ID, 'cp_publicly_queryable', true);
    $cp_show_ui = get_post_meta($post->ID, 'cp_show_ui', true);
    $cp_show_in_menu = get_post_meta($post->ID, 'cp_show_in_menu', true); 
    $cp_query_var = get_post_meta($post->ID, 'cp_query_var', true); 
    $cp_rewrite = get_post_meta($post->ID, 'cp_rewrite', true); 
    $cp_has_archive = get_post_meta($post->ID, 'cp_has_archive', true); 
    $cp_hierarchical = get_post_meta($post->ID, 'cp_hierarchical', true);
    $cp_capability_type = get_post_meta($post->ID, 'cp_capability_type', true);
    $cp_menu_position = get_post_meta($post->ID, 'cp_menu_position', true);
    $cp_s_title = get_post_meta($post->ID, 'cp_s_title', true);
    $cp_s_editor = get_post_meta($post->ID, 'cp_s_editor', true);
    $cp_s_author = get_post_meta($post->ID, 'cp_s_author', true);
    $cp_s_thumbnail = get_post_meta($post->ID, 'cp_s_thumbnail', true);
    $cp_s_excerpt = get_post_meta($post->ID, 'cp_s_excerpt', true);
    $cp_s_comments = get_post_meta($post->ID, 'cp_s_comments', true);
    $cp_general_name = get_post_meta($post->ID, 'cp_general_name', true);
    $cp_singular_name = get_post_meta($post->ID, 'cp_singular_name', true);
    $cp_add_new = get_post_meta($post->ID, 'cp_add_new', true);
    $cp_add_new_item = get_post_meta($post->ID, 'cp_add_new_item', true);
    $cp_edit_item = get_post_meta($post->ID, 'cp_edit_item', true);
    $cp_new_item = get_post_meta($post->ID, 'cp_new_item', true);
    $cp_all_items = get_post_meta($post->ID, 'cp_all_items', true);
    $cp_view_item = get_post_meta($post->ID, 'cp_view_item', true);
    $cp_search_items = get_post_meta($post->ID, 'cp_search_items', true);
    $cp_not_found = get_post_meta($post->ID, 'cp_not_found', true);
    $cp_not_found_in_trash = get_post_meta($post->ID, 'cp_not_found_in_trash', true);
    $cp_parent_item_colon = get_post_meta($post->ID, 'cp_parent_item_colon', true);
    ?>
    <h4>Main Settings:</h4>
    <table width="100%">
        <tr>
            <td><input type="checkbox" <?php
    if ($cp_public == "on") {
        echo "checked";
    }
    ?> name="cp_public" /> Public </td>
            <td><input type="checkbox" <?php
                   if ($cp_publicly_queryable == "on") {
                       echo "checked";
                   }
    ?> name="cp_publicly_queryable" /> Publicly Queryable </td>
            <td><input type="checkbox" <?php
                   if ($cp_show_ui == "on") {
                       echo "checked";
                   }
    ?> name="cp_show_ui" /> Show UI </td>
            <td><input type="checkbox" <?php
                   if ($cp_show_in_menu == "on") {
                       echo "checked";
                   }
    ?> name="cp_show_in_menu" /> Show in Menu </td>
            <td><input type="checkbox" <?php
                   if ($cp_query_var == "on") {
                       echo "checked";
                   }
    ?> name="cp_query_var" /> Query Var </td>
            <td><input type="checkbox" <?php
                   if ($cp_rewrite == "on") {
                       echo "checked";
                   }
    ?> name="cp_rewrite" /> Rewrite </td>
            <td><input type="checkbox" <?php
                   if ($cp_has_archive == "on") {
                       echo "checked";
                   }
    ?> name="cp_has_archive" /> Has Archive </td>
            <td><input type="checkbox" <?php
                   if ($cp_hierarchical == "on") {
                       echo "checked";
                   }
    ?> name="cp_hierarchical" /> Hierarchical </td>
        </tr>
    </table>
    <br/>
    <table>
        <tr>
            <td>Capability Type:<br/><select name="cp_capability_type">
                    <option value="5" <?php
                   if ($cp_capability_type == "5") {
                       echo "selected";
                   }
    ?>>below Posts</option>
                    <option value="10" <?php
                        if ($cp_capability_type == "10") {
                            echo "selected";
                        }
    ?>>below Media</option>
                    <option value="15" <?php
                        if ($cp_capability_type == "15") {
                            echo "selected";
                        }
    ?>>below Links</option>
                    <option value="20" <?php
                        if ($cp_capability_type == "20") {
                            echo "selected";
                        }
    ?>>below Pages</option>
                    <option value="25" <?php
                        if ($cp_capability_type == "25") {
                            echo "selected";
                        }
    ?>>below comments</option>
                    <option value="60" <?php
                        if ($cp_capability_type == "60") {
                            echo "selected";
                        }
    ?>>below first separator</option>
                    <option value="65" <?php
                        if ($cp_capability_type == "65") {
                            echo "selected";
                        }
    ?>>below Plugins</option>
                    <option value="70" <?php
                        if ($cp_capability_type == "70") {
                            echo "selected";
                        }
    ?>>below Users</option>
                    <option value="75" <?php
                        if ($cp_capability_type == "75") {
                            echo "selected";
                        }
    ?>>below Tools</option>
                    <option value="80" <?php
                        if ($cp_capability_type == "80") {
                            echo "selected";
                        }
    ?>>below Settings</option>
                    <option value="100" <?php
                        if ($cp_capability_type == "100") {
                            echo "selected";
                        }
    ?>>below second separator</option>

                </select></td>
            <td>Menu Position:<br/><select name="cp_menu_position">
                    <option value="post" <?php
                        if ($cp_menu_position == "post") {
                            echo "selected";
                        }
    ?>>Post</option>
                    <option value="page" <?php
                        if ($cp_menu_position == "page") {
                            echo "selected";
                        }
    ?>>Page</option>
                </select></td>
        </tr>
    </table>
    <h4>Supports:</h4>
    <table width="100%">
        <tr>
            <td><input type="checkbox" name="cp_s_title" <?php
                        if ($cp_s_title == "on") {
                            echo "checked";
                        }
    ?>/> Title </td>
            <td><input type="checkbox" name="cp_s_editor" <?php
                   if ($cp_s_editor == "on") {
                       echo "checked";
                   }
    ?>/> Editor  </td>
            <td><input type="checkbox" name="cp_s_author" <?php
                   if ($cp_s_author == "on") {
                       echo "checked";
                   }
    ?>/> Author </td> 
            <td><input type="checkbox" name="cp_s_thumbnail" <?php
                   if ($cp_s_thumbnail == "on") {
                       echo "checked";
                   }
    ?>/> Thumbnail  </td>
            <td><input type="checkbox" name="cp_s_excerpt" <?php
                   if ($cp_s_excerpt == "on") {
                       echo "checked";
                   }
    ?>/> Excerpt  </td>
            <td><input type="checkbox" name="cp_s_comments" <?php
                   if ($cp_s_comments == "on") {
                       echo "checked";
                   }
    ?>/> Comments  </td>
        </tr>
    </table>
    <h4>Lables:</h4>
    <table width="100%">
        <tr>
            <td>General name:<br/> <input type="text" name="cp_general_name" value="<?php echo $cp_general_name; ?>"/></td>
            <td>Singular name:<br/> <input type="text" name="cp_singular_name" value="<?php echo $cp_singular_name; ?>"/></td>
            <td>Add new:<br/> <input type="text" name="cp_add_new" value="<?php echo $cp_add_new; ?>"/></td>
        </tr>
        <tr>
            <td>Add new item:<br/> <input type="text" name="cp_add_new_item" value="<?php echo $cp_add_new_item; ?>"/></td>
            <td>Edit Item:<br/> <input type="text" name="cp_edit_item" value="<?php echo $cp_edit_item; ?>"/></td>
            <td>New Item:<br/> <input type="text" name="cp_new_item" value="<?php echo $cp_new_item; ?>"/></td>
        </tr>
        <tr>
            <td>All Items:<br/> <input type="text" name="cp_all_items" value="<?php echo $cp_all_items; ?>"/></td>
            <td>View Item:<br/> <input type="text" name="cp_view_item" value="<?php echo $cp_view_item; ?>"/></td>
            <td>Search Items:<br/> <input type="text" name="cp_search_items" value="<?php echo $cp_search_items; ?>"/></td>
        </tr>
        <tr>
            <td>Not Found:<br/> <input type="text" name="cp_not_found" value="<?php echo $cp_not_found; ?>"/></td>
            <td>Not Found in Trash:<br/> <input type="text" name="cp_not_found_in_trash" value="<?php echo $cp_not_found_in_trash; ?>"/></td>
            <td>Parent item Column:<br/> <input type="text" name="cp_parent_item_colon" value="<?php echo $cp_parent_item_colon; ?>"/></td>
        </tr>
    </table>
    <input type="hidden" name="cpt-hidd" value="true" />
    <?php
}

function init_custom_post_types() {
    $labels = array(
        'name' => _x('CPT', 'post type general name'),
        'singular_name' => _x('CPT', 'post type singular name'),
        'add_new' => _x('Add New CPT', 'CPT'),
        'add_new_item' => __('Add New Post type'),
        'edit_item' => __('Edit CPT'),
        'new_item' => __('New CPT'),
        'all_items' => __('All CPT'),
        'view_item' => __('View CPT'),
        'search_items' => __('Search CPT'),
        'not_found' => __('No CPT found'),
        'not_found_in_trash' => __('No CPT found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => __('CPT')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title')
    );
    register_post_type('CPT', $args);

    $the_query = new WP_Query(array('post_type' => array('CPT')));
    while ($the_query->have_posts()) : $the_query->the_post();
        global $post;
        //*************************get the values
        $cp_public = get_post_meta($post->ID, 'cp_public', true);
        if ($cp_public == "on") {
            $cp_public = true;
        } else {
            $cp_public = false;
        }
        $cp_publicly_queryable = get_post_meta($post->ID, 'cp_publicly_queryable', true);
        if ($cp_publicly_queryable == "on") {
            $cp_publicly_queryable = true;
        } else {
            $cp_publicly_queryable = false;
        }
        $cp_show_ui = get_post_meta($post->ID, 'cp_show_ui', true);
        if ($cp_show_ui == "on") {
            $cp_show_ui = true;
        } else {
            $cp_show_ui = false;
        }
        $cp_show_in_menu = get_post_meta($post->ID, 'cp_show_in_menu', true); //
        if ($cp_show_in_menu == "on") {
            $cp_show_in_menu = true;
        } else {
            $cp_show_in_menu = false;
        }
        $cp_query_var = get_post_meta($post->ID, 'cp_query_var', true); //
        if ($cp_query_var == "on") {
            $cp_query_var = true;
        } else {
            $cp_query_var = false;
        }
        $cp_rewrite = get_post_meta($post->ID, 'cp_rewrite', true); //
        if ($cp_rewrite == "on") {
            $cp_rewrite = true;
        } else {
            $cp_rewrite = false;
        }
        $cp_has_archive = get_post_meta($post->ID, 'cp_has_archive', true); //
        if ($cp_has_archive == "on") {
            $cp_has_archive = true;
        } else {
            $cp_has_archive = false;
        }
        $cp_hierarchical = get_post_meta($post->ID, 'cp_hierarchical', true);
        if ($cp_hierarchical == "on") {
            $cp_hierarchical = true;
        } else {
            $cp_hierarchical = false;
        }
        $cp_capability_type = get_post_meta($post->ID, 'cp_capability_type', true);
        $cp_menu_position = get_post_meta($post->ID, 'cp_menu_position', true);
        $cp_s_title = get_post_meta($post->ID, 'cp_s_title', true);
        if ($cp_s_title == "on") {
            $cp_s[] = 'title';
        }
        $cp_s_editor = get_post_meta($post->ID, 'cp_s_editor', true);
        if ($cp_s_editor == "on") {
            $cp_s[] = 'editor';
        }
        $cp_s_author = get_post_meta($post->ID, 'cp_s_author', true);
        if ($cp_s_author == "on") {
            $cp_s[] = 'author';
        }
        $cp_s_thumbnail = get_post_meta($post->ID, 'cp_s_thumbnail', true);
        if ($cp_s_thumbnail == "on") {
            $cp_s[] = 'thumbnail';
        }
        $cp_s_excerpt = get_post_meta($post->ID, 'cp_s_excerpt', true);
        if ($cp_s_excerpt == "on") {
            array_push($cp_s, 'excerpt');
        }
        $cp_s_comments = get_post_meta($post->ID, 'cp_s_comments', true);
        if ($cp_s_comments == "on") {
            array_push($cp_s, 'comments');
        }
        $cp_general_name = get_post_meta($post->ID, 'cp_general_name', true);
        $cp_singular_name = get_post_meta($post->ID, 'cp_singular_name', true);
        $cp_add_new = get_post_meta($post->ID, 'cp_add_new', true);
        $cp_add_new_item = get_post_meta($post->ID, 'cp_add_new_item', true);
        $cp_edit_item = get_post_meta($post->ID, 'cp_edit_item', true);
        $cp_new_item = get_post_meta($post->ID, 'cp_new_item', true);
        $cp_all_items = get_post_meta($post->ID, 'cp_all_items', true);
        $cp_view_item = get_post_meta($post->ID, 'cp_view_item', true);
        $cp_search_items = get_post_meta($post->ID, 'cp_search_items', true);
        $cp_not_found = get_post_meta($post->ID, 'cp_not_found', true);
        $cp_not_found_in_trash = get_post_meta($post->ID, 'cp_not_found_in_trash', true);
        $cp_parent_item_colon = get_post_meta($post->ID, 'cp_parent_item_colon', true);

        $labels = array(
            'name' => _x(get_the_title($post->ID), 'post type general name'),
            'singular_name' => _x($cp_singular_name, 'post type singular name'),
            'add_new' => _x($cp_add_new, get_the_title($post->ID)),
            'add_new_item' => __($cp_add_new_item),
            'edit_item' => __($cp_edit_item),
            'new_item' => __($cp_new_item),
            'all_items' => __($cp_all_items),
            'view_item' => __($cp_view_item),
            'search_items' => __($cp_search_items),
            'not_found' => __($cp_not_found),
            'not_found_in_trash' => __($cp_not_found_in_trash),
            'parent_item_colon' => __($cp_parent_item_colon),
            'menu_name' => __(get_the_title($post->ID))
        );
        $args = array(
            'labels' => $labels,
            'public' => $cp_public,
            'publicly_queryable' => $cp_publicly_queryable,
            'show_ui' => $cp_show_ui,
            'show_in_menu' => $cp_show_in_menu,
            'query_var' => $cp_query_var,
            'rewrite' => $cp_rewrite,
            'capability_type' => 'post',
            'has_archive' => $cp_has_archive,
            'hierarchical' => $cp_hierarchical,
            'menu_position' => $cp_menu_position,
            'supports' => $cp_s
        );
        register_post_type(get_the_title($post->ID), $args);

    endwhile;
}

function cpt_add_meta_boxes() {
    add_meta_box('cpt_meta_id', 'Custom Post Type Settings', 'cpt_inner_custom_box', 'CPT', 'normal');
}

add_action('save_post', 'cpt_save_postdata');
add_action('add_meta_boxes', 'cpt_add_meta_boxes');
add_action('init', 'init_custom_post_types');






