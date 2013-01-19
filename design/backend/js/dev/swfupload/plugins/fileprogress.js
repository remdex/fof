/*
	A simple class for displaying file information and progress
	Note: This is a demonstration only and not part of SWFUpload.
	Note: Some have had problems adapting this class in IE7. It may not be suitable for your application.
*/

// Constructor
// file is a SWFUpload file object
// targetID is the HTML element id attribute that the FileProgress HTML structure will be added to.
// Instantiating a new FileProgress object with an existing file will reuse/update the existing DOM elements
function FileProgress(file, targetID, swfUploadInstance) {
	
	
	
	this.fileProgressID = file.id;

	this.opacity = 100;
	this.height = 0;
	this.swfUploadInstance = swfUploadInstance;
	
    this.status = '';
    this.cancelUpload = false;
	this.wwwDir = WWW_DIR_JAVASCRIPT;
	
	
	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if (!this.fileProgressWrapper) {
		
		var fileName = file.name;
		var instance = this;
		
		
		$.postJSON(this.wwwDir + 'gallery/fileuploadcontainer/'+this.fileProgressID+"/"+$('#AlbumIDToUpload').val(),{'filename':fileName},function(data){
            		  
		    if (data.error == false)  
		    {
    			$('#'+targetID).append(data.result);		
    			instance.attatchActions();
    			
    			if (instance.status != '')
    			{
    			    instance.setStatus(instance.status);
    			    instance.status = '';
    			}		    
    			
    			if (instance.cancelUpload == true)
    			{   
    			    $('#progressContainer'+file.id).attr('class','progressContainer red');
                	$('#progressBarInProgress'+file.id).attr('class','progressBarError');
                	$('#'+file.id).fadeOut(5000,function(){	    
                	    $(this).remove();	    
                	});
                	
                	if (swfUploadInstance.getStats().files_queued === 0) {
                	    fileconverter.flashQueCancel(); 
			        }
    			}		    
    			
		    } else {
		        		     
		        $('#'+targetID).append(data.result);
		        
		        $('#progressContainer'+file.id).attr('class','progressContainer red');
            	$('#progressBarInProgress'+file.id).attr('class','progressBarError');
            	$('#'+file.id).fadeOut(5000,function(){	    
            	    $(this).remove();	    
            	});
                		        		    
		        swfUploadInstance.cancelUpload(file.id);
		        
		        if (swfUploadInstance.getStats().files_queued === 0) {		            
		            fileconverter.flashQueCancel();		            
			    }
		    }	
			
		});
			
		
	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
		this.reset();
	}
	
}

FileProgress.prototype.setTimer = function (timer) {
	this.fileProgressElement["FP_TIMER"] = timer;
};
FileProgress.prototype.getTimer = function (timer) {
	return this.fileProgressElement["FP_TIMER"] || null;
};

FileProgress.prototype.reset = function () {
	$('#progressContainer'+this.fileProgressID).attr('class','progressContainer');	
	$('#progresStatus'+this.fileProgressID).html("&nbsp;");
	$('#progresStatus'+this.fileProgressID).attr("class","progressBarStatus");
		
	$('#progressBarInProgress'+this.fileProgressID).css({width:'0%'});
	$('#progressBarInProgress'+this.fileProgressID).attr('class','progressBarInProgress');
	
};

FileProgress.prototype.setProgress = function (percentage) {
	
	$('#progressContainer'+this.fileProgressID).attr('class','progressContainer green');	
	$('#progressBarInProgress'+this.fileProgressID).attr('class','progressBarInProgress');
	$('#progressBarInProgress'+this.fileProgressID).css({'width':percentage + "%"});	
};

FileProgress.prototype.setComplete = function () {
	
	$('#'+this.fileProgressID).fadeOut(2000,function(){	    
	    $(this).remove();	    
	});	
};
FileProgress.prototype.setError = function () {
	  
	$('#progressContainer'+this.fileProgressID).attr('class','progressContainer red');
	$('#progressBarInProgress'+this.fileProgressID).attr('class','progressBarError');
	$('#'+this.fileProgressID).fadeOut(2000,function(){	    
	    $(this).remove();	    
	});
	
    this.cancelUpload = true;
};
FileProgress.prototype.setCancelled = function () {
	
	$('#'+this.fileProgressID).fadeOut(2000,function(){	    
	    $(this).remove();	    
	});
	
};

FileProgress.prototype.setStatus = function (status) {  
           
	   $('#progresStatus'+this.fileProgressID).html(status);   
        this.status = status;
};

// Show/Hide the cancel button
FileProgress.prototype.toggleCancel = function (show, swfUploadInstance) {
		
	if (show)
	{
		$('#cancelLink'+this.fileProgressID).show();
	} else {
		$('#cancelLink'+this.fileProgressID).hide();
	}
};

FileProgress.prototype.attatchActions = function()
{
	var swfUploadInstance = this.swfUploadInstance;
	var fileID = this.fileProgressID;
	$('#cancelLink'+this.fileProgressID).click(function(){	
		swfUploadInstance.cancelUpload(fileID);
		return false;
	});

	swfUploadInstance.addFileParam(fileID,'title',$('#PhotoTitle'+this.fileProgressID).val());
	swfUploadInstance.addFileParam(fileID,'keyword',$('#PhotoKeyword'+this.fileProgressID).val());
	swfUploadInstance.addFileParam(fileID,'description',$('#PhotoDescription'+this.fileProgressID).val());
	swfUploadInstance.addFileParam(fileID,'anaglyph',$('#PhotoAnaglyph'+this.fileProgressID).attr('checked'));
	
	$('#PhotoTitle'+this.fileProgressID).change(function(){
	    swfUploadInstance.addFileParam(fileID,'title',$(this).val())
	});
	
	$('#PhotoKeyword'+this.fileProgressID).change(function(){
	    swfUploadInstance.addFileParam(fileID,'keyword',$(this).val())
	});
	
	$('#PhotoDescription'+this.fileProgressID).change(function(){
	    swfUploadInstance.addFileParam(fileID,'description',$(this).val())
	});
	
	$('#PhotoAnaglyph'+this.fileProgressID).change(function(){
	    swfUploadInstance.addFileParam(fileID,'anaglyph',$(this).attr('checked'))
	});
	
}