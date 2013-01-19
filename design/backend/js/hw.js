
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

var hw = {
	votepath : 'gallery/addvote/',
	votedeductpath : 'gallery/deductvote/',
	updatepath : 'gallery/updateimage/',
	deletepath : 'gallery/deleteimage/',
	tagpath : 'gallery/tagphoto/',
	captcha_url: 'captcha/captchastring/comment/',
	deletecommentpath: 'gallery/deletecomment/',
	formAddPath: WWW_DIR_JAVASCRIPT,		
	appendURL : null,
		
	setPath : function (path)
	{		
		this.formAddPath = path;
	},
	
	getPath : function(path)
	{		
		return this.formAddPath;
	},
	
	processMap : function(map_id)
	{
		 var mapCanvas = $('#advertmap_'+map_id);
		 var address =   $("#Address_"+map_id);
		 var latitude =  $("#Latitude_"+map_id);
	     var longitude = $("#Longitude_"+map_id);
	
		 var geocoder = new google.maps.Geocoder();
	     var defaultLatLng = new google.maps.LatLng(51.5001524, -0.12623619999999391);
	
	     var map = new google.maps.Map(mapCanvas[0], {
	        zoom: 15,
	        center: defaultLatLng,
	        mapTypeId: google.maps.MapTypeId.ROADMAP,
	        options: {
	            scrollwheel: false
	        }
	    });
	
	    var marker = new google.maps.Marker({ position: defaultLatLng, map: map, icon:  "/design/defaulttheme/images/icons/map.png", draggable: true });
	    marker.setVisible(true);
	    
	    google.maps.event.addListener(marker, 'drag', function () {
	        geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
	            if (status == google.maps.GeocoderStatus.OK) {
	                if (results[0]) {
	                    address.val(results[0].formatted_address);
	                    latitude.val(marker.getPosition().lat());
	                    longitude.val(marker.getPosition().lng());                  
	                }
	            }
	        });
	    });
	    
	    google.maps.event.addListener(marker, 'dragstart', function () {
	      
	    });
	
	    var timeoutExecute = null;
	    
		address.keyup( function() {				
			clearTimeout(timeoutExecute);
			var locationText = $(this).val();				
			timeoutExecute = setTimeout(function(){
					GetLocation(locationText);
			}, 500 );
			
	    });
	    
		function GetLocation(address) {
	        var geocoder = new google.maps.Geocoder();
		        geocoder.geocode({ 'address': address }, function (results, status) {
		            if (status == google.maps.GeocoderStatus.OK) {	                            
		                var lat = results[0].geometry.location.lat().toString();
		                var lng = results[0].geometry.location.lng().toString();
		                latitude.val(lat);
		                longitude.val(lng);
		                updateMap();
		            }
		            else{
		              alert('error: '+status);
		          }
		   });
	    };
	    
	    function updateMap() {
	        var location = new google.maps.LatLng(latitude.val(), longitude.val());
	        marker.setPosition(location);
	        map.setCenter(location);	        
	    }
	    
	    if (address.val()) {
	        updateMap();
	    }
	},

	initLocationChoosing : function() {
		$('input[name=location_type]').change(function() {			
			$('.location-options').hide();			
			$('#location_type-'+$(this).val()).show();			
		});
	},
	
	addMap : function () {
		$.getJSON(this.formAddPath + 'pnadmin/addmap', function(data){	
			if (data.error == 'false')
			{	
				$('#map-custom-list').append(data.result);
				hw.processMap(data.map_id);
			} 
           return true;	          
		});	
	},
	
	vote : function (photo,score)
	{
		var pdata = {
				photo	:photo,
				score		: score				
		}

		$.postJSON(this.formAddPath + this.votepath, pdata , function(data){	
			if (data.error == 'false')
			{	
				$('#vote-content').html(data.result); 
			} 
           return true;	          
		});		
	},	
	
	loginOpenID : function() {    
        $('#googe-login-block').hide();
        $('#loading-block').fadeIn();    
        $.getJSON(this.formAddPath + 'user/loginwithgoogle', function(data){	
    			if (data.error == 'false')
    			{
    				 $('.open-id-block').prepend(data.result);
    				 $('#openid_message').submit();
    			}
               return true;	          
    	});	
    },
    
	deductvote : function (photo)
	{
		$.postJSON(this.formAddPath + this.votedeductpath + photo, function(data){	
			if (data.error == 'false')
			{	
				$('#vote-content').html(data.result); 
			} 
           return true;	          
		});		
	},
	
	setAppendURL : function(appendURLPar){
	    this.appendURL = appendURLPar;
	},
	
	addCheck : function (timestamp,pid)
	{
	    
	    $('#CommentButtomStore').attr("disabled","disabled");
	    var originalLabel = $('#CommentButtomStore').val();
	    $('#CommentButtomStore').val("Working...");
	    
	    var formAddPath = this.formAddPath;
	    
		$.getJSON(this.formAddPath + this.captcha_url+timestamp, function(data) {	                
			            
            var pdata = {				
				Name	     :$('#IDName').val(),				
				CommentBody  :$('#IDCommentBody').val()								
		    }		    
		    pdata["captcha_"+data.result] = timestamp;
		    
            $.postJSON(formAddPath + 'gallery/addcomment/'+pid, pdata , function(data) {                
                $('.error-list').remove();
                $('.ok').remove(); 
                
                if (data.error == 'true') {
                    $('.comment-form').prepend(data.status);
                } else { 
                    $('#comments-list').html(data.comments);
                    $('.comment-form').prepend(data.status);
                    $('#com_'+data.id).fadeIn(1500);
                    $('#IDName').val('');
                    $('#IDCommentBody').val('');
                }          
                
                $('#CommentButtomStore').removeAttr('disabled');
                $('#CommentButtomStore').val(originalLabel);
                
            });	            	
            
		});		  		
		return false;	
	},
	
	tagphoto : function (photoid)
	{
		var pdata = {
				photo	:photoid,
				tags	:$('#IDtagsPhoto').val()				
		}

		$.postJSON(this.formAddPath + this.tagpath, pdata , function(data){	
			if (data.error == 'false')
			{	
				 $('#tags-container').html(data.result);
			} 
           return true;	          
		});	
		return false;	
	},
	
	getDependNotice : function(newspaper_group_id) {
		
		//$('#council_depend_content').html('Loading...');
		$('#newspaper_depend_content').html('Loading...');
		
		$.postJSON(this.formAddPath + 'pn/getdependnotice/' + newspaper_group_id, function(data){	
 		   if (data.error == 'false')
 		   {	
 			  //$('#council_depend_content').html(data.council_depend_content);
 			  $('#newspaper_depend_content').html(data.newspaper_depend_content);
 		   } 
           return true;	          
 		});
	},
	
	displayContacts : function(contact_id) { 
		$.postJSON(this.formAddPath + 'pnadmin/getcontactdetails/' + contact_id, function(data){	
	 		   if (data.error == 'false')
	 		   {	
	 			  $('#contacts_summary').html(data.result);
	 		   } 
	           return true;	          
	 		});
	},
	
	addContact : function(council_id) {
		
		//$.colorbox({href:this.formAddPath + 'pnadmin/addcontact/'+council_id,iframe:true,width:400,height:300});
		if ($("#dialog-content").hasClass("ui-dialog-content")) {	    
		       $("#dialog-content").dialog('destroy');	 
		       $("#dialog-content").remove();
		};
		
		var d = $('<div id="dialog-content" style="overflow:hidden"></div>').html('<iframe width="450" height="300" hspace=0 vspace=0 border=0 frameborder=0 marginheight=0 scrolling=no allowtransparency=true marginwidth=0 id="ifrm"></iframe>')
			.dialog({
            title: 'New contact',
            modal: false,
            autoOpen: true,
            width: 450
        });
		
        $("#ifrm").attr("src", this.formAddPath + 'pnadmin/addcontact/'+council_id); 
        
		return false;				
	},
	
	closeDialog : function() {
		    		
			parent.$("#dialog-content").dialog('close');	 
			parent.$("#dialog-content").remove();	
		
	},
	
	contactSaved : function(council_id,contact_id) {	
		parent.hw.getContacts(council_id,contact_id);
		
		parent.$("#dialog-content").dialog('close');	 
		parent.$("#dialog-content").remove();
	},
	
	getContacts : function(council_id,contact_id) { 
		
		$.postJSON(this.formAddPath + 'pnadmin/getcontactoptions/' + council_id + '/' + contact_id, function(data){	
 		   if (data.error == 'false')
 		   {
 			  $('#id_contact_options').html(data.result);
 			  
 			  hw.displayContacts(contact_id);
 		   } 
           return true;	          
 		});
		
	},
	
	deleteComment : function (comment_id)
	{		
	    if ( confirm('Are you sure?') ) {
    		$.postJSON(this.formAddPath + this.deletecommentpath + comment_id, function(data){	
    		   if (data.error == 'false')
    		   {	
    				 $('#comment_row_id_'+comment_id).fadeOut();
    		   } 
               return true;	          
    		});	
	    }
		return false;	
	},
	
	getalbumcacheinfo : function (album_id)
	{		
		$.postJSON(this.formAddPath + 'system/albumcacheinfo/'+album_id,  function(data) {			    
		   $('#information-block-album').html(data.result);			
           return true;	          
		});	
		return false;	
	},
	
	rotateImage : function (image_id,action)
	{		
	    $('#pid_thumb_'+image_id).addClass('loading-item');
	    $('#pid_thumb_'+image_id+' > a > img').hide();
	    
		$.getJSON(this.formAddPath + 'gallery/rotate/'+image_id + '/' + action,  function(data) {
		    $('#pid_thumb_'+image_id+' > a > img').show();		
		    $('#pid_thumb_'+image_id+' > a > img').attr('src',$('#pid_thumb_'+image_id+' > a > img').attr('src')+'?time='+data.time);	
		    $('#pid_thumb_'+image_id).removeClass('loading-item');	 
           return true;	          
		});	
		return false;	
	},
	
	rotateImageFromEditWindow : function (image_id,action)
	{		
	    $('.main').fadeTo('slow', 0.5);
		$.getJSON(this.formAddPath + 'gallery/rotate/'+image_id + '/' + action,  function(data) {		    
		    $('.main').fadeTo('slow', 1);
		    $('.main').attr('src',$('.main').attr('src')+'?time='+data.time);	
           return true;	          
		});	
		return false;	
	},
	
	getcategorycacheinfo : function (category_id)
	{		
		$.postJSON(this.formAddPath + 'system/categorycacheinfo/'+category_id,  function(data) {			    
		   $('#information-block-category').html(data.result);			
           return true;	          
		});	
		return false;	
	},
	
	clearimagecache : function (image_id)
	{		
		$.postJSON(this.formAddPath + 'system/clearimagecache/'+image_id,  function(data) {			    
		   alert("Cache cleared!");			
           return true;	          
		});	
		return false;	
	},
	
	updatePhoto : function(photo_id){
	    var pdata = {
				title	  : $('#PhotoTitle_'+photo_id).val(),
				keywords  : $('#PhotoKeyword_'+photo_id).val(),				
				caption	  : $('#PhotoDescription_'+photo_id).val(),				
				anaglyph  : $('#PhotoAnaglyph_'+photo_id).attr('checked'),
				approved  : $('#PhotoApproved_'+photo_id).attr('checked')				
		}
		$('#image_status_'+photo_id).html('Updating...');
		$('#image_status_'+photo_id).removeClass('ok');
        $.postJSON(this.formAddPath + this.updatepath+photo_id, pdata , function(data){	
			if (data.error == 'false')
			{	
				$('#image_status_'+photo_id).html(data.result); 
				$('#image_status_'+photo_id).addClass('ok');
				
			} 
           return true;	          
		});		 
	},
	
	deletePhoto : function(photo_id){
	    
	    if (confirm('Are you sure?')) {
            $.postJSON(this.formAddPath + this.deletepath+photo_id, {} , function(data){	
    			if (data.error == 'false')
    			{	
    				$('#image_thumb_'+photo_id).fadeOut();				
    			} 
                         
    		});		
	    }
		return false;	
	},
	
	deletePhotoQuick : function(photo_id,message){
	    
	    if (confirm('Are you sure?')) {
            $.postJSON(this.formAddPath + this.deletepath+photo_id, {} , function(data){	
    			if (data.error == 'false')
    			{	
    				alert(message);			
    			} 
                         
    		});		
	    }
		return false;	
	},
	
	confirm : function(question){	    
       return confirm(question);
	},
	
	getimages : function(url,direction) {	
        $.getJSON(url + "/(direction)/"+direction, {} , function(data){	
            if (data.error != 'true')			
			$('#ajax-navigator-content').html(data.result);	
		});			
		return false;	
	},
	
	expandBlock : function(idBlock){
	    
	    $('.duplicates-row').hide();	    
	    $('#details-block-'+idBlock).fadeIn();
	},
	
	editComment : function(msg_id){
	    
	    $.getJSON(this.formAddPath + "gallery/editcomment/"+msg_id+"/(action)/form", {} , function(data){	
            if (data.error != 'true'){			
			     $('#comment_edit_body_'+msg_id).html(data.result);	
            }
		});			
		return false;
	},
	
	setFolderImage : function (image_id)
	{			   	    
		$.getJSON(this.formAddPath + 'gallery/setfolderthumb/'+image_id,  function(data) {		    	 
		    alert(data.result);
            return true;	          
		});	
		return false;	
	},
	
	chooseExisting : function(notice_id) {
		 $.colorbox({href:this.formAddPath + 'pnadmin/choosefile/'+notice_id,iframe:true,width:750,height:800});
	},
	
	chooseFile : function(file_id) {				
		parent.$('#chosen-file-data').val(file_id);
		parent.hw.updateFileSummary(file_id);
		parent.$.colorbox.close();
	},
	
	updateFileSummary : function(file_id) { 
		$.getJSON(this.formAddPath + 'pnadmin/filesummary/'+file_id, function(data) {				
			if ( data.error == 'false' ) {
			    $('#chosen-file-data-summary').html(data.result);			   
			};
		});		
	},
	
	openParsedData : function(file_id) { 
		window.open(this.formAddPath + 'pnadmin/pdfdata/'+file_id,'file_id'+file_id,"menubar=1,resizable=1,width=750,height=750"); 
	},
	
	openInFullscreen : function(file_id) { 
		window.open(this.formAddPath + 'pnadmin/editpnpdf/'+file_id+'/(fullscreen)/1','file_id_full'+file_id,"menubar=1,resizable=1,width=750,height=750"); 
		return false;
	},
	
	openInFullscreenIframe : function(file_id) { 
		window.open(this.formAddPath + 'pnadmin/editpnpdfiframe/'+file_id,'file_id_full_iframe'+file_id,"menubar=1,resizable=1,width=750,height=750"); 
		return false;
	},
	
	setNewImage : function (image_id)
	{	
	    $.colorbox({href:this.formAddPath + 'gallery/switchimage/'+image_id,iframe:true,width:400,height:300});
	},
	
	setNewImageFull : function (image_id)
	{	
	    $.colorbox({href:this.formAddPath + 'gallery/switchimage/'+image_id+'/(type)/full',iframe:true,width:400,height:300});
	},
	
	moveImageToAnotherAlbum : function (image_id)
	{	
	    $.colorbox({href:this.formAddPath + 'gallery/moveimage/'+image_id,iframe:true,width:400,height:300});
	},
	
	goToPage : function(url)
	{	 
        $.getJSON(url, function(data) {				
			if (data.error == 'false') {
			    $('#'+data.container).html(data.result);
			    document.location = '#'+data.scrollto;			   
			};        
		});
	},
	
	updateComment : function(msg_id){	    
	    var pdata = {
	       'msg_author' : $('#MessageAuthor_'+msg_id).val(),       
	       'msg_body' : $('#MessageBody_'+msg_id).val() 
	    };
		    
	    $.postJSON(this.formAddPath + "gallery/editcomment/"+msg_id+"/(action)/edit", pdata , function(data){	
            if (data.error != 'true') {
			     $('#comment_edit_body_'+msg_id).html(data.result);	
            }
		});			
		return false;
	},
	
	initSortBox : function(name)
	{
	    $(document).ready(function() {
            $(name+" .current-sort").mouseenter(function() {
            $(name+" .sort-box").fadeIn();
            $(name+' .choose-sort').addClass('active-sort'); 
          }).mouseleave(function() {
            $(name+' .sort-box').hide();
            $(name+' .choose-sort').removeClass('active-sort');
          });
          if ($(name+' .sort-box .selor').size() > 0) {
              $(name+' .choose-sort span').addClass($(name+' .sort-box .selor').hasClass('ar') ? 'ar-ind' : 'da-ind');                                      
              var lengthString = $(name+' .sort-box .selor').text().length;  
              var stringName = $(name+' .sort-box .selor').text();                                         
              if (lengthString > 10){
                  stringName = stringName.substring(0, 10)+'...';
              };                        
              $(name+' .choose-sort span').text(stringName);                
              
          };
        });
	},
	
	showFullImage : function(inst)
	{	 	   		   
	     var orig = inst.attr('href');
         inst.attr('href',inst.find('img').attr('src'));     

         inst.find('img').remove();       
         var imgFull = $('<img/>').attr({
                'src': orig,
                'class':'main'
            }).appendTo(inst);	                       

        if (!inst.hasClass('full-mode')) {  
            
            inst.attr('scrtop',$(window).scrollTop());  
            
            var imageWidth = parseInt(inst.attr('rel'));
                    
            $('<a/>').attr({
                'id': 'close-zoom',
                'title' : 'Return to normal size',
                'rel':imageWidth
            }).click(function(){
                hw.showFullImage(inst);
            }).prependTo($('#img-view .img')); 
               
            $(window).resize(function() {                    
              $('#img-view').css({'max-width':($('#container').width())+'px'});
              
               var scaleWidth = imageWidth;
               if ($('#container').width()-60 <= imageWidth) {
                    scaleWidth = $('#container').width()-60;
                    imgFull.attr('width',$('#container').width()-60);
               } else {
                   imgFull.attr('width',imageWidth);
               }            
               
               $('#zoom-percent').text(Math.round(((scaleWidth/imageWidth)*100))+'%').attr('style','margin-left:'+(scaleWidth-91) +'px');
               
               if ($('#container').width() > imageWidth){
                    $('.navigator-image').css('padding-left',(($('#container').width()-imageWidth-60)/2)+'px');              
               }
            });     
                 
            inst.attr('rel',$('#container').width());
            inst.find('img').attr('title','Return to normal size');
            $('#container').css('width','98%');
            
            if ($('#container').width() > imageWidth){
                $('#img-view .img').css({width:(imageWidth+18),'margin':'0 auto','float':'none'});              
            }
            
            if ($('#container').width() > imageWidth){
                $('.navigator-image').css('padding-left',(($('#container').width()-imageWidth-60)/2)+'px');              
            }
            
            inst.addClass('full-mode');
            $('#img-view .hide-full').hide();  
            $('#leftmenucont').hide();  
            $('#container').addClass('no-left-column'); 
            $('#img-view').css({'max-width':($('#container').width())+'px','overflow':'hidden'});
            $('#ajax-navigator-content').css({'margin':'0 auto'});
            
            var widthMargin = imageWidth;
            var scaleWidth = imageWidth;
            if ($('#container').width()-60 <= imageWidth){ 
                scaleWidth = $('#container').width()-60;
                imgFull.attr('width',$('#container').width()-60);
                widthMargin = $('#container').width()-60;
            }            
            widthMargin = widthMargin - 91;            
            $('<a/>').attr({
                'id': 'zoom-percent',
                'title' : 'Indicates current zoom percent, click to open full image in new window',
                'target' :'_blank',
                'href' : orig,
                'style':'margin-left:'+widthMargin +'px'
            }).text(Math.round(((scaleWidth/imageWidth)*100))+'%').prependTo($('#img-view .img'));
            
            
        } else {                 
            
            inst.find('img').attr('title','Click to see full image');
            $('#container').css('width',inst.attr('rel')+'px');            
            inst.removeClass('full-mode');
            $('#leftmenucont').show(); 
            $('#container').removeClass('no-left-column'); 
            $('#img-view .hide-full').show();
            $('#img-view').css({'max-width':'auto','overflow':'visible'});
            inst.attr('rel',$('#close-zoom').attr('rel'));
            $('#close-zoom').remove();            
            $('#zoom-percent').remove();            
            $('#img-view .img').css({width:'auto','margin':'0','float':'left'});            
            $('html, body').scrollTop(parseInt(inst.attr('scrtop')));            
            $('.navigator-image').css('padding-left','0');
        }         
        return false;        	   
	}
		
};

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