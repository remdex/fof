<div class="header-list"><h1>New article</h1></div>

<div class="static-article">
	<form action="<?=erLhcoreClassDesign::baseurl('article/new')?>/<?=$category_id?>" method="post" enctype="multipart/form-data">   
		<?php include_once(erLhcoreClassDesign::designtpl('lharticle/edit_form_article.tpl.php'));?>
	</form>
</div>