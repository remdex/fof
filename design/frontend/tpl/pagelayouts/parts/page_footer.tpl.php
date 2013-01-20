<div class="row nav">
    <div class="twelve columns">
        <ul class="nav-bar">                   
            <?php 
            $modals = array();
            foreach(erLhcoreClassModelArticle::getList(array('filter' => array('category_id' => 1))) as $article) : ?>
                <?php if ($article->is_modal == 1) : $modals[$article->alias_url] = $article->intro;  ?>
                	<li><a href="#" id="<?php echo $article->alias_url?>-link"><?php echo htmlspecialchars($article->article_name); ?></a></li>
                <?php else : ?>
                	<li><a href="<?php echo $article->url_article?>"><?php echo htmlspecialchars($article->article_name); ?></a></li>
                <?php endif?>                    
            <?php endforeach;?>
        </ul>
    </div>
</div>
        
        
<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::designJS('js/app.js');?>"></script>

<?php if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' ) == true) {
		$debug = ezcDebug::getInstance(); 
		echo $debug->generateOutput(); 
} ?>