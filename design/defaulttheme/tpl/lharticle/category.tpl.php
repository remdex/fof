<h1><?=$category->category_name?></h1>

<? foreach ($list as $Article) : ?>
<div class="article-item">
<div class="right"><?=$Article->ptime_front;?></div>
<h2><?=htmlspecialchars($Article->article_name)?></h2>
    <div class="attribute-short">
        <?=$Article->intro?>
    </div>
    <div>
    <a href="<?=$Article->url_article?>">Read more</a>
    </div>
</div>
<? endforeach; ?>

<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>