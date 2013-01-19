<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Watermark configuration')?></legend>


<? if (isset($data_updated)) : ?>
	<div class="dataupdate"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Settings updated');?></div>
<? endif; ?>


<form method="post" action="<?=erLhcoreClassDesign::baseurl('systemconfig/watermark')?>" enctype="multipart/form-data">

<?php $data = $systemconfig->data; ?>
<?php if (isset($data['watermark']) && $data['watermark'] != '') : ?>
<div style="width:300px;height:300px;overflow:auto;background-color:#CCCCCC;"><img src="<?=erLhcoreClassSystem::instance()->wwwDir().'/var/watermark/'.$data['watermark']?>" alt="" /></div>
<label><input type="checkbox" name="DeleteWaterMark" value="on" /><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Delete watermark');?></label>
<?php endif;?>

<div class="in-blk">
<label><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Image, it is recommended PNG type image');?></strong></label>
<input type="file" name="WatermarkFile" value="" />
</div>

<br />
<div class="in-blk">
<label><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Settings');?></strong></label>
<label><input type="radio" value="thumbnails_none" name="WatermarkEnabled"  <?= (isset($data['watermark_disabled']) && $data['watermark_disabled'] == true) ? 'checked="checked"' : '' ?> /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Watermark disabled');?></label>
<label><input type="radio" value="thumbnails" name="WatermarkEnabled"  <?= (isset($data['watermark_enabled']) && $data['watermark_enabled'] == true) ? 'checked="checked"' : '' ?> /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Watermark enabled for normal size thumbnails');?></label>
<label><input type="radio" value="thumbnails_all" name="WatermarkEnabled"  <?= (isset($data['watermark_enabled_all']) && $data['watermark_enabled_all'] == true) ? 'checked="checked"' : '' ?> /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Watermark enabled for all images');?></label><br />
</div>

<div class="in-blk">
<label><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Watermark position');?></strong></label>
<label><input type="radio" value="top_left" name="WatermarkPosition"  <?= (isset($data['watermark_position']) && $data['watermark_position'] == 'top_left') ? 'checked="checked"' : '' ?> /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Top left');?></label>
<label><input type="radio" value="top_right" name="WatermarkPosition"  <?= (isset($data['watermark_position']) && $data['watermark_position'] == 'top_right') ? 'checked="checked"' : '' ?> /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Top right');?></label>
<label><input type="radio" value="bottom_left" name="WatermarkPosition"  <?= (isset($data['watermark_position']) && $data['watermark_position'] == 'bottom_left') ? 'checked="checked"' : '' ?> /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Bottom left');?></label>
<label><input type="radio" value="bottom_right" name="WatermarkPosition"  <?= (isset($data['watermark_position']) && $data['watermark_position'] == 'bottom_right') ? 'checked="checked"' : '' ?> /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Bottom right');?></label>
<label><input type="radio" value="center_center" name="WatermarkPosition"  <?= (isset($data['watermark_position']) && $data['watermark_position'] == 'center_center') ? 'checked="checked"' : '' ?> /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Center');?></label>
</div>

<div class="in-blk">
<label><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Watermark padding from borders');?></strong></label>
X <input type="text" name="PaddingX" value="<?=isset($data['watermark_position_padding_x']) ? (int)$data['watermark_position_padding_x'] : 10?>" class="default-input"/> Y
<input type="text" name="PaddingY" value="<?=isset($data['watermark_position_padding_y']) ? (int)$data['watermark_position_padding_y'] : 10?>" class="default-input"/>
</div>
<br />

<input type="submit" class="default-button" name="UpdateConfig" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Update')?>"/>

</form>

</fieldset>