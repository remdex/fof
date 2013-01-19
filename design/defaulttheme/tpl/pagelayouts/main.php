<!DOCTYPE html>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>

<div id="container" class="no-right-column">

<div class="p10">
    <div id="main-header-bg" class="float-break">
        
        <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/logo.tpl.php'));?>
        
        <div class="top-menu float-break">        
        <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_top_menu.tpl.php'));?>
        </div>
        
        <div class="top-submenu" >
        <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/search_box.tpl.php'));?>
        <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/lang_box.tpl.php'));?>
        </div>
        
    </div>
</div>


<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/path.tpl.php'));?>

	<div id="bodcont" class="float-break">
	
        <div id="leftmenucont">     
                       
            <?php if (erLhcoreClassUser::instance()->isLogged()) : ?>
            <div class="left-infobox">
            <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/leftmenu_user.tpl.php'));?>	
            </div>
            <?php endif;?>
             
            <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/leftmenu_worda.tpl.php'));?>
               	      
        </div>
                	
		<div id="middcont">
			<div id="mainartcont">
			
			<?
			 echo $Result['content'];		
			?>			
			
			</div>
		</div>
						
	</div>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>
</div>
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head_js.tpl.php'));?> 

