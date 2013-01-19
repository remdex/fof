        <div class="row">
            <div class="eight columns">

                <h2><?=htmlspecialchars($article->article_name)?></h2>
                
				<?php if ($article->has_photo == 1) : ?>
				<div class="attr-img">
				<img src="<?=$article->thumb_article?>" alt="" />
				</div>
				<?php endif;?>
   
				<div class="article-body">
					
					<?=$article->intro?>
					
					<?=$article->body?>	
					
					<div class="read-more">
				        <a href="javascript:history.go(-1)" class="button large">Back</a>
				    </div>
				    	
				</div>		
				
			</div>
</div>		
