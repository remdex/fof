<div class="left-infobox">                    
    <h3><a href="<?=erLhcoreClassDesign::baseurl('gallery/lasthits')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last viewed images')?> &raquo;</a></h3>
    <?php 
    $cache = CSCacheAPC::getMem();
    $cacheVersion = $cache->getCacheVersion('last_hits_version',time(),600);
    if (($ResultCache = $cache->restore(md5($cacheVersion.'_lasthits_infobox_siteaccess'.erLhcoreClassSystem::instance()->SiteAccess))) === false)
    {
        $items = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'filter' => array('approved' => 1), 'sort' => 'mtime DESC, pid DESC','offset' => 0, 'limit' => 4));
        $appendImageMode = '/(mode)/lasthits';
        $ResultCache = '<ul class="last-hits-infobox">';
        foreach ($items as $item)
        {
            $ResultCache .= '<li><a href="'.$item->url_path.$appendImageMode.'">';

            if ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE){
                $ResultCache .= '<img title="'.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size').'" src="'.erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename)).'" alt="'.htmlspecialchars($item->name_user).'">';
            } elseif ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeHTMLV || $item->media_type == erLhcoreClassModelGalleryImage::mediaTypeVIDEO) {

                if ($item->has_preview) {
                    $ResultCache .= '<img title="'.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size').'" src="'.erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode(str_replace(array('.ogv','.avi','.mpg','.mpeg','.wmv'),'.jpg',$item->filename))).'" alt="'.htmlspecialchars($item->name_user).'">';
                } else {
                    $ResultCache .= '<img title="'.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size').'" src="'.erLhcoreClassDesign::design('images/icons/ogv.jpg').'" alt="'.htmlspecialchars($item->name_user).'">';
                }

            } elseif ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeSWF){
                $ResultCache .= '<img title="'.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','See full size').'" src="'.erLhcoreClassDesign::design('images/icons/swf.jpg').'" alt="'.htmlspecialchars($item->name_user).'">';
            }
            $ResultCache .= '</a>';
        }
        
        $ResultCache .= '</ul>';

        $cache->store(md5($cacheVersion.'_lasthits_infobox_siteaccess'.erLhcoreClassSystem::instance()->SiteAccess),$ResultCache);
    }
    echo $ResultCache;
    ?>                                         							
</div>