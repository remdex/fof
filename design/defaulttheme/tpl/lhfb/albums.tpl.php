<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('fb/album','My facebook albums')?></h1>
</div>

<?php foreach ($albums['data'] as $album) : ?>
    <div class="album-thumb">
        <div class="content">        
            <div class="albthumb-img">
            <a title="<?=htmlspecialchars($album['name'])?>" href="<?=erLhcoreClassDesign::baseurl('fb/album')?>/<?=$album['id']?>"> 
                <img src="https://graph.facebook.com/<?=$album['cover_photo']?>/picture?type=album&access_token=<?=$facebook->getAccessToken()?>" alt="" width="130" height="140">
            </a>
            </div>
            <div class="album-attr">
                <div class="tit-itema">
                <h2><a title="<?=htmlspecialchars($album['name'])?>" href="<?=erLhcoreClassDesign::baseurl('fb/album')?>/<?=$album['id']?>"><?=htmlspecialchars($album['name'])?></a></h2>      
                </div>
                <span class="files-ico" title="files"><?=$album['count']?></span>
               <span class="right"><?php echo date('Y-m-d H:i',strtotime($album['updated_time']))?></span>       
            </div>
       </div>
    </div>
<?php endforeach; ?>
