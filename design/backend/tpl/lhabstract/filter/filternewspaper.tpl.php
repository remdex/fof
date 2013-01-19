<div class="admin-filter">
	<form action="<?=erLhcoreClassDesign::baseurl('abstract/list')?>/Newspaper" method="get">
		<label>Name: <input type="text" value="<?=isset($filter_params['filterlike']['name']) ? htmlspecialchars($filter_params['filterlike']['name']) : ''?>" class="default-input" name="name" /></label> 
		<input type="submit" name="filter" value="Search" class="default-button" />
	</form>
</div>