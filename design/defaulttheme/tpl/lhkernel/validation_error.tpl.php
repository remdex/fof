<? if (isset($errors)) : ?>
<div class="alert-box alert"><a href="" class="close">×</a>
<ul>
<?php foreach ($errors as $err) : ?>
    <li><?=$err?></li>
<?php endforeach;?>
</ul>
</div>
<? endif;?>