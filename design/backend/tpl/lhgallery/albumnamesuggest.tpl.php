<ul>
<?php foreach ($albums as $album) : ?>
<li><input type="radio" name="AlbumDestinationDirectory<?=$key_directory?>" value="<?=$album->aid?>" /><a href="<?=$album->url_path?>" target="_blank" title="Click to see target, new window will open"><?=htmlspecialchars($album->title)?></a></li>
<?php endforeach;?>
</ul>