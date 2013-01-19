<div class="header-list"><h1>Article edit - <?=htmlspecialchars($article->article_name)?></h1></div>

<div class="static-article">
	<form action="<?=erLhcoreClassDesign::baseurl('article/editarticle')?>/<?=$article->id?>" method="post" enctype="multipart/form-data">   
  		<?php include_once(erLhcoreClassDesign::designtpl('lharticle/edit_form_article.tpl.php'));?>
    </form>
</div>