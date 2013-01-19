fc.prototype.startUpload = function(instance)
{
	var swfinst = this.swfinstance;
	
	var thisinstance = this;
		
    $('#ConvertButton').attr('disabled','disabled');
    
	//alert(this.wwwDir);
	$.postJSON(this.wwwDir+"gallery/getsessionarchive/",{},function(data){			
		if (data.error == 'false')
		{	
		    if (swfinst.getStats().files_queued > 0) 			    
		    thisinstance.hasFlashQue = true;
		    	
		    // Test if all removed url files ?		    
		    if (thisinstance.filesQue.length > 0)
		    thisinstance.hasUrlQue = true;
		    
		    $('#errorsList').remove();		    
		    sessionHash = data.sessionhash;
			swfinst.addPostParam('sessionupload',data.sessionhash);
			swfinst.startUpload();				
			thisinstance.startUrlUpload();
			
		}
		else {
		    $('#ConvertButton').removeAttr('disabled');			    
		    if ($('#errorsList').size() == 0) $('#divSWFUploadUI').prepend('<div id="errorsList"></div>');
		    $('#errorsList').html(data.result);
		  
		}			
	});	
}