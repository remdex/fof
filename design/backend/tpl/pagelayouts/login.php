<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>
</head>

<div class="row">
    <div class="columns twelve"> 			 
		 <?php echo $Result['content']; ?>	
    </div>
</div>
	
<div class="row">
    <div class="columns twelve"> 	
        <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>
    </div>
</div>


</body>
</html>