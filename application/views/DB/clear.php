<div id="content">
	<?php include 'db_menu.php'; ?>
	<h2>Clear</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	<div class="waringInfo">
		Warning: Are you sure you want to clear the database all collections `<?php echo $_dbname ?>`
	</div>
	<form method="post" action="<?php echo base_url('DB/clear/'.$_dbname); ?>">
		<input type="hidden" name="_"/>
		<center><button type="submit" id="exc">Clear!!!</button></center>
	</form>
</div>
		