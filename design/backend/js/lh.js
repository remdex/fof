
$.postJSON = function(url, data, callback) {
	$.post(url, data, callback, "json");
};

function lh(){

    this.wwwDir = WWW_DIR_JAVASCRIPT;
          
    this.setwwwDir = function (wwwdir){
        this.wwwDir = wwwdir;
    }
    
	
	this.abstractDialog = function(element_id,title,url)
	{
	    if ($("#"+element_id).hasClass("ui-dialog-content"))
	    {	    
	       $("#"+element_id).dialog('open');
	       
	    } else {	
	     
        $("#"+element_id).load(url).dialog({
             title: title,
             modal: true,
             autoOpen: true,                 
             width: 500
          }); 
	    }	   
	}
	
	this.abstractDialogFrame = function(element_id,title,url)
	{
	    if ($("#"+element_id).hasClass("ui-dialog-content"))
	    {	    
	       $("#"+element_id).dialog('open');
	       
	    } else {	
	        
	     var d = $("#"+element_id).html('<iframe width="460" height="370" hspace=0 vspace=0 border=0 frameborder=0 marginheight=0 scrolling=no allowtransparency=true marginwidth=0 id="ifrm"></iframe>');                 
         d.dialog({
             title: title,
             modal: true,
             autoOpen: true,                 
             width: 500
         });           
         $("#"+element_id+">#ifrm").attr("src", url); 
         
          
	    }	   
	}   
    
}

var lhinst = new lh();