<div class="feedback-form">

<div class="simple-header">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Feedback')?></h1>
</div>


<? if (isset($errArr)) : ?>
<div class="error-message">
    <? foreach ((array)$errArr as $error) : ?>
    	<div class="error"><span class="requared">*</span>&nbsp;<?=$error;?></div>
    <? endforeach; ?>
</div>
<? endif;?>

<? if (isset($messageSend)) : ?>
<div class="message-ok">
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Feedback was send')?>!
</div>
<? endif; ?>

<div class="form-content">
    <form action="<?=erLhcoreClassDesign::baseurl('feedback/form')?>" method="post">
        <table>
            <tr>
                <td class="td-label"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Name')?> <span class="requared">*</span></td>
                <td><input type="text" name="FormName" class="default-input" value="<?=htmlspecialchars($form_data['FormName'])?>" /></td>
            </tr>
            <tr>
                <td class="td-label"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','E-mail')?> <span class="requared">*</span></td>
                <td><input name="FormEmail" type="text" class="default-input" value="<?=htmlspecialchars($form_data['FormEmail'])?>" /></td>
            </tr>
            <tr>
                <td class="td-label"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Text')?> <span class="requared">*</span></td>
                <td><textarea name="FormText" class="default-textarea"><?=htmlspecialchars($form_data['FormText'])?></textarea></td>
            </tr>
            <tr>
                <td class="td-label"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Captcha image')?></td>
                <td><img src="<?=erLhcoreClassDesign::baseurl('captcha/image/feedback_form')?>" alt="" /></td>
            </tr>
            <tr>
                <td class="td-label"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Captcha code')?> <span class="requared">*</span></td>
                <td><input type="text" value="" class="default-input" name="CaptchaCode" /></td>
            </tr>
            <tr>
                <td colspan="2"><br /><?=erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Required fields')?> (<span class="requared">*</span>)<br /><br /></td>
            </tr>
            <tr>
                <td colspan="2"><input class="default-button" type="submit" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Send')?>" name="SendRequest" /></td>
            </tr>
        </table>
    </form>
</div>
<br />
</div>