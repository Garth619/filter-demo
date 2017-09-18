<?php 



/* jQuery from Google
-------------------------------------------------------------- */


if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js", false, null,true);
   wp_enqueue_script('jquery');
}


/* Enqueued Scripts
-------------------------------------------------------------- */



 function load_my_styles_scripts() {
     // Load my stylesheet
     wp_enqueue_style( 'styles', get_template_directory_uri() . '/style.css', '', 1, 'all' ); 

     // Load my javascripts
     wp_enqueue_script( 'jquery-addon', get_template_directory_uri() . '/js/custom-min.js', array('jquery'), '', true );
 }
 
 add_action( 'wp_enqueue_scripts', 'load_my_styles_scripts', 20 );
 
 
 // Critical Styles in the header
 
 
/*
 function internal_css_print() {
    echo '<style type="text/css">';
    include_once get_template_directory() . '/critical.css';
    echo '</style>';
}
 
 
 
 add_action( 'wp_head', 'internal_css_print' );
*/



/* Force Gravity Forms to init scripts in the footer and ensure that the DOM is loaded before scripts are executed
-------------------------------------------------------------- */


add_filter( 'gform_init_scripts_footer', '__return_true' );
add_filter( 'gform_cdata_open', 'wrap_gform_cdata_open', 1 );
function wrap_gform_cdata_open( $content = '' ) {
if ( ( defined('DOING_AJAX') && DOING_AJAX ) || isset( $_POST['gform_ajax'] ) ) {
return $content;
}
$content = 'document.addEventListener( "DOMContentLoaded", function() { ';
return $content;
}
add_filter( 'gform_cdata_close', 'wrap_gform_cdata_close', 99 );
function wrap_gform_cdata_close( $content = '' ) {
if ( ( defined('DOING_AJAX') && DOING_AJAX ) || isset( $_POST['gform_ajax'] ) ) {
return $content;
}
$content = ' }, false );';
return $content;
}




/* Remove Unnecessary Scripts
-------------------------------------------------------------- */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');

/* Register Nav-Menus
-------------------------------------------------------------- */

register_nav_menus(array(
    'main_menu' => 'Main Menu',
    
));

/* Widgets
-------------------------------------------------------------- */

if (function_exists('register_sidebars')) {

    register_sidebar(array(
        'name' => 'Sidebar',
        'id' => 'sidebar',
        'description' => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
    ));

 }

/* Add Theme Support Page Thumbnails
-------------------------------------------------------------- */

add_theme_support('post-thumbnails');

/* Modify the_excerpt() " read more "
-------------------------------------------------------------- */

function new_excerpt_more($more)
{
    global $post;
    return '... <a href="' . get_permalink($post->ID) . '">' . 'read more' . '</a>';
}

add_filter('excerpt_more', 'new_excerpt_more');

/* Add Page Slug to Body Class
-------------------------------------------------------------- */
function add_slug_body_class($classes)
{
    global $post;
    if (isset($post)) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}

add_filter('body_class', 'add_slug_body_class');



/* ACF: CREATE OPTIONS PAGE
-------------------------------------------------------------- */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Theme General Settings',
        'menu_title' => 'Theme Settings',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

/* ALLOW SVGs IN MEDIA UPLOAD
-------------------------------------------------------------- */
function cc_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

add_filter('upload_mimes', 'cc_mime_types');







/*




function my_plugin_tags() {

	if( is_main_query() ) {


		$posttags = get_the_tags();
 
			if ($posttags) {
				
				
	 
				foreach($posttags as $tag) {
		 
					$fnal_tags = $tag->slug;
		 
				}
				
			
		 
			}

	}
	

    
}


 add_filter( 'post_class', 'my_plugin_tags' );
 
*/
 
 
/*
 function my_plugin_tags() {

	if( is_main_query() ) {


		$posttags = get_the_tags();
 
			if ($posttags) {
				
				echo 'class="';
	 
				foreach($posttags as $tag) {
		 
					echo $tag->slug . ' ';
		 
				}
				
				echo '"';
		 
			}

	}
	

    
}


 add_filter( 'the_content', 'my_plugin_tags' );
*/






/*
function pn_body_class_add_categories( $classes ) {
 
	// Only proceed if we're on a single post page
	if ( !is_single() )
		return $classes;
 
	// Get the categories that are assigned to this post
	$post_categories = get_the_category();
 
	// Loop over each category in the $categories array
	foreach( $post_categories as $current_category ) {
 
		// Add the current category's slug to the $body_classes array
		$classes[] = 'category-' . $current_category->slug;
 
	}
 
	// Finally, return the $body_classes array
	return $classes;
}



add_filter( 'body_class', 'pn_body_class_add_categories' );
*/













