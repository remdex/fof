<div class="header-list"><h1>Article edit - <?=htmlspecialchars($article->article_name)?></h1></div>

<div class="static-article">
	<form action="<?=erLhcoreClassDesign::baseurl('article/editarticle')?>/<?=$article->id?>" method="post" enctype="multipart/form-data">   
	    <?php if (isset($errors)) : ?>
        		<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
        <?php endif; ?>
        
        <?php if (isset($updated)) : ?>
                <?php $msg = 'Article was updated'?>
        		<?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
        <?php endif; ?>

  		<?php include_once(erLhcoreClassDesign::designtpl('lharticle/edit_form_article.tpl.php'));?>
    </form>
</div>