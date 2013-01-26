<div class="right mtime">Last modification: <?=htmlspecialchars($article->mtime_front)?></div>

<h1><?=htmlspecialchars($article->article_name)?></h1>

<?php if ($article->has_photo == 1) : ?>
<div class="attr-img">
<img src="<?=$article->thumb_article?>" alt="" />
</div>
<?php endif;?>
<div class="pb10">	
	<?=$article->intro?>	
	<?=$article->body?>
	<div class="read-more">
        <a href="javascript:history.go(-1)" class="button">Back</a>
    </div>    	
</div>		
		