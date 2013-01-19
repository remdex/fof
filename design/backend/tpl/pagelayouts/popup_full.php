<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="margin:0;background:#FFFFFF;">
<head>
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>
<style>
html {
	height: 100%;
}
body {
	margin: 0;
	padding: 0;
	height: 100%;
}
#content {
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	padding: 10px;
	margin: auto;
	min-height: 80%;
}
</style>
</head>
<body style="margin:0;background:#FFFFFF;">	 
    <div id="content">       	
		 <?					
		     echo $Result['content'];		
		 ?>	
	</div>
</body>
</html>