<div id="content">
	<?php include 'menu.php'; ?>
	<h2>Drop</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	<div class="waringInfo">
		Warning: Are you sure you want to drop the database collection 
		`<?php echo $_dbname ?>`.`<?php echo $_collectionname ?>`
	</div>
	
	<form method="post" action="<?php echo base_url('Collection/drop/'.sprintf('%s/%s',$_dbname,$_collectionname)); ?>">
		<input type="hidden" name="_"/>
		<center><button type="submit" id="exc">Drop!!!</button></center>
	</form>
</div>
		