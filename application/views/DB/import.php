<div id="content">
	<?php include 'db_menu.php'; ?>
	<h2>Import</h2>
	
	<div id="excInfo"><?php echo @$excInfo ;?></div>
	
	<form method="post" action="<?php echo base_url('DB/Import/'.$_dbname); ?>" enctype="multipart/form-data" >
		<table border="1">
			<tr>
				<th align="left" width="30%">.js file</th>
				<td><input type="file" id="jsfile" name="jsfile"/></td>
			</tr>
		</table>
		<br />
		<center><button type="submit" id="exc">Execute!</button></center>
	</form>
</div>
		