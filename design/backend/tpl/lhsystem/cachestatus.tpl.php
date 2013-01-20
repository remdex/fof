<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Cache status');?></h1>



<table class="lentele" width="100%" cellpadding="0" cellspacing="0">
<thead>
    <tr>
        <th>Name</th>
        <th>Version</th>
        <th>Additional info</th>
    </tr>
</thead>
    <tr>
        <td>Site version</td>
        <td><?=$site_version?></td>
        <td>Some application parts uses it as global cache key</td>
    </tr>            
</table>

<a class="tiny button round" href="<?=erLhcoreClassDesign::baseurl('system/expirecache')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Clean all cache');?></a>

</fieldset>

