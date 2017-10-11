(function() {
    tinymce.PluginManager.add('mybutton', function( editor, url ) {
        editor.addButton( 'mybutton', {
            title: 'Flux Tabs',
            // autofocus: true,
            image: url + '/../1p21.png',
            onclick: function() {
                editor.windowManager.open( {
                    title: tinyMCE_object.button_title,
                    body: [
	                    
	                    	{
                            type   : 'container',
                            name   : 'container',
                            label  : 'Turn Posts into Filterd Tabs Based on Tags',
                            html   : ''
                        },
												{
                            type   : 'listbox',
                            name   : 'mypostlist',
                            label  : 'Choose Your Post Type',
                            values : [
                                { text: "Blog", value: "post" },
                                { text: 'Flux Tabs CPT', value: 'flux_tabs' }
                                
                            ],
                            value : 'posts' // Sets the default
                        }                        
                                       
                                                       
                    ],
                    onsubmit: function( e ) {
	                    
										editor.insertContent( '[flux-tabs feed="'+e.data.mypostlist+'"]');
										
										
/*
										
       'font-size' => '16px',
       'background' => '#000',
       'font-family' => 'helvetica',
       'color' => '#fff',
       'text-transform' => 'uppercase',
       'font-weight' => 'bold'
										
                    
*/
                    }
                });
            },
        });
    });
 
})();




/*
jQuery(document).ready(function($){
    $(document).on('click', '.mce-my_upload_button', upload_image_tinymce);
 
    function upload_image_tinymce(e) {
        e.preventDefault();
        var $input_field = $('.mce-my_input_image');
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Add Video',
            button: {
                text: 'Add Video'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $input_field.val(attachment.url);
        });
        custom_uploader.open();
    }
});
*/




