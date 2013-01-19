<div class="header-list">
<h1><?=htmlspecialchars($album->title)?></h1>
</div>
 
<form action="" method="post">
          
<table>
    <tr>
        <td>Resolution</td>
        <td>Enter album name</td>
    </tr>
    <tr>
        <td valign="top">        
        <select name="ResolutionSource">
        <?php foreach (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'resolutions' ) as $resolution) : ?>
            <option value="<?=$resolution['width']?>x<?=$resolution['height']?>"><?=$resolution['width']?>x<?=$resolution['height']?></option>
        <?php endforeach;?>
        </select>        
        </td>
        <td valign="top">        
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top"><div><input type="text" class="default-input newAlbumName" value="" > <input class="default-button" type="button" value="Search album" /></div> </td>
                    <td><div id="album_select_directory0"></div></td>
                </tr>
            </table>
        </td>
        <td valign="top">
        <input type="submit" class="default-button" value="Move photos" id="moveAction" style="display:none" name="moveSelectedPhotos" /> 
        </td>
    </tr>
    
</table>
</form>
  
  
<?php if (isset($effected_images)) : ?>
<h2>We moved <?=$effected_images?> images with <?=htmlspecialchars($filter_resolution)?> resolution</h2>
<?php endif;?>

<script>
$('.newAlbumName').change(function(){	
    if ($(this).val() != '') {
	$.getJSON("<?=erLhcoreClassDesign::baseurl('gallery/albumnamesuggest')?>/0/"+escape($(this).val()), {} , function(data){	
                   $('#album_select_directory0').html(data.result);                       
                   if (data.error == 'false'){
                        $('#album_select_directory0 input').eq(0).attr("checked","checked");
                        $('#moveAction').show();
                   } else {
                       $('#moveAction').hide();
                   }                       
    	});	
    }
});

</script>
  
  

