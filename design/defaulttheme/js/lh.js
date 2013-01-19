
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
    
}

var lhinst = new lh();