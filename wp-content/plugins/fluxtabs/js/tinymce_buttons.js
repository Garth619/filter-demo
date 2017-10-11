(function($) {
		if ( $('.post-type-page').length ) {
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
	                            label  : '',
	                            html   : ''
	                        },
													{
	                            type   : 'listbox',
	                            name   : 'mypostlist',
	                            label  : 'Choose Your Post Type',
	                            values : [
	                                { text: "Posts", value: "post" },
	                                { text: 'Custom Post Type', value: 'flux_tabs' }
	                                
	                            ],
	                            value : 'posts' // Sets the default
	                        }                        
	                                       
	                                                       
	                    ],
	                    onsubmit: function( e ) {
		                    
											editor.insertContent( '[flux-tabs feed="'+e.data.mypostlist+'"]');
											
	
	                    }
	                });
	            },
	        });
	    });
		}
})(jQuery);

