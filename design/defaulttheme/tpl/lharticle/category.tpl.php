<h1><?=htmlspecialchars($category->category_name)?></h1>

<?=$category->intro?>

<?php if ($category->hide_articles == 0) : ?>

<ul class="news-list">
<? foreach ($list as $Article) : ?>
<li>

    <div class="right ptime"><?=$Article->ptime_front;?></div>
    
    <h2><?=htmlspecialchars($Article->article_name)?></h2>
      
    <?=$Article->intro?>
   
    <?php if ($Article->body != '') : ?>
    <a href="<?=$Article->url_article?>">Read more &raquo;</a>
    <?php endif; ?>
    
</li>
<? endforeach; ?>
</ul>

<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>

<?php endif; ?>