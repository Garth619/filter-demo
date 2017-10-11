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
										

                    }
                });
            },
        });
    });
 
})();

