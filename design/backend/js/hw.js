
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

jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        };
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

var functionMap = {
    'hw_init_markit_up': function(a,b) {    
        mySettings.previewParserPath = a;
        $(b).markItUp(mySettings);    
    }
};

var _lactq = _lactq || [];

$(document).ready(function() { 
     
    $.each(_lactq, function(index, value) {
        functionMap[value.f].apply(hw,value.a);       
    });   
           
		
});