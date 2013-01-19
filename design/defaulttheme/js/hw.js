$.postJSON = function(url, data, callback) {
	$.post(url, data, callback, "json");
};
$.fn.reverse = [].reverse;

var hw = {
	votepath : 'gallery/addvote/',
	updatepath : 'gallery/updateimage/',
	deletepath : 'gallery/deleteimage/',
	tagpath : 'gallery/tagphoto/',	
	addtofavorites : 'gallery/addtofavorites/',
	deletefavorite : 'gallery/deletefavorite/',
	ajaximages : 'gallery/ajaximages/',
	captcha_url: 'captcha/captchastring/comment/',
	appendURL : null,
	formAddPath: WWW_DIR_JAVASCRIPT,		
	myTimer : false,
	myTimerAlbum:false,
	delayTime: false,
	delayTimeAlbum:false,
	fetchingInfo: false,	
	processing:false,
	
	setPath : function (path)
	{		
		this.formAddPath = path;
	},
	
	getPath : function(path)
	{		
		return this.formAddPath;
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
	    
        $.postJSON(this.formAddPath + this.deletepath+photo_id, {} , function(data){	
			if (data.error == 'false')
			{	
				$('#image_thumb_'+photo_id).fadeOut();				
			} 
                     
		});		
		
		return false;	
	},
	
	confirm : function(question){	    
       return confirm(question);
	},
	
	setAppendURL : function(appendURLPar){
	    this.appendURL = appendURLPar;
	},
	
	initAjaxNavigation : function(){
	   $('.right-ajax a').click(function(){
            hw.getimages($(this).attr('rel'),'right');
           return false;
        });
        $('.left-ajax a').click(function(){
            hw.getimages($(this).attr('rel'),'left');
           return false;
        });
        
        
        $('.ad-html').colorbox();
        $('.ad-phpbb').colorbox();
        
        /*
        $("#images-ajax-container").mousewheel(function(event, delta) {
          if (delta < 0) { 
              if ($('.right-ajax:visible a').size() > 0) {
                hw.getimages($('.right-ajax a').attr('rel'),'right');
                event.preventDefault();
              }
          } else {
              if ($('.left-ajax:visible a').size() > 0) {
                hw.getimages($('.left-ajax:visible a').attr('rel'),'left');
                event.preventDefault();
              }
          } 
        });*/
	      
	},
	
	getimages : function(url,direction) {	
	    	    	
	   if (this.processing == true) {
	       return false;
	   }	
	    
	   var appendUrlToUser = this.appendURL;
	   var ajaxImagesURL = this.ajaximages;
	   var urlmain = this.formAddPath;
	   
	   $('#ajax-navigator-content').addClass("ajax-loading-items");	
	   $('#images-ajax-container').hide();
	   $('.right-ajax').hide();
	   $('.left-ajax').hide();
	   this.processing = true;
	   
	   
       $.getJSON(url + "/(direction)/"+direction, {} , function(data) {	
            
            $('#ajax-navigator-content').removeClass('ajax-loading-items'); 
            $('#images-ajax-container').show();
    	    $('.right-ajax').show();
    	    $('.left-ajax').show();
	        hw.processing = false;
	        
            if (data.error != 'true'){	
                 
                 if (data.has_more_images == 'true') {                     
                     $('.left-ajax a').attr('rel',urlmain + ajaxImagesURL + data.left_img_pid + appendUrlToUser);
                     $('.right-ajax a').attr('rel',urlmain + ajaxImagesURL + data.right_img_pid + appendUrlToUser);                     	
			         $('#images-ajax-container').html(data.result);	
			         $('.right-ajax').show();
			         $('.left-ajax').show();
                 } else {                                        
                    
                     if (direction == 'left') { 
                         $('.left-ajax').hide(); 
                         $('.right-ajax').show();
                     } else {
                         $('.right-ajax').hide();
                         $('.left-ajax').show(); 
                     }
                     
                     var dif = data.images_found;
                     
                     if (data.images_found == 5) {  
                            $('#images-ajax-container').html(data.result);	
                            
                            if (direction == 'right') {
                                $('.left-ajax a').attr('rel',urlmain + ajaxImagesURL + data.left_img_pid + appendUrlToUser);
                            } else {
                                $('.right-ajax a').attr('rel',urlmain + ajaxImagesURL + data.right_img_pid + appendUrlToUser);
                            }
                            
                     } else if (direction == 'left') { 
                           
                           jQuery.each($('#images-ajax-container div.image-thumb').reverse(), function(i, val) {                               
                               if (dif > 0  ){ 
                                   $(this).remove();
                                   dif--;
                               }                               
                           });                            
                           $('#images-ajax-container').prepend(data.result);
                           $('.left-ajax a').attr('rel',urlmain + ajaxImagesURL + data.left_img_pid + appendUrlToUser);
                           $('.right-ajax a').attr('rel',urlmain + ajaxImagesURL + $('#images-ajax-container div.image-thumb:last a').attr('rel') + appendUrlToUser);
                           
                     } else if (direction == 'right') {       
                            
                            jQuery.each($('#images-ajax-container div.image-thumb'), function(i, val) {
                                  if (dif > 0  ){ 
                                       $(this).remove();
                                       dif--;
                                   }
                            });                           
                                          
                           $('#images-ajax-container').append(data.result);
                           $('.right-ajax a').attr('rel',urlmain + ajaxImagesURL + data.right_img_pid + appendUrlToUser);
                           $('.left-ajax a').attr('rel',urlmain + ajaxImagesURL + $('#images-ajax-container div.image-thumb a').attr('rel') + appendUrlToUser);                                                     
                           
                     } 
                 }	            
            }
		});			
		return false;	
	},
	
	addToFavorites : function(pid)
	{
		$.getJSON(this.formAddPath + this.addtofavorites+pid, {} , function(data){	
			
			$('.ad-fv').addClass('ad-fv-ok');
            	
		});
	},
		
	initInfoWindow : function(sort) {	
	   $('.inf-img').mouseover(function() {		       
	       var inst = $(this);
	       hw.delayTime = setTimeout(function(){ 
                hw.fetchImageInfoWindow(inst,sort);
	       },300);
      }).mouseleave(function(){
          clearTimeout(hw.delayTime);
      });      
	},	
	
	initInfoWindowAlbum : function() {	
	   $('.inf-alb').mouseover(function() {		       
	       var inst = $(this);
	       hw.delayTimeAlbum = setTimeout(function(){ 
                hw.fetchImageInfoWindowAlbum(inst);
	       },300);
      }).mouseleave(function(){
          clearTimeout(hw.delayTimeAlbum);
      });      
	},
	
	fetchImageInfoWindowAlbum : function(img){
	    if (hw.fetchingInfo == img.attr('rel')) return;	    
	    hw.fetchingInfo = img.attr('rel');	    
	    clearTimeout(hw.myTimerAlbum);	    
		$.getJSON(this.formAddPath + 'gallery/showalbuminfo/'+img.attr('rel'), {} , function(data) {	
		    $('#imageInfoWindowAlbum').remove()
		    img.before(data.result);
		    $('#imageInfoWindowAlbum').fadeIn('fast');
		    $('#imageInfoWindowAlbum').mouseleave(function() {		    
		        hw.myTimerAlbum = setTimeout(function(){
                    $('#imageInfoWindowAlbum').fadeOut();
                    hw.fetchingInfo = false;
                },100);
		    });		    
		    $('#imageInfoWindowAlbum').mouseenter(function(){clearTimeout(hw.myTimerAlbum);});		
		});
	},
	
	fetchImageInfoWindow : function(img,sort){
	    if (hw.fetchingInfo == img.attr('rel')) return;	    
	    hw.fetchingInfo = img.attr('rel');	    
	    clearTimeout(hw.myTimer);	    
		$.getJSON(this.formAddPath + 'gallery/showimageinfo/'+img.attr('rel')+'/'+sort, {} , function(data) {	
		    $('#imageInfoWindow').remove()
		    img.before(data.result);
		    $('#imageInfoWindow').fadeIn('fast');
		    $('#imageInfoWindow').mouseleave(function() {		    
		        hw.myTimer = setTimeout(function(){
                    $('#imageInfoWindow').fadeOut();
                    hw.fetchingInfo = false;
                },250);
		    });		    
		    $('#imageInfoWindow').mouseenter(function(){clearTimeout(hw.myTimer);});		
		});
	},
	
	deleteFavorite : function(pid)
	{
		$.getJSON(this.formAddPath + this.deletefavorite+pid, {} , function(data) {				
			$('#image_thumb_'+pid).fadeOut();            	
		});
		
		return false;
	},
	
	reportMessage : function(inst) {
	   $('.youtube-frame').css('visibility','hidden'); 
	   $.colorbox({href:this.formAddPath + 'forum/report/'+inst.attr('rel'),onClosed : function(){ $('.youtube-frame').css('visibility','visible'); }});	   
	},
	
	quoteMessage : function(msg_id)
	{
		$.postJSON(this.formAddPath + 'forum/quote/'+msg_id, {} , function(data) {		    
		    $('#IDAlbumDescription').focus();
			$('#IDAlbumDescription').val($('#IDAlbumDescription').val()+data.result);
			document.location = '#new-message-block';			
		});
				
		return false;
	},
	
	explandAlbumInfo : function(item)
	{	    
	    var nextElement = item.next();
	    var tempName = item.attr('rel');
	    var currentName = item.html();
	    item.html(tempName);
		item.attr('rel',currentName);
		  
	    if (!nextElement.hasClass('expanded-alb-desc')) {
		  nextElement.addClass('expanded-alb-desc');		  
	    } else {
	      nextElement.removeClass('expanded-alb-desc');
	    };
	
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

	showTransteToBox : function(inst,msg_id) {
	    $.getJSON(this.formAddPath + 'gallery/translatebox/'+msg_id, {} , function(data) {				
			 inst.prepend(data.result);
		});
	},
	
	translateComment : function(msg_id,lang) {
	    $.getJSON(this.formAddPath + 'gallery/translatecomment/'+msg_id + '/' + lang, {} , function(data) {				
			 $('#msg_bd_'+msg_id).html(data.result);
		});
	},
	
	initCommentTranslations : function() {
	    $('.lang-box').mouseenter(function(){ 
	        $('.lng-cmt').hide(); 
	        $('.tr-lnk').show();
	        
	        clearTimeout(hw.delayTime);
            if ($(this).find('.lng-cmt').size() == 0)
            {
                hw.showTransteToBox($(this),$(this).find('a.tr-lnk').attr('rel'));  
                $(this).find('.tr-lnk').hide(); 
            } else {
                $(this).find('.lng-cmt').show();
                $(this).find('.tr-lnk').hide();
            }        
        }).mouseleave(function() {   
             hw.delayTime = setTimeout(function(){ 
                    $('.lng-cmt').hide();
                    $('.tr-lnk').show();
    	     },1000);
        });
	},
	
	rotateImage : function (image_id,action)
	{		
	    $('#pid_thumb_'+image_id).addClass('loading-item');
	    $('#pid_thumb_'+image_id+' > a > img').hide();
	    
		$.getJSON(this.formAddPath + 'gallery/rotate/'+image_id+'/' + action,  function(data) {
		    $('#pid_thumb_'+image_id+' > a > img').show();		
		    $('#pid_thumb_'+image_id+' > a > img').attr('src',$('#pid_thumb_'+image_id+' > a > img').attr('src')+'?time='+data.time);	
		    $('#pid_thumb_'+image_id).removeClass('loading-item');	 
            return true;	          
		});	
		return false;	
	},
	
	setNewImageFull : function (image_id)
	{	
	    $.colorbox({href:this.formAddPath + 'gallery/switchimage/'+image_id+'/(type)/full',iframe:true,width:400,height:300});
	},
	
	moveImageToAnotherAlbum : function (image_id)
	{	
	    $.colorbox({href:this.formAddPath + 'gallery/moveimage/'+image_id,iframe:true,width:400,height:300});
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
	
	setNewImage : function (image_id)
	{	
	    $.colorbox({href:this.formAddPath + 'gallery/switchimage/'+image_id,iframe:true,width:400,height:300});
	},
	
	setFolderImage : function (image_id)
	{			   	    
		$.getJSON(this.formAddPath + 'gallery/setfolderthumb/'+image_id,  function(data) {		    	 
		    alert(data.result);
            return true;	          
		});	
		return false;	
	},
	
	initPalleteFilter : function(current_colors,mode,keyword)
	{	 
	   var baseURL = this.formAddPath;
	   $("#color-filter-nav .current-sort").mouseenter(function() {	
	        if (!$('#pall-comb').is('*')) {
                $.getJSON(baseURL + 'gallery/getpallete'+current_colors + '/(mode)/'+mode+ '/(keyword)/' + keyword, {} , function(data) {				
        			$('#pallete-content').html(data.result);         	
        		});      
	        }          
       })	   
	},
	
	initNPalleteFilter : function(current_colors,mode,keyword)
	{	 
	   var baseURL = this.formAddPath;
	   $("#ncolor-filter-nav .current-sort").mouseenter(function() {	
	        if (!$('#npall-comb').is('*')) {
                $.getJSON(baseURL + 'gallery/getnpallete'+current_colors + '/(mode)/'+mode+ '/(keyword)/' + keyword, {} , function(data) {				
        			$('#npallete-content').html(data.result);
        		});      
	        }          
       })	   
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
	
	initColorChoose : function()
	{	 
	    var baseURL = this.formAddPath;
        $('#KeywordColorSearch').change(function(){ 
          var keyword = $(this).val();
          if ($(this).val() == '') {
                $('#pallete-include a').each(function(index) {
                    	$(this).attr('href',baseURL+"gallery/color/(color)/"+$(this).attr('rel'));
                });
                $('#pallete-exclude a').each(function(index) {
                    	$(this).attr('href',baseURL+"gallery/color/(ncolor)/"+$(this).attr('rel'));
                });
          } else { 
                $('#pallete-include a').each(function(index) {
                    	$(this).attr('href',baseURL+"gallery/search/(keyword)/"+escape(keyword)+"/(color)/"+$(this).attr('rel'));
                }); 
                $('#pallete-exclude a').each(function(index) {
                    	$(this).attr('href',baseURL+"gallery/search/(keyword)/"+escape(keyword)+"/(ncolor)/"+$(this).attr('rel'));
                });
          }
      }); 
	},
	
	initAdFav : function(pid)
	{
	    $('.ad-fv').click(function(){
            hw.addToFavorites(pid);
           return false;
        }); 
	},
	
	searchSynonymous : function(wordlanguage,wordOriginalid,word)
	{
	    $('#synonymous-container').html('Working...');
	    $.postJSON(this.formAddPath + 'dictionary/synonymous/'+wordlanguage+'/'+wordOriginalid, {'word' : word} , function(data){
			   $('#synonymous-container').html(data.result);
		});
	},
	
	addSynonymous : function(wordlanguage,wordOriginalid)
	{	    
	    var wordid = $('input[name="WordSynonymous"]').val();
	    $('#synonymous-container').html('Working...');
	    $.getJSON(this.formAddPath + 'dictionary/addsynonymous/'+wordlanguage+'/'+wordOriginalid+'/'+wordid , function(data){
            
	        $('#synonymous-container').html(data.resultad);
	        if ( data.error == 'false' ) {
	           $('#word-synonymous').html(data.result);
	        };
			   
		});		
	},
	
	initSimilarGet : function(pid)
	{
	    $.getJSON(this.formAddPath + 'similar/imagejson/'+pid , function(data){
			$('#similar-images-container').removeClass('ajax-loading-items');
            $('#similar-images-container .img-list').html(data.result);         
		});
	},
	
	initFBAlbumImport : function()
	{ 
	       var baseURL = this.formAddPath;
	       
	       $('#checkAllButton').click(function() { 
               $( '.itemPhoto' ).each( function() {         
            		$( this ).attr( 'checked', $( this ).is( ':checked' ) ? '' : 'checked' );
            	})    
            });
            
            $('.newAlbumName').change(function(){	    
            	$.getJSON(baseURL+"gallery/albumnamesuggest/0/"+escape($(this).val()), {} , function(data){	
                               $('#album_select_directory0').html(data.result);                       
                               if (data.error == 'false'){
                                    $('#album_select_directory0 input').eq(0).attr("checked","checked");
                                    $('#moveAction').show();
                               } else {
                                   $('#moveAction').hide();
                               }                       
                	});	
            });
            
            $('#moveAction').click(function() { 
                if ($('.image_import:checked').size() > 0) {
                importPhoto();
                } else {
                    alert('Please choose atleast one image!');
                }
            });
            
            function importPhoto()
            {
               if ($('input[name=AlbumDestinationDirectory0]:checked').val() != undefined) { 
                   if ($('.image_import:checked').size() > 0) {
                       $('#total_to_import').html($('.image_import:checked').size() + ' images to import...');
                       $('#total_to_import').addClass('spinning-now');
                       
                       $.getJSON(baseURL+"fb/importfbphoto/"+$('input[name=AlbumDestinationDirectory0]:checked').val()+"/"+$('.image_import:checked').eq(0).val(), {} , function(data){
                              $('.image_import:checked').eq(0).removeClass('image_import');	
                    		  importPhoto();
                    	});
                   } else { 
                        $('#total_to_import').removeClass('spinning-now');
                        $('#total_to_import').html('All images were imported...');
                   }                	
               } else {
                   alert('Please choose album');
               }
            }
	},
	
	initPublicUpload : function(extensions, photo_size, max_upload_limit, template_uploader, file_template, action_url)
	{
	    var uploader = new qq.FileUploader({
            element: document.getElementById('ad-image-upload'),
            listElement: document.getElementById('listElement-ad-image'),
            action: action_url,
            allowedExtensions:extensions,
            autoStart : false,
            sizeLimit : photo_size,
            maxFiles : max_upload_limit,
            paramsCallback : function(file_id) {
                return {
        				title	    : $('#PhotoTitle'+file_id).val(),
        				keywords    : $('#PhotoKeyword'+file_id).val(),				
        				description	: $('#PhotoDescription'+file_id).val(),				
        				anaglyph    : $('#PhotoAnaglyph'+file_id).attr('checked'),
        				album_id    : $('#AlbumIDToUpload').val()
        		}
            },
            onComplete: function(id, fileName, responseJSON) {
                if (responseJSON.success == 'true') {   
                    var strintID = String(id);       
                    $('#file_id_row_'+strintID.replace('qq-upload-handler-iframe','')).fadeOut();
                }
            },
            onStart : function(){ 
                $('.qq-upload-spinner').addClass('active-spinner');
                return true;
            },
        	template: template_uploader,
            fileTemplate: file_template,
            multiple: true
        });
        
        $('#ConvertButton').click(function(){ 
            uploader.startUpload();
        });
	},
	
	initSimilarImage : function(extensions, photo_size, max_upload_limit, template_uploader, file_template, action_url)
	{
	    var uploader = new qq.FileUploader({
            element: document.getElementById('ad-image-upload'),
            listElement: document.getElementById('listElement-ad-image'),
            action: action_url,
            allowedExtensions: extensions,
            autoStart : true,
            sizeLimit : photo_size,
            maxFiles : max_upload_limit,   
            onComplete: function(id, fileName, responseJSON) {
                if (responseJSON.success == 'true') {   
                    var strintID = String(id);       
                    $('#file_id_row_'+strintID.replace('qq-upload-handler-iframe','')).fadeOut();
                    $('#similar-images-container').removeClass('ajax-loading-items');
                    $('#similar-images-container .img-list').html(responseJSON.result);
                }
            },
            onStart : function(){ 
                $('.qq-upload-spinner').addClass('active-spinner');
                $('#similar-images-container').addClass('ajax-loading-items');
                $('#similar-images-container .img-list').html('');
                return true;
            },
            onSubmit : function(){ 
                $('#similar-images-container').addClass('ajax-loading-items');
                $('#similar-images-container .img-list').html('');
                return true;
            },
        	template: template_uploader,
            fileTemplate: file_template,
            multiple: false,
            debug: true
        });
	},
	
	initUploadZip : function( archive_size, max_upload_limit, template_uploader, file_template, action_url)
	{
	    var uploader = new qq.FileUploader({
            element: document.getElementById('ad-image-upload'),
            listElement: document.getElementById('listElement-ad-image'),
            action: action_url,
            allowedExtensions:['zip'],
            autoStart : false,
            sizeLimit : archive_size,
            maxFiles : max_upload_limit,   
            onComplete: function(id, fileName, responseJSON) {
                if (responseJSON.success == 'true'){
                    var strintID = String(id);       
                    $('#file_id_row_'+strintID.replace('qq-upload-handler-iframe','')).fadeOut();
                }
            },
            onStart : function() {
                var status = true;
                $('#listElement-ad-image li').each(function(){
                    if ($(this).find('#AlbumIDToUploadArchive'+$(this).attr('idattr')).val() == '' && $(this).find('#PhotoTitle'+$(this).attr('idattr')).val() == '' ) {
                        alert('Please choose album or enter album new name');
                        $(this).find('#PhotoTitle'+$(this).attr('idattr')).focus();
                        status = false;
                    }
                });
                if (status == true) {
                    $('.qq-upload-spinner').addClass('active-spinner');
                }
                return status;
            },
            paramsCallback : function(file_id) {
                return {
        				title	    : $('#PhotoTitle'+file_id).val(),
        				keyword     : $('#PhotoKeyword'+file_id).val(),				
        				description	: $('#PhotoDescription'+file_id).val(),				
        				album_id    : $('#AlbumIDToUploadArchive'+file_id).val()
        		}
            },
        	template: template_uploader,
            fileTemplate: file_template,
            multiple: true
        });
        
        $('#ConvertButton').click(function(){ 
            uploader.startUpload();
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

/*!
 * jQuery UI 1.8.6
 *
 * Copyright 2010, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI
 */
(function(c,j){function k(a){return!c(a).parents().andSelf().filter(function(){return c.curCSS(this,"visibility")==="hidden"||c.expr.filters.hidden(this)}).length}c.ui=c.ui||{};if(!c.ui.version){c.extend(c.ui,{version:"1.8.6",keyCode:{ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,
NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91}});c.fn.extend({_focus:c.fn.focus,focus:function(a,b){return typeof a==="number"?this.each(function(){var d=this;setTimeout(function(){c(d).focus();b&&b.call(d)},a)}):this._focus.apply(this,arguments)},scrollParent:function(){var a;a=c.browser.msie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?this.parents().filter(function(){return/(relative|absolute|fixed)/.test(c.curCSS(this,
"position",1))&&/(auto|scroll)/.test(c.curCSS(this,"overflow",1)+c.curCSS(this,"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0):this.parents().filter(function(){return/(auto|scroll)/.test(c.curCSS(this,"overflow",1)+c.curCSS(this,"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0);return/fixed/.test(this.css("position"))||!a.length?c(document):a},zIndex:function(a){if(a!==j)return this.css("zIndex",a);if(this.length){a=c(this[0]);for(var b;a.length&&a[0]!==document;){b=a.css("position");
if(b==="absolute"||b==="relative"||b==="fixed"){b=parseInt(a.css("zIndex"),10);if(!isNaN(b)&&b!==0)return b}a=a.parent()}}return 0},disableSelection:function(){return this.bind((c.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(a){a.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}});c.each(["Width","Height"],function(a,b){function d(f,g,l,m){c.each(e,function(){g-=parseFloat(c.curCSS(f,"padding"+this,true))||0;if(l)g-=parseFloat(c.curCSS(f,
"border"+this+"Width",true))||0;if(m)g-=parseFloat(c.curCSS(f,"margin"+this,true))||0});return g}var e=b==="Width"?["Left","Right"]:["Top","Bottom"],h=b.toLowerCase(),i={innerWidth:c.fn.innerWidth,innerHeight:c.fn.innerHeight,outerWidth:c.fn.outerWidth,outerHeight:c.fn.outerHeight};c.fn["inner"+b]=function(f){if(f===j)return i["inner"+b].call(this);return this.each(function(){c(this).css(h,d(this,f)+"px")})};c.fn["outer"+b]=function(f,g){if(typeof f!=="number")return i["outer"+b].call(this,f);return this.each(function(){c(this).css(h,
d(this,f,true,g)+"px")})}});c.extend(c.expr[":"],{data:function(a,b,d){return!!c.data(a,d[3])},focusable:function(a){var b=a.nodeName.toLowerCase(),d=c.attr(a,"tabindex");if("area"===b){b=a.parentNode;d=b.name;if(!a.href||!d||b.nodeName.toLowerCase()!=="map")return false;a=c("img[usemap=#"+d+"]")[0];return!!a&&k(a)}return(/input|select|textarea|button|object/.test(b)?!a.disabled:"a"==b?a.href||!isNaN(d):!isNaN(d))&&k(a)},tabbable:function(a){var b=c.attr(a,"tabindex");return(isNaN(b)||b>=0)&&c(a).is(":focusable")}});
c(function(){var a=document.body,b=a.appendChild(b=document.createElement("div"));c.extend(b.style,{minHeight:"100px",height:"auto",padding:0,borderWidth:0});c.support.minHeight=b.offsetHeight===100;c.support.selectstart="onselectstart"in b;a.removeChild(b).style.display="none"});c.extend(c.ui,{plugin:{add:function(a,b,d){a=c.ui[a].prototype;for(var e in d){a.plugins[e]=a.plugins[e]||[];a.plugins[e].push([b,d[e]])}},call:function(a,b,d){if((b=a.plugins[b])&&a.element[0].parentNode)for(var e=0;e<b.length;e++)a.options[b[e][0]]&&
b[e][1].apply(a.element,d)}},contains:function(a,b){return document.compareDocumentPosition?a.compareDocumentPosition(b)&16:a!==b&&a.contains(b)},hasScroll:function(a,b){if(c(a).css("overflow")==="hidden")return false;b=b&&b==="left"?"scrollLeft":"scrollTop";var d=false;if(a[b]>0)return true;a[b]=1;d=a[b]>0;a[b]=0;return d},isOverAxis:function(a,b,d){return a>b&&a<b+d},isOver:function(a,b,d,e,h,i){return c.ui.isOverAxis(a,d,h)&&c.ui.isOverAxis(b,e,i)}})}})(jQuery);
;/*!
 * jQuery UI Widget 1.8.6
 *
 * Copyright 2010, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Widget
 */
(function(b,j){if(b.cleanData){var k=b.cleanData;b.cleanData=function(a){for(var c=0,d;(d=a[c])!=null;c++)b(d).triggerHandler("remove");k(a)}}else{var l=b.fn.remove;b.fn.remove=function(a,c){return this.each(function(){if(!c)if(!a||b.filter(a,[this]).length)b("*",this).add([this]).each(function(){b(this).triggerHandler("remove")});return l.call(b(this),a,c)})}}b.widget=function(a,c,d){var e=a.split(".")[0],f;a=a.split(".")[1];f=e+"-"+a;if(!d){d=c;c=b.Widget}b.expr[":"][f]=function(h){return!!b.data(h,
a)};b[e]=b[e]||{};b[e][a]=function(h,g){arguments.length&&this._createWidget(h,g)};c=new c;c.options=b.extend(true,{},c.options);b[e][a].prototype=b.extend(true,c,{namespace:e,widgetName:a,widgetEventPrefix:b[e][a].prototype.widgetEventPrefix||a,widgetBaseClass:f},d);b.widget.bridge(a,b[e][a])};b.widget.bridge=function(a,c){b.fn[a]=function(d){var e=typeof d==="string",f=Array.prototype.slice.call(arguments,1),h=this;d=!e&&f.length?b.extend.apply(null,[true,d].concat(f)):d;if(e&&d.charAt(0)==="_")return h;
e?this.each(function(){var g=b.data(this,a),i=g&&b.isFunction(g[d])?g[d].apply(g,f):g;if(i!==g&&i!==j){h=i;return false}}):this.each(function(){var g=b.data(this,a);g?g.option(d||{})._init():b.data(this,a,new c(d,this))});return h}};b.Widget=function(a,c){arguments.length&&this._createWidget(a,c)};b.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",options:{disabled:false},_createWidget:function(a,c){b.data(c,this.widgetName,this);this.element=b(c);this.options=b.extend(true,{},this.options,
this._getCreateOptions(),a);var d=this;this.element.bind("remove."+this.widgetName,function(){d.destroy()});this._create();this._trigger("create");this._init()},_getCreateOptions:function(){return b.metadata&&b.metadata.get(this.element[0])[this.widgetName]},_create:function(){},_init:function(){},destroy:function(){this.element.unbind("."+this.widgetName).removeData(this.widgetName);this.widget().unbind("."+this.widgetName).removeAttr("aria-disabled").removeClass(this.widgetBaseClass+"-disabled ui-state-disabled")},
widget:function(){return this.element},option:function(a,c){var d=a;if(arguments.length===0)return b.extend({},this.options);if(typeof a==="string"){if(c===j)return this.options[a];d={};d[a]=c}this._setOptions(d);return this},_setOptions:function(a){var c=this;b.each(a,function(d,e){c._setOption(d,e)});return this},_setOption:function(a,c){this.options[a]=c;if(a==="disabled")this.widget()[c?"addClass":"removeClass"](this.widgetBaseClass+"-disabled ui-state-disabled").attr("aria-disabled",c);return this},
enable:function(){return this._setOption("disabled",false)},disable:function(){return this._setOption("disabled",true)},_trigger:function(a,c,d){var e=this.options[a];c=b.Event(c);c.type=(a===this.widgetEventPrefix?a:this.widgetEventPrefix+a).toLowerCase();d=d||{};if(c.originalEvent){a=b.event.props.length;for(var f;a;){f=b.event.props[--a];c[f]=c.originalEvent[f]}}this.element.trigger(c,d);return!(b.isFunction(e)&&e.call(this.element[0],c,d)===false||c.isDefaultPrevented())}}})(jQuery);
;/*
 * jQuery UI Position 1.8.6
 *
 * Copyright 2010, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Position
 */
(function(c){c.ui=c.ui||{};var n=/left|center|right/,o=/top|center|bottom/,t=c.fn.position,u=c.fn.offset;c.fn.position=function(b){if(!b||!b.of)return t.apply(this,arguments);b=c.extend({},b);var a=c(b.of),d=a[0],g=(b.collision||"flip").split(" "),e=b.offset?b.offset.split(" "):[0,0],h,k,j;if(d.nodeType===9){h=a.width();k=a.height();j={top:0,left:0}}else if(d.setTimeout){h=a.width();k=a.height();j={top:a.scrollTop(),left:a.scrollLeft()}}else if(d.preventDefault){b.at="left top";h=k=0;j={top:b.of.pageY,
left:b.of.pageX}}else{h=a.outerWidth();k=a.outerHeight();j=a.offset()}c.each(["my","at"],function(){var f=(b[this]||"").split(" ");if(f.length===1)f=n.test(f[0])?f.concat(["center"]):o.test(f[0])?["center"].concat(f):["center","center"];f[0]=n.test(f[0])?f[0]:"center";f[1]=o.test(f[1])?f[1]:"center";b[this]=f});if(g.length===1)g[1]=g[0];e[0]=parseInt(e[0],10)||0;if(e.length===1)e[1]=e[0];e[1]=parseInt(e[1],10)||0;if(b.at[0]==="right")j.left+=h;else if(b.at[0]==="center")j.left+=h/2;if(b.at[1]==="bottom")j.top+=
k;else if(b.at[1]==="center")j.top+=k/2;j.left+=e[0];j.top+=e[1];return this.each(function(){var f=c(this),l=f.outerWidth(),m=f.outerHeight(),p=parseInt(c.curCSS(this,"marginLeft",true))||0,q=parseInt(c.curCSS(this,"marginTop",true))||0,v=l+p+parseInt(c.curCSS(this,"marginRight",true))||0,w=m+q+parseInt(c.curCSS(this,"marginBottom",true))||0,i=c.extend({},j),r;if(b.my[0]==="right")i.left-=l;else if(b.my[0]==="center")i.left-=l/2;if(b.my[1]==="bottom")i.top-=m;else if(b.my[1]==="center")i.top-=m/2;
i.left=parseInt(i.left);i.top=parseInt(i.top);r={left:i.left-p,top:i.top-q};c.each(["left","top"],function(s,x){c.ui.position[g[s]]&&c.ui.position[g[s]][x](i,{targetWidth:h,targetHeight:k,elemWidth:l,elemHeight:m,collisionPosition:r,collisionWidth:v,collisionHeight:w,offset:e,my:b.my,at:b.at})});c.fn.bgiframe&&f.bgiframe();f.offset(c.extend(i,{using:b.using}))})};c.ui.position={fit:{left:function(b,a){var d=c(window);d=a.collisionPosition.left+a.collisionWidth-d.width()-d.scrollLeft();b.left=d>0?
b.left-d:Math.max(b.left-a.collisionPosition.left,b.left)},top:function(b,a){var d=c(window);d=a.collisionPosition.top+a.collisionHeight-d.height()-d.scrollTop();b.top=d>0?b.top-d:Math.max(b.top-a.collisionPosition.top,b.top)}},flip:{left:function(b,a){if(a.at[0]!=="center"){var d=c(window);d=a.collisionPosition.left+a.collisionWidth-d.width()-d.scrollLeft();var g=a.my[0]==="left"?-a.elemWidth:a.my[0]==="right"?a.elemWidth:0,e=a.at[0]==="left"?a.targetWidth:-a.targetWidth,h=-2*a.offset[0];b.left+=
a.collisionPosition.left<0?g+e+h:d>0?g+e+h:0}},top:function(b,a){if(a.at[1]!=="center"){var d=c(window);d=a.collisionPosition.top+a.collisionHeight-d.height()-d.scrollTop();var g=a.my[1]==="top"?-a.elemHeight:a.my[1]==="bottom"?a.elemHeight:0,e=a.at[1]==="top"?a.targetHeight:-a.targetHeight,h=-2*a.offset[1];b.top+=a.collisionPosition.top<0?g+e+h:d>0?g+e+h:0}}}};if(!c.offset.setOffset){c.offset.setOffset=function(b,a){if(/static/.test(c.curCSS(b,"position")))b.style.position="relative";var d=c(b),
g=d.offset(),e=parseInt(c.curCSS(b,"top",true),10)||0,h=parseInt(c.curCSS(b,"left",true),10)||0;g={top:a.top-g.top+e,left:a.left-g.left+h};"using"in a?a.using.call(b,g):d.css(g)};c.fn.offset=function(b){var a=this[0];if(!a||!a.ownerDocument)return null;if(b)return this.each(function(){c.offset.setOffset(this,b)});return u.call(this)}}})(jQuery);
;/*
 * jQuery UI Autocomplete 1.8.6
 *
 * Copyright 2010, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Autocomplete
 *
 * Depends:
 *	jquery.ui.core.js
 *	jquery.ui.widget.js
 *	jquery.ui.position.js
 */
(function(e){e.widget("ui.autocomplete",{options:{appendTo:"body",delay:300,minLength:1,position:{my:"left top",at:"left bottom",collision:"none"},source:null},_create:function(){var a=this,b=this.element[0].ownerDocument,f;this.element.addClass("ui-autocomplete-input").attr("autocomplete","off").attr({role:"textbox","aria-autocomplete":"list","aria-haspopup":"true"}).bind("keydown.autocomplete",function(c){if(!(a.options.disabled||a.element.attr("readonly"))){f=false;var d=e.ui.keyCode;switch(c.keyCode){case d.PAGE_UP:a._move("previousPage",
c);break;case d.PAGE_DOWN:a._move("nextPage",c);break;case d.UP:a._move("previous",c);c.preventDefault();break;case d.DOWN:a._move("next",c);c.preventDefault();break;case d.ENTER:case d.NUMPAD_ENTER:if(a.menu.active){f=true;c.preventDefault()}case d.TAB:if(!a.menu.active)return;a.menu.select(c);break;case d.ESCAPE:a.element.val(a.term);a.close(c);break;default:clearTimeout(a.searching);a.searching=setTimeout(function(){if(a.term!=a.element.val()){a.selectedItem=null;a.search(null,c)}},a.options.delay);
break}}}).bind("keypress.autocomplete",function(c){if(f){f=false;c.preventDefault()}}).bind("focus.autocomplete",function(){if(!a.options.disabled){a.selectedItem=null;a.previous=a.element.val()}}).bind("blur.autocomplete",function(c){if(!a.options.disabled){clearTimeout(a.searching);a.closing=setTimeout(function(){a.close(c);a._change(c)},150)}});this._initSource();this.response=function(){return a._response.apply(a,arguments)};this.menu=e("<ul></ul>").addClass("ui-autocomplete").appendTo(e(this.options.appendTo||
"body",b)[0]).mousedown(function(c){var d=a.menu.element[0];e(c.target).closest(".ui-menu-item").length||setTimeout(function(){e(document).one("mousedown",function(g){g.target!==a.element[0]&&g.target!==d&&!e.ui.contains(d,g.target)&&a.close()})},1);setTimeout(function(){clearTimeout(a.closing)},13)}).menu({focus:function(c,d){d=d.item.data("item.autocomplete");false!==a._trigger("focus",c,{item:d})&&/^key/.test(c.originalEvent.type)&&a.element.val(d.value)},selected:function(c,d){d=d.item.data("item.autocomplete");
var g=a.previous;if(a.element[0]!==b.activeElement){a.element.focus();a.previous=g;setTimeout(function(){a.previous=g},1)}false!==a._trigger("select",c,{item:d})&&a.element.val(d.value);a.term=a.element.val();a.close(c);a.selectedItem=d},blur:function(){a.menu.element.is(":visible")&&a.element.val()!==a.term&&a.element.val(a.term)}}).zIndex(this.element.zIndex()+1).css({top:0,left:0}).hide().data("menu");e.fn.bgiframe&&this.menu.element.bgiframe()},destroy:function(){this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete").removeAttr("role").removeAttr("aria-autocomplete").removeAttr("aria-haspopup");
this.menu.element.remove();e.Widget.prototype.destroy.call(this)},_setOption:function(a,b){e.Widget.prototype._setOption.apply(this,arguments);a==="source"&&this._initSource();if(a==="appendTo")this.menu.element.appendTo(e(b||"body",this.element[0].ownerDocument)[0])},_initSource:function(){var a=this,b,f;if(e.isArray(this.options.source)){b=this.options.source;this.source=function(c,d){d(e.ui.autocomplete.filter(b,c.term))}}else if(typeof this.options.source==="string"){f=this.options.source;this.source=
function(c,d){a.xhr&&a.xhr.abort();a.xhr=e.getJSON(f,c,function(g,i,h){h===a.xhr&&d(g);a.xhr=null})}}else this.source=this.options.source},search:function(a,b){a=a!=null?a:this.element.val();this.term=this.element.val();if(a.length<this.options.minLength)return this.close(b);clearTimeout(this.closing);if(this._trigger("search",b)!==false)return this._search(a)},_search:function(a){this.element.addClass("ui-autocomplete-loading");this.source({term:a},this.response)},_response:function(a){if(a&&a.length){a=
this._normalize(a);this._suggest(a);this._trigger("open")}else this.close();this.element.removeClass("ui-autocomplete-loading")},close:function(a){clearTimeout(this.closing);if(this.menu.element.is(":visible")){this._trigger("close",a);this.menu.element.hide();this.menu.deactivate()}},_change:function(a){this.previous!==this.element.val()&&this._trigger("change",a,{item:this.selectedItem})},_normalize:function(a){if(a.length&&a[0].label&&a[0].value)return a;return e.map(a,function(b){if(typeof b===
"string")return{label:b,value:b};return e.extend({label:b.label||b.value,value:b.value||b.label},b)})},_suggest:function(a){this._renderMenu(this.menu.element.empty().zIndex(this.element.zIndex()+1),a);this.menu.deactivate();this.menu.refresh();this.menu.element.show().position(e.extend({of:this.element},this.options.position));this._resizeMenu()},_resizeMenu:function(){var a=this.menu.element;a.outerWidth(Math.max(a.width("").outerWidth(),this.element.outerWidth()))},_renderMenu:function(a,b){var f=
this;e.each(b,function(c,d){f._renderItem(a,d)})},_renderItem:function(a,b){return e("<li></li>").data("item.autocomplete",b).append(e("<a></a>").text(b.label)).appendTo(a)},_move:function(a,b){if(this.menu.element.is(":visible"))if(this.menu.first()&&/^previous/.test(a)||this.menu.last()&&/^next/.test(a)){this.element.val(this.term);this.menu.deactivate()}else this.menu[a](b);else this.search(null,b)},widget:function(){return this.menu.element}});e.extend(e.ui.autocomplete,{escapeRegex:function(a){return a.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,
"\\$&")},filter:function(a,b){var f=new RegExp(e.ui.autocomplete.escapeRegex(b),"i");return e.grep(a,function(c){return f.test(c.label||c.value||c)})}})})(jQuery);
(function(e){e.widget("ui.menu",{_create:function(){var a=this;this.element.addClass("ui-menu ui-widget ui-widget-content ui-corner-all").attr({role:"listbox","aria-activedescendant":"ui-active-menuitem"}).click(function(b){if(e(b.target).closest(".ui-menu-item a").length){b.preventDefault();a.select(b)}});this.refresh()},refresh:function(){var a=this;this.element.children("li:not(.ui-menu-item):has(a)").addClass("ui-menu-item").attr("role","menuitem").children("a").addClass("ui-corner-all").attr("tabindex",
-1).mouseenter(function(b){a.activate(b,e(this).parent())}).mouseleave(function(){a.deactivate()})},activate:function(a,b){this.deactivate();if(this.hasScroll()){var f=b.offset().top-this.element.offset().top,c=this.element.attr("scrollTop"),d=this.element.height();if(f<0)this.element.attr("scrollTop",c+f);else f>=d&&this.element.attr("scrollTop",c+f-d+b.height())}this.active=b.eq(0).children("a").addClass("ui-state-hover").attr("id","ui-active-menuitem").end();this._trigger("focus",a,{item:b})},
deactivate:function(){if(this.active){this.active.children("a").removeClass("ui-state-hover").removeAttr("id");this._trigger("blur");this.active=null}},next:function(a){this.move("next",".ui-menu-item:first",a)},previous:function(a){this.move("prev",".ui-menu-item:last",a)},first:function(){return this.active&&!this.active.prevAll(".ui-menu-item").length},last:function(){return this.active&&!this.active.nextAll(".ui-menu-item").length},move:function(a,b,f){if(this.active){a=this.active[a+"All"](".ui-menu-item").eq(0);
a.length?this.activate(f,a):this.activate(f,this.element.children(b))}else this.activate(f,this.element.children(b))},nextPage:function(a){if(this.hasScroll())if(!this.active||this.last())this.activate(a,this.element.children(".ui-menu-item:first"));else{var b=this.active.offset().top,f=this.element.height(),c=this.element.children(".ui-menu-item").filter(function(){var d=e(this).offset().top-b-f+e(this).height();return d<10&&d>-10});c.length||(c=this.element.children(".ui-menu-item:last"));this.activate(a,
c)}else this.activate(a,this.element.children(".ui-menu-item").filter(!this.active||this.last()?":first":":last"))},previousPage:function(a){if(this.hasScroll())if(!this.active||this.first())this.activate(a,this.element.children(".ui-menu-item:last"));else{var b=this.active.offset().top,f=this.element.height();result=this.element.children(".ui-menu-item").filter(function(){var c=e(this).offset().top-b+f-e(this).height();return c<10&&c>-10});result.length||(result=this.element.children(".ui-menu-item:first"));
this.activate(a,result)}else this.activate(a,this.element.children(".ui-menu-item").filter(!this.active||this.first()?":last":":first"))},hasScroll:function(){return this.element.height()<this.element.attr("scrollHeight")},select:function(a){this._trigger("selected",a,{item:this.active})}})})(jQuery);
;

;(function($, undefined) {
    
    var tag2attr = {
        a       : 'href',
        img     : 'src',
        form    : 'action',
        base    : 'href',
        script  : 'src',
        iframe  : 'src',
        link    : 'href'
    },
    
	key = ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","fragment"], // keys available to query

	aliases = { "anchor" : "fragment" }, // aliases for backwards compatability

	parser = {
		strict  : /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,  //less intuitive, more accurate to the specs
		loose   :  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ // more intuitive, fails on relative paths and deviates from specs
	},

	querystring_parser = /(?:^|&|;)([^&=;]*)=?([^&;]*)/g, // supports both ampersand and semicolon-delimted query string key/value pairs

	fragment_parser = /(?:^|&|;)([^&=;]*)=?([^&;]*)/g; // supports both ampersand and semicolon-delimted fragment key/value pairs

	function parseUri( url, strictMode )
	{
		var str = decodeURI( url ),
		    res   = parser[ strictMode || false ? "strict" : "loose" ].exec( str ),
		    uri = { attr : {}, param : {}, seg : {} },
		    i   = 14;

		while ( i-- )
		{
			uri.attr[ key[i] ] = res[i] || "";
		}

		// build query and fragment parameters

		uri.param['query'] = {};
		uri.param['fragment'] = {};

		uri.attr['query'].replace( querystring_parser, function ( $0, $1, $2 ){
			if ($1)
			{
				uri.param['query'][$1] = $2;
			}
		});

		uri.attr['fragment'].replace( fragment_parser, function ( $0, $1, $2 ){
			if ($1)
			{
				uri.param['fragment'][$1] = $2;
			}
		});

		// split path and fragement into segments

        uri.seg['path'] = uri.attr.path.replace(/^\/+|\/+$/g,'').split('/');
        
        uri.seg['fragment'] = uri.attr.fragment.replace(/^\/+|\/+$/g,'').split('/');
        
        // compile a 'base' domain attribute
        
        uri.attr['base'] = uri.attr.host ? uri.attr.protocol+"://"+uri.attr.host + (uri.attr.port ? ":"+uri.attr.port : '') : '';
        
		return uri;
	};

	function getAttrName( elm )
	{
		var tn = elm.tagName;
		if ( tn !== undefined ) return tag2attr[tn.toLowerCase()];
		return tn;
	}

	$.fn.url = function( strictMode )
	{
	    var url = '';

	    if ( this.length )
	    {
	        url = $(this).attr( getAttrName(this[0]) ) || '';
	    }

        return $.url( url, strictMode );
	};

	$.url = function( url, strictMode )
	{
	    if ( arguments.length === 1 && url === true )
        {
            strictMode = true;
            url = undefined;
        }
        
        strictMode = strictMode || false;
        url = url || window.location.toString();
        	    	            
        return {
            
            data : parseUri(url, strictMode),
            
            // get various attributes from the URI
            attr : function( attr )
            {
                attr = aliases[attr] || attr;
                return attr !== undefined ? this.data.attr[attr] : this.data.attr;
            },
            
            // return query string parameters
            param : function( param )
            {
                return param !== undefined ? this.data.param.query[param] : this.data.param.query;
            },
            
            // return fragment parameters
            fparam : function( param )
            {
                return param !== undefined ? this.data.param.fragment[param] : this.data.param.fragment;
            },
            
            // return path segments
            segment : function( seg )
            {
                if ( seg === undefined )
                {
                    return this.data.seg.path;                    
                }
                else
                {
                    seg = seg < 0 ? this.data.seg.path.length + seg : seg - 1; // negative segments count from the end
                    return this.data.seg.path[seg];                    
                }
            },
            
            // return fragment segments
            fsegment : function( seg )
            {
                if ( seg === undefined )
                {
                    return this.data.seg.fragment;                    
                }
                else
                {
                    seg = seg < 0 ? this.data.seg.fragment.length + seg : seg - 1; // negative segments count from the end
                    return this.data.seg.fragment[seg];                    
                }
            }
            
        };
        
	};

})(jQuery);


var cache = {},
		lastXhr;
		
var functionMap = {
    'hw_init_info_window_album': hw.initInfoWindowAlbum,
    'hw_init_info_window': hw.initInfoWindow,
    'hw_init_color_choose': hw.initColorChoose,
    'hw_init_sort_box': hw.initSortBox,
    'hw_set_append_url': hw.setAppendURL,
    'init_ajax_navigation': hw.initAjaxNavigation,
    'init_add_fav': hw.initAdFav,
    'init_pallete_filter': hw.initPalleteFilter,
    'init_npallete_filter': hw.initNPalleteFilter,
    'hw_init_comment_translations': hw.initCommentTranslations,
    'hw_init_public_upload': hw.initPublicUpload,
    'hw_init_similar_image': hw.initSimilarImage,
    'hw_init_public_upload_zip': hw.initUploadZip,
    'hw_init_fb_album_import': hw.initFBAlbumImport,
    'init_info_art_menu':function(){
       $('.top-artists-menu a').click(function(){
            $('.swtch-cnt').hide();
            $('#'+$(this).attr('rel')).show();    
            $('.top-artists-menu a').removeClass('selected');
            $(this).addClass('selected');
            return false;
        });
        
        if ($.url().fsegment(1) != undefined && $.url().fsegment(1) == 'show'){ 
            
            el = $('#c-'+$.url().fsegment(2));
            $('.swtch-cnt').hide();
            $('#'+el.attr('rel')).show();    
            $('.top-artists-menu a').removeClass('selected');
            el.addClass('selected');
        }
    },
    'hw_init_similar_image_get': hw.initSimilarGet,
    'hw_init_map_account': function() { 
        $('input[name=CreateAccount]').change(function(){    
            if ($(this).val() == 3){
                $('.map-login').fadeIn();
                $('input[name=Username]').focus();
            } else {
                $('.map-login').fadeOut();
            }
        });
    },
    'hw_init_markit_up': function(a,b) {    
        mySettings.previewParserPath = a;
        $(b).markItUp(mySettings);    
    },
    'hw_init_advanced_search': function() {    
       
        $('input[name=AllLanguages]').change(function(){
            if ($(this).is(':checked')){
                $('.language-custom').removeAttr('checked','checked');
            };
        });
        
        $('.language-custom').change(function(){
            if ($(this).is(':checked')){
                $('input[name=AllLanguages]').removeAttr('checked','checked');
            };
            
            if ($('.language-custom:checked').size() == 0){
                $('input[name=AllLanguages]').attr('checked','checked');
            }
        });
       
    },
    'hw_init_info_word' : function(a,bi) { 
        $('#sentence-list-id span, .words-block span').attr('title',bi);
        $('#sentence-list-id span, .words-block span').click(function() {		       
    	       var inst = $(this);
    	       hw.delayTime = setTimeout(function(){ 
                        	           
                    if (hw.fetchingInfo == inst.attr('rel')) return;	    
            	    hw.fetchingInfo = inst.attr('rel');	    
            	    clearTimeout(hw.myTimer);	    
            		$.getJSON(a+inst.attr('rel'), {} , function(data) {	
            		    $('#imageInfoWindow').remove()
            		    inst.prepend(data.result);
            		    $('#imageInfoWindow').fadeIn('fast');
            		    $('#imageInfoWindow').mouseleave(function() {		    
            		        hw.myTimer = setTimeout(function(){
                                $('#imageInfoWindow').fadeOut();
                                hw.fetchingInfo = false;
                            },250);
            		    });		    
            		    $('#imageInfoWindow').mouseenter(function(){clearTimeout(hw.myTimer);});		
            		});            		  	            
                    
    	       },400);
        }).mouseleave(function(){
              clearTimeout(hw.delayTime);
        });
        
        
    },
    'init_anaglyph_action': function(a){ $('.ad-anaglyph').colorbox(a);}
};
var _lactq = _lactq || [];

$(document).ready(function() { 
     
    $.each(_lactq, function(index, value) {
        functionMap[value.f].apply(hw,value.a);       
    });    
            
    $('#searchtext').focus();  
	
});