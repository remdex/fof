<div class="picture-voting">
<div class="sub-header">
<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Rate this picture')?></h3>
</div>
<div id="vote-content">
    <label><input type="radio" checked="checked" value="1" name="Voting" />(1 <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','star')?>)</label> <label><input type="radio" value="2" name="Voting" /> (2 <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','stars')?>)</label> <label><input type="radio" value="3" name="Voting" />(3 <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','stars')?>)</label> <label><input type="radio" value="4" name="Voting" />(4 <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','stars')?>)</label> <label><input type="radio" value="5" name="Voting" />(5 <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','stars')?>)</label>
    <input type="button" class="default-button" name="AddVote" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Vote!')?>" onclick="hw.vote(<?=$image->pid?>,$('input[name=Voting]:checked').val())"/>
    <input type="button" class="default-button" name="ResetVote" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Deduct vote!')?>" onclick="hw.deductvote(<?=$image->pid?>)"/>
</div>
</div>