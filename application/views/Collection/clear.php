<div id="content">
	<?php include 'menu.php'; ?>
	<h2>Clear</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	<div class="waringInfo">
		Warning: Are you sure you want to clear the collections 
		`<?php echo $_dbname ?>`.`<?php echo $_collectionname ?>`
	</div>
	<form method="post" action="<?php echo base_url('Collection/clear/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">
		<input type="hidden" name="_"/>
		<center><button type="submit" id="exc">Clear!!!</button></center>
	</form>
</div>


		