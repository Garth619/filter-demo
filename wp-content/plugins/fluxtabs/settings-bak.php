// https://konstantin.blog/2012/the-wordpress-settings-api/

// Example Two


// https://codesymphony.co/using-the-wordpress-settings-api/

/** Set Defaults **/
// add_option( 'myplugin_field_1', 'some default value' );
add_option( 'myplugin_field_2', '30' );
add_option( 'myplugin_field_3', 'another default value' );
 
/** Add Settings Page **/
function myplugin_settings_menu() {
 
    add_options_page(
    /*1*/   'My Plugin Settings',
    /*2*/   'My Plugin',
    /*3*/   'manage_options',
    /*4*/   'myplugin_settings',
    /*5*/   'myplugin_settings_page'
    );
 
}
add_action( 'admin_menu', 'myplugin_settings_menu' );
 

 
/** Settings Page Content **/
function myplugin_settings_page() {
 
    ?>
 
    <div class="wrap">
       
 
        <h2>My Plugin</h2>
        <p>Some text describing what the plugin settings do.</p>
 
        <form method="post" action="options.php">
            <?php
 
            // Output the settings sections.
            do_settings_sections( 'myplugin_settings' );
 
            // Output the hidden fields, nonce, etc.
            settings_fields( 'myplugin_settings_group' );
 
            // Submit button.
            submit_button();
 
            ?>
        </form>
    </div>
 
    <?php
}
 
/** Settings Initialization **/
function myplugin_settings_init() {
 
          
   /** Section 2 **/
    add_settings_section(
    /*1*/   'myplugin_settings_section_2',
    /*2*/   'Section 2',
    /*3*/   'myplugin_settings_section_2_callback',
    /*4*/   'myplugin_settings'
    );
     
    // Field 2.
    add_settings_field(
    /*1*/   'myplugin_field_2',
    /*2*/   'Field 2',
    /*3*/   'myplugin_field_2_input',
    /*4*/   'myplugin_settings',
    /*5*/   'myplugin_settings_section_2'
    );
 
    // Register this field with our settings group.
    register_setting( 'myplugin_settings_group', 'myplugin_field_2' );  
     
    // Field 3.
    add_settings_field(
    /*1*/   'myplugin_field_3',
    /*2*/   'Field 3',
    /*3*/   'myplugin_field_3_input',
    /*4*/   'myplugin_settings',
    /*5*/   'myplugin_settings_section_2'
    );
 
    // Register this field with our settings group.
    register_setting( 'myplugin_settings_group', 'myplugin_field_3' );
}
add_action( 'admin_init', 'myplugin_settings_init' );
 
 
function myplugin_settings_section_2_callback() {
 
    echo( 'An explanation of this section.' );
}
 

/** Field 2 Input **/
function myplugin_field_2_input() {
 
    // This example input will be a dropdown.
    // Available options.
    $options = array(
        '1' => 'Option 1',
        '2' => 'Option 2',
        '3' => 'Option 3',
    );
     
    // Current setting.
    $current = get_option( 'myplugin_field_2' );
     
    // Build <select> element.
    $html = '<select id="myplugin_field_2" name="myplugin_field_2">';
 
    foreach ( $options as $value => $text )
    {
        $html .= '<option value="'. $value .'"';
 
        // We make sure the current options selected.
        if ( $value == $current ) $html .= ' selected="selected"';
 
        $html .= '>'. $text .'</option>';
    }
     
    $html .= '</select>';
 
    echo( $html );  
}
 
/** Field 3 Input **/
function myplugin_field_3_input() {
 
    // Output the form input, with the current setting as the value.
    echo( '<input type="text" name="myplugin_field_3" id="myplugin_field_3" value="'. get_option( 'myplugin_field_3' ) .'" />' ); 
}


