<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Cache status');?></legend>



<table class="lentele" cellpadding="0" cellspacing="0">
    <tr>
        <th>Name</th>
        <th>Version</th>
        <th>Additional info</th>
    </tr>
    <tr>
        <td>Last hits cache version</td>
        <td><?=date('Y-m-d H:i:s',$last_hits_version)?> (<?=$last_hits_version?>)</td>
        <td>Expires every 10 minutes</td>
    </tr>
    <tr>
        <td>Most popular version</td>
        <td><?=date('Y-m-d H:i:s',$most_popular_version)?> (<?=$most_popular_version?>)</td>
        <td>Expires every 25 minutes</td>
    </tr>
    <tr>
        <td>Popular recent version</td>
        <td><?=date('Y-m-d H:i:s',$popularrecent_version)?> (<?=$popularrecent_version?>)</td>
        <td>Expires every 10 minutes</td>
    </tr>
    <tr>
        <td>Top rated recent version</td>
        <td><?=$ratedrecent_version?></td>
        <td></td>
    </tr>
    <tr>
        <td>Top rated version</td>
        <td><?=$top_rated?></td>
        <td></td>
    </tr>
    <tr>
        <td>Last commented</td>
        <td><?=$last_commented?></td>
        <td></td>
    </tr>
    <tr>
        <td>Last rated</td>
        <td><?=$last_rated?></td>
        <td></td>
    </tr>
    <tr>
        <td>Site version</td>
        <td><?=$site_version?></td>
        <td>Some application parts uses it as global cache key</td>
    </tr>            
</table>

<a href="<?=erLhcoreClassDesign::baseurl('system/expirecache')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Clean all cache');?></a>

</fieldset>

<br />

<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Abum cache status');?></legend>
<p class="explain">With help of this window you can get information about custom album cache versions.</p>

<input type="text" id="albumID" class="default-input" value="" /> <input type="button" class="default-button" name="GetCache" onclick="hw.getalbumcacheinfo($('#albumID').val())" value="Get information" />
<div id="information-block-album">
Enter album ID above and press Get information
</div>

</fieldset>

<br />

<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Category cache status');?></legend>
<p class="explain">With help of this window you can get information about custom category cache versions.</p>

<input type="text" id="categoryID" class="default-input" value="" /> <input type="button" class="default-button" name="GetCache" onclick="hw.getcategorycacheinfo($('#categoryID').val())" value="Get information" />
<div id="information-block-category">
Enter category ID above and press Get information
</div>

</fieldset>

<br />

<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Image cache status');?></legend>

<input type="text" id="imageID" class="default-input" value="" />
<input type="button" class="default-button" name="GetCache" onclick="hw.clearimagecache($('#imageID').val())" value="Clear image cache" />

</fieldset>