!function(){tinymce.PluginManager.add("mybutton",function(e,t){e.addButton("mybutton",{title:"Flux Tabs",image:t+"/../1p21.png",onclick:function(){e.windowManager.open({title:tinyMCE_object.button_title,body:[{type:"container",name:"container",label:"Turn Posts into Filterd Tabs Based on Tags",html:""},{type:"listbox",name:"mypostlist",label:"Choose Your Post Type",values:[{text:"Blog",value:"post"},{text:"Flux Tabs",value:"flux_tabs"}],value:"posts"},{type:"textbox",name:"fontsize",label:"Tab Font Size (i.e. 17px)",value:"16px",classes:""},{type:"textbox",name:"background",label:"Tab Background Color (Hex Colors i.e #3e3e3e)",value:"#000",classes:""},{type:"textbox",name:"fontfamily",label:'Tab Font Family (i.e. arial,"Sans-Serif")',value:"arial",classes:""},{type:"textbox",name:"textcolor",label:"Tab Text Color (Hex Colors i.e #3e3e3e)",value:"#fff",classes:""},{type:"listbox",name:"fontweight",label:"Tab Font Weight",values:[{text:"Normal",value:"normal"},{text:"Bold",value:"bold"}],value:"bold"},{type:"listbox",name:"texttransform",label:"Tab Text Transform",values:[{text:"Capitalize",value:"capitalize"},{text:"Lowercase",value:"lowercase"},{text:"Uppercase",value:"uppercase"}],value:"uppercase"}],onsubmit:function(t){e.insertContent('[flux-tabs feed="'+t.data.mypostlist+'" font-size="'+t.data.fontsize+'" background="'+t.data.background+'" font-family="'+t.data.fontfamily+'" color="'+t.data.textcolor+'" text-transform="'+t.data.texttransform+'" font-weight="'+t.data.fontweight+'"]')}})}})})}();