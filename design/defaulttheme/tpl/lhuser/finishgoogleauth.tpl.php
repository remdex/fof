<div class="header-list">
<h1>Google authentification</h1>
</div>

<div class="articlebody">

<? if (isset($msg)) : ?>
    	<div class="error">*&nbsp;<?=$msg;?></div>
<? endif;?>

<? if (isset($success)) : ?>
	<div class="dataupdate"><?=$success?></div>
<? endif; ?>

</div>