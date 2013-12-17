<div id="content">
	<?php include 'db_menu.php'; ?>
	<h2>Drop</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	<div class="waringInfo">
		Warning: Are you sure you want to drop the database `<?php echo $_dbname ?>`
	</div>
	
	<form method="post" action="<?php echo base_url('DB/drop/'.$_dbname); ?>">
		<input type="hidden" name="_"/>
		<center><button type="submit" id="exc">Drop!!!</button></center>
	</form>
</div>
		