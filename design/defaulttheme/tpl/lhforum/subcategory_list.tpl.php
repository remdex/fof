<table width="100%" cellpadding="0" cellspacing="0">
<tr class="header-row">
<th class="hitem" width="99%">
<h1><a href="<?=$category->path_url?>"><?=htmlspecialchars($category->name)?></a></h1>
<? if ($category->description != '') : ?>
<p class="cat-desc"><?=$category->description?></p>
<?endif;?>
</div>
</th>
<th class="top-th" width="1%">Topics</th>
<th class="msg-th" width="1%">Messages</th>
<th class="lst-th" width="1%">Last</th>
</tr>
<?php 
$counter = 0;
foreach ($subcategorys as $subcategory) : ?>   
    <tr class="forumsubcategory">
        <td> <h3><a href="<?=$subcategory->path_url?>"><?=htmlspecialchars($subcategory->name)?></a></h3>
        <p class="cat-desc">        
        <? if ($subcategory->description != '') : ?>
        <?=$subcategory->description?>  
        <?endif;?>
        <?php 
if (is_array($subcategory->subcategorys)) :
$counter = 0;
foreach ($subcategory->subcategorys as $subcat) : ?><?php if ($counter > 0) : ?>, <?php endif;?><a href="<?=$subcat->path_url?>"><?=htmlspecialchars($subcat->name)?></a><?php $counter++;endforeach;endif;?>
        </p>
        </td>
        <td class="ptd"> <span class="topic-count">
        <?=$subcategory->topic_count;?>
        </span>
              </td>
        <td class="ptd"> <span class="msg-count">
        <?=$subcategory->message_count;?>
        </span></td>
        <td class="ptd"><span class="last-msg">
        <?php if ($subcategory->last_message_category !== false && $subcategory->last_message_category->user !== false) : ?>
            <a href="<?=$subcategory->last_message_category->topic->url_path?>"><?=$subcategory->last_message_category->user->username?></a>
            <br />
            <span class="ctime"><?=$subcategory->last_message_category->ctime_front?></span>
            
        <?php else : ?>
        -
        <?php endif;?>
        </span></td>
    </tr>
<?php endforeach;?>  
</table>